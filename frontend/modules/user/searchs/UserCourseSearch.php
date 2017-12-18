<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\user\searchs;

use common\models\CategoryJoin;
use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\Subject;
use common\models\Favorites;
use common\models\StudyLog;
use common\models\Teacher;
use common\models\TeacherCourse;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of UserCourseSearch
 *
 * @author Administrator
 */
class UserCourseSearch 
{
    /** @var array 参数 */
    private $params = [];
    /** @var integer 二级分类id */
    private $par_id;
    /** @var integer|array 分类id */
    private $cat_id;
    /** @var integer 学科id */
    private $sub_id;
    /** @var integer 年级 */
    private $grade;
    /** @var integer 分页 */
    private $page;
    /** @var integer 显示数量 */
    private $limit;
    /** @var boolean 是否为选课 */
    private $is_choice = false;


    /**
     * 构造函数
     */
    public function __construct() 
    {
        $this->params = \Yii::$app->request->queryParams;
    }

    /**
     * 同步课堂
     * @return array
     */
    public function syncSearch()
    {
        //判断是否为老师角色
        if(\Yii::$app->user->identity->isRoleTeacher())
            $this->is_choice = true;
        
        $query = $this->addSearch();
        //查询后的结果
        $subject_result= $this->subjectSearch()->all();
        $course_result = $query->all();
        $study_result = $this->studyLogSearch()->one();
        
        //查课程总数
        $totalCount = count($course_result);
        //课程分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $this->limit]);   
        
        return [
            'filter' => $this->params,              //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $pages,                      //分页
            'totalCount' => $totalCount,            //总数
            'result' => [
                'subject' => $subject_result,       //可选学科
                'courses' => $course_result,        //课程结果
                'study' => $study_result,           //学习结果
            ]
        ];
    }
    
    /**
     * 学科培优/素质提升
     * @param array $params
     * @return array
     */
    public function collegeSearch($params)
    {
        $count = 0;
        $is_join = [];
        //顶级分类
        $cat_id = ArrayHelper::getValue($params, 'cat_id');
        //查询分类下的学院id
        $catIds = CourseCategory::getCatChildrenIds($cat_id); 
        //查询加入人数
        $query = (new Query())->select([
            'Category.id', 'CategoryJoin.category_id AS cate_id',
            'GROUP_CONCAT(DISTINCT CategoryJoin.user_id SEPARATOR \',\') as users',
            'Category.name', 'Category.image', 'COUNT(CategoryJoin.id) AS totalCount'
        ])->from(['Category' => CourseCategory::tableName()]);
        
        $query->leftJoin(['CategoryJoin' => CategoryJoin::tableName()], 'CategoryJoin.category_id = Category.id');
        $query->where(['Category.id' => $catIds]);
        $query->groupBy(['Category.id'])->orderBy(['Category.sort_order' => SORT_ASC]);
        //查询结果
        $join_results = $query->all();
        //计算加入学院的人数和判断该用户是否加入学院
        foreach($join_results as $item){
            $count += $item['totalCount'];
            $is_join[$item['cate_id']] = in_array(Yii::$app->user->id, explode(',', $item['users']));
        }
        
        return [
            'cateJoins' => $join_results,
            'count' => $count,
            'is_join' => $is_join,
        ];
    }
    
    /**
     * 学习轨迹
     * @param array $params
     * @return array
     */
    public function studySearch($params = null)
    {
        //查询学习记录
        $query = (new Query())->select(['StudyLog.id'])->from(['StudyLog' => StudyLog::tableName()]);
        //查询条件
        $query->filterWhere(['StudyLog.user_id' => Yii::$app->user->id]);
        $queryCopy = clone $query;
        //关联查询
        $query->addSelect(['Course.id', 'Course.courseware_name AS cou_name',
            'Course.term','Course.unit','Course.grade','Course.tm_ver',
            'Subject.img AS sub_img','Teacher.img AS tea_img','StudyLog.created_at AS date',
            'IF(Attribute.index_type=1,GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \'|\'),\'\') as attr_values']);
        $query->leftJoin(['Course' => Course::tableName()], 'Course.id = StudyLog.course_id');
        $query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id');  
        $query->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id');
        $query ->leftJoin(['CourseAttr' => CourseAttr::tableName()], 'CourseAttr.course_id = Course.id');
        $query->leftJoin(['Attribute' => CourseAttribute::tableName()], 'Attribute.id = CourseAttr.attr_id');
        $query->orderBy(['StudyLog.created_at' => SORT_DESC]);      //排序
        $totleCount = $queryCopy->all();    //计算总数
        $query->groupBy('StudyLog.id');
        //组装学习日志
        $study_results = [];
        foreach ($query->all() as $item){
            $study_results[date('Y年m月', $item['date'])][date('d日', $item['date'])][] = $item;
        }
        
        return [
            'totleCount' => count($totleCount),
            'studyLog' => $study_results
        ];
    }

    /**
     * 我的收藏
     * @param array $params
     * @return array
     */
    public function favoritesSearch($params = null)
    {
        //查询收藏
        $query = (new Query())
            ->select(['Favorites.id', 'Favorites.course_id', 'Course.courseware_name AS cou_name', 
            'Course.term','Course.unit','Course.grade','Course.tm_ver',
            'Subject.img AS sub_img','Teacher.img AS tea_img',
            'IF(StudyLog.course_id IS NUll || SUM(StudyLog.studytime)/60 < 5,0,1) AS is_study',
            'IF(Attribute.index_type=1,GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \'|\'),\'\') as attr_values'
            ])->from(['Favorites' => Favorites::tableName()]);
        //关联查询
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], 'StudyLog.course_id = Favorites.course_id AND StudyLog.user_id = Favorites.user_id');
        $query->leftJoin(['Course' => Course::tableName()], 'Course.id = Favorites.course_id');
        $query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id');  
        $query->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id');
        $query ->leftJoin(['CourseAttr' => CourseAttr::tableName()], 'CourseAttr.course_id = Course.id');
        $query->leftJoin(['Attribute' => CourseAttribute::tableName()], 'Attribute.id = CourseAttr.attr_id');
        //查询条件
        $query->filterWhere(['Favorites.user_id' => Yii::$app->user->id]);
        //收藏分组
        $query->groupBy('Favorites.id');
        
        return [
            'favorites' => $query->all(),
            'tm_logo' => Course::$tm_logo,
        ];
    }
    
    /**
     * 学习记录搜索
     * @return Query
     */
    public function studyLogSearch()
    {
        //复制对象，为查询对应学习记录
        $query = $this->search()->addSelect(['SUM(StudyLog.studytime) AS studytime']);
        $query->andFilterWhere(['Course.subject_id' => $this->sub_id]);
        if(!$this->is_choice){
            //查询上、下、全一册条件判断
            if(date('n', time()) <= 2 || date('n', time()) >= 9)
                $query->andFilterWhere(['Course.term' => 1]);
            else if(date('n', time()) >= 3 && date('n', time()) <= 8)
                $query->andFilterWhere(['Course.term' => 2]);
            else
                $query->andFilterWhere(['Course.term' => 3]);
        }
        $query->andFilterWhere(['StudyLog.user_id' => \Yii::$app->user->id])->groupBy('Course.id');
        //关联课程学习记录
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], 'StudyLog.course_id=Course.id');
        //查询学习记录
        $stu_query = (new Query())->select(['COUNT(IF(StudyCopy.studytime/60<5,NULL,StudyCopy.studytime)) AS num'])
            ->from(['StudyCopy' => $query]);
        
        return $stu_query;
    }

    /**
     * 学科搜索
     * @return Query
     */
    public function subjectSearch()
    {
        //复制对象，为查询对应学科
        $query = $this->search()->addSelect(['Course.subject_id']);
        //查询学科
        $sub_query = (new Query())->select(['Subject.id', 'Subject.name'])
            ->from(['SubjectCopy' => $query]);
        //关联学科
        $sub_query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = SubjectCopy.subject_id');  
        //学科分组排序
        $sub_query->groupBy('Subject.id')->orderBy('sort_order');
        
        return $sub_query;
    }
    
    /**
     * 课程条件搜索
     * @return Query
     */
    public function search()
    {
        $psub_id = ArrayHelper::getValue($this->params, 'psub_id');
        $psubIds = explode('_', $psub_id);
        $this->par_id = isset($psubIds[0]) ? $psubIds[0] : null;                      //二级分类
        $this->sub_id = isset($psubIds[1]) ? $psubIds[1] : null;                      //学科
        $this->page = ArrayHelper::getValue($this->params, 'page', 1);                //分页
        $this->limit = ArrayHelper::getValue($this->params, 'limit', 20);             //限制显示数量
        //查找所有课程分类id
        $this->cat_id = CourseCategory::getCatChildrenIds($this->par_id);
        //查找课程        
        $query = (new Query())->select(['Course.id'])->from(['Course' => Course::tableName()]);
        //判断是否为选课
        if($this->is_choice)
            $query->rightJoin(['TeacherCourse' => TeacherCourse::tableName()], ['AND', 'TeacherCourse.course_id=Course.id',['TeacherCourse.user_id' => \Yii::$app->user->id]]);
        else{
            $query->where (['Course.is_recommend' => 1]);
            $this->grade = Yii::$app->user->identity->profile->getGrade(false);      //年级
        }
        //查询的必要条件
        $query->andWhere(['Course.is_publish' => 1]);
        $query->andFilterWhere(['Course.grade' => $this->grade]);
        
        return $query;
    }

    /**
     * 补充搜索查询
     * @return array
     */
    public function addSearch()
    {
        //复制对象，为对应属性查询条件
        $query = $this->search();
        //需求条件查询
        $query->andFilterWhere(['Course.cat_id' => $this->cat_id]);
        $query->andFilterWhere(['Course.subject_id' => $this->sub_id]);
        if(!$this->is_choice){
            //查询上、下、全一册条件判断
            if(date('n', time()) <= 2 || date('n', time()) >= 9)
                $query->andFilterWhere(['Course.term' => 1]);
            else if(date('n', time()) >= 3 && date('n', time()) <= 8)
                $query->andFilterWhere(['Course.term' => 2]);
            else
                $query->andFilterWhere(['Course.term' => 3]);
        }
        //额外字段属性
        $query->addSelect(['Course.courseware_name AS cou_name','Course.term','Course.unit','Course.grade','Course.tm_ver','Course.play_count',
            'Subject.img AS sub_img','Teacher.img AS tea_img',
            'IF(Attribute.index_type=1,GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \'|\'),\'\') as attr_values',
            'IF(StudyLog.course_id IS NUll || SUM(StudyLog.studytime)/60<5,0,1) AS is_study']);
        //关联课程分类
        $query->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = Course.cat_id');
        //关联课程学科
        $query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id');  
        //关联课程老师
        $query->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id');
        //关联查询课程属性
        $query->leftJoin(['CourseAttr' => CourseAttr::tableName()],'CourseAttr.course_id = Course.id');
        //关联查询属性
        $query->leftJoin(['Attribute' => CourseAttribute::tableName()],'Attribute.id = CourseAttr.attr_id');
        //关联课程学习记录
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], ['AND','StudyLog.course_id=Course.id',['StudyLog.user_id' => \Yii::$app->user->id]]);
        //按课程id分组
        $query->groupBy(['Course.id']);    
        //课程排序
        $query->orderBy(['Course.courseware_sn' => SORT_ASC, "Course.sort_order" => SORT_ASC]);
        //显示数量 
        //$query->offset(($this->page-1)*$limit)->limit($this->limit);   
             
        return $query;
    }
}
