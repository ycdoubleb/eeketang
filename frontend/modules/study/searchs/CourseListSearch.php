<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\study\searchs;

use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\Subject;
use common\models\StudyLog;
use common\models\Teacher;
use common\models\TeacherCourse;
use common\models\TeacherGet;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Description of CourseListSearch
 *
 * @author Administrator
 */
class CourseListSearch {
    /** @var array 参数 */
    private $params;
    /** @var integer 二级分类id */
    private $par_id;
    /** @var integer 分类id */
    private $cat_id;
    /** @var integer 学科id */
    private $sub_id;
    /** @var integer 学期 */
    private $term;
    /** @var integer 年级 */
    private $grade;
    /** @var string 版本 */
    private $tm_ver;
    /** @var bool 是否学习 */
    private $is_study;
    /** @var string 关键字 */
    private $keywords;
    /** @var string 排序 */
    private $sort_order;
    /** @var array 附加属性 */
    private $attrs;
    /** @var integer 分页 */
    private $page;
    /** @var integer 显示数量 */
    private $limit;

    /**
     * 构造函数
     */
    public function __construct() 
    {
        $this->params = \Yii::$app->request->queryParams;
    }
    
    /**
     * 学院课程搜索
     * @return array
     */
    public function collegeSearch()
    {
        $this->par_id = ArrayHelper::getValue($this->params, 'par_id', null);   
        $query_result = $this->addSearch();
        $query = $query_result['query'];
        //已选的判断条件
        $query->addSelect(['IF(StudyLog.course_id IS NUll || SUM(StudyLog.studytime)/60 < 5,0,1) AS is_study']);
        //关联课程学习记录
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], ['AND',
            'StudyLog.course_id=Course.id',['StudyLog.user_id' => Yii::$app->user->id]
        ]);
        //查询后的结果
        $category_result = $this->categorySearch()->all();
        $subject_result = $this->subjectSearch()->all();
        $copy_result = $this->copySearch()->all();
        $course_result = $query->all();
        
        //用属性的 name 作键分组
        $attr_result = [];
        foreach($this->CourseAttrSearch()->all() as $attr_arr){
            $attr_result[$attr_arr['name']] = [
                'attr_id' => $attr_arr['attr_id'],                       //把相同属性名的属性id组合以'_'字符连接
                'value' => explode(',', $attr_arr['value']),             //合并相同属性值
            ];
        }
        
        return [
            'filter' => $this->params,              //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $query_result['pages'],      //分页
            'totalCount' => $query_result['total'], //总数
            'results' => [
                'category' => $category_result,     //可选分类
                'subject' => $subject_result,       //可选学科
                'term' => explode('_', ArrayHelper::getColumn($copy_result, 'term')[0]),            //可选册数
                'grade' => explode('_', ArrayHelper::getColumn($copy_result, 'grade')[0]),          //可选年级
                'tm_ver' => explode('_', ArrayHelper::getColumn($copy_result, 'tm_ver')[0]),        //可选版本
                'courses' => $course_result,        //课程结果
                'attrs' => $attr_result,            //可选属性
            ]
        ];
    }
    
    /**
     * 观课中心搜索
     * @return array
     */
    public function choiceSearch()
    {
        $par_id = ArrayHelper::getValue($this->params, 'par_id', null);
        $tea_get = TeacherGet::findOne(['category_id' => $par_id]);
        $this->par_id = ArrayHelper::getValue($tea_get, 'category_id', null); 
        if($this->par_id == null)
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        
        $query_result = $this->addSearch();
        $query = $query_result['query'];
        //已选的判断条件
        $query->addSelect(['IF(TeacherCourse.course_id IS NUll,0,1) AS is_choice']);
        //关联已选课程
        $query->leftJoin(['TeacherCourse' => TeacherCourse::tableName()], ['AND',
            'TeacherCourse.course_id=Course.id',['TeacherCourse.user_id' => Yii::$app->user->id]
        ]);
        //查询后的结果
        $category_result = $this->categorySearch()->all();
        $subject_result = $this->subjectSearch()->all();
        $copy_result = $this->copySearch()->all();
        $course_result = $query->all();
        
        //用属性的 name 作键分组
        $attr_result = [];
        foreach($this->CourseAttrSearch()->all() as $attr_arr){
            $attr_result[$attr_arr['name']] = [
                'attr_id' => $attr_arr['attr_id'],                       //把相同属性名的属性id组合以'_'字符连接
                'value' => explode(',', $attr_arr['value']),             //合并相同属性值
            ];
        }
        
        return [
            'filter' => $this->params,              //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $query_result['pages'],      //分页
            'totalCount' => $query_result['total'], //总数
            'results' => [
                'category' => $category_result,     //可选分类
                'subject' => $subject_result,       //可选学科
                'term' => explode('_', ArrayHelper::getColumn($copy_result, 'term')[0]),            //可选册数
                'grade' => explode('_', ArrayHelper::getColumn($copy_result, 'grade')[0]),          //可选年级
                'tm_ver' => explode('_', ArrayHelper::getColumn($copy_result, 'tm_ver')[0]),        //可选版本
                'courses' => $course_result,        //课程结果
                'attrs' => $attr_result,            //可选属性
            ]
        ];
    }


    /**
     * 课程附加属性搜索
     * @return Query
     */
    public function CourseAttrSearch()
    {
        //复制对象，为查询对应附加属性
        $query = $this->search()->addSelect(['Course.subject_id']); 
        //查询附加属性
        $attr_query = (new Query())->select([
            'Attribute.name','GROUP_CONCAT(DISTINCT CourseAttr.attr_id SEPARATOR \'_\') as attr_id',
            'GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \',\') as value'])->from(['AttrCopy' => $query]);
        //关联查询课程属性
        $attr_query->leftJoin(['CourseAttr' => CourseAttr::tableName()],'CourseAttr.course_id = AttrCopy.id');
        //关联查询属性
        $attr_query->leftJoin(['Attribute' => CourseAttribute::tableName()],'Attribute.id = CourseAttr.attr_id');
        //只查询添加筛选的属性
        $attr_query->where(['Attribute.index_type' => 1]);               
        $attr_query->groupBy('Attribute.name')->orderBy('CourseAttr.sort_order');
        //添加已经过滤的属性条件
        foreach($this->attrs as $attr){
            $attr_query->andFilterWhere(['NOT IN','CourseAttr.attr_id',explode('_', $attr['attr_id'])]);
            $attr_query->andFilterWhere(['NOT IN','CourseAttr.value',explode('_', $attr['attr_value'])]);
        }
        
        return $attr_query;
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
        //过滤已选择学科条件
        $sub_query->filterWhere(['NOT IN', 'Subject.id', $this->sub_id]);
        //学科分组排序
        $sub_query->groupBy('Subject.id')->orderBy('sort_order');
        
        return $sub_query;
    }


    /**
     * 分类搜索
     * @return Query
     */
    public function categorySearch()
    {
        //复制对象，为查询对应分类
        $query = $this->search()->addSelect(['Course.cat_id']);  
        //查询分类
        $cat_query = (new Query())->select(['Category.id', 'Category.name'])
            ->from(['CategoryCopy' => $query]);
        //关联课程分类
        $cat_query->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = CategoryCopy.cat_id');
        //过滤已选择分类条件
        $cat_query->andFilterWhere(['NOT IN', 'Category.id', $this->cat_id]);
        //分类分组排序
        $cat_query->groupBy('Category.id')->orderBy('sort_order');  
        
        return $cat_query;
    }
    
    /**
     * 课程条件搜索
     * @return Query
     */
    public function search() 
    {
        $this->cat_id = ArrayHelper::getValue($this->params, 'cat_id', null);                   //分类
        $this->sub_id = ArrayHelper::getValue($this->params, 'sub_id', null);                   //学科
        $this->term = ArrayHelper::getValue($this->params, 'term', null);                       //学期
        $this->grade = ArrayHelper::getValue($this->params, 'grade', null);                     //年级
        $this->tm_ver = ArrayHelper::getValue($this->params, 'tm_ver', null);                   //版本
        $this->attrs = ArrayHelper::getValue($this->params, 'attrs', []);                       //附加属性
        $this->is_study = ArrayHelper::getValue($this->params, 'is_study', 1);                  //附加属性
        $this->keywords = ArrayHelper::getValue($this->params, 'keyword');                      //搜索的关键字
        $this->sort_order = ArrayHelper::getValue($this->params, 'sort_order', 'sort_order');   //排序
        $this->page = ArrayHelper::getValue($this->params, 'page', 1);                          //分页
        $this->limit = ArrayHelper::getValue($this->params, 'limit', 20);                       //限制显示数量        
        //查找所有课程分类id
        $catids = CourseCategory::getCatChildrenIds($this->par_id);
        //查寻过滤后的课程
        //[[id,cat_id,img,play_count],...]
        $query = (new Query())->select(['Course.id'])->from(['Course' => Course::tableName()]);
        //查询的必要条件
        $query->where(['is_publish' => 1]);
        //判断所需课程分类条件
        if($catids == null && $this->cat_id == null)
            $query->andFilterWhere(['Course.cat_id' => $this->par_id]);
        else if($this->cat_id == null)
            $query->andFilterWhere(['Course.cat_id' => $catids]);
        else 
            $query->andFilterWhere(['Course.cat_id' => $this->cat_id]);
        //需求条件查询
        $query->andFilterWhere(['Course.subject_id' => $this->sub_id]);
        $query->andFilterWhere(['Course.term' => $this->term]);
        $query->andFilterWhere(['Course.grade' => $this->grade]);
        $query->andFilterWhere(['Course.tm_ver' => $this->tm_ver]);
        //$query->andFilterWhere(['Course.is_study' => $is_study]);
        $query->andFilterWhere(['or',['like', 'Course.name', $this->keywords],
            ['like', 'Course.courseware_name', $this->keywords],
            ['like', 'Course.keywords', $this->keywords]
        ]);
        //添加属性过滤条件
        if(count($this->attrs)>0){
            foreach ($this->attrs as $key => $attr_arrs){
                //合并所有已经选择的属性id
                $query->leftJoin(['CourseAtt_'.$key => CourseAttr::tableName()], "CourseAtt_{$key}.course_id = Course.id");
                $query->andFilterWhere([
                    "CourseAtt_{$key}.attr_id" => explode('_', $attr_arrs['attr_id']), 
                    "CourseAtt_{$key}.value" => $attr_arrs['attr_value']
                ]);
            }
        }
        
        return $query;
    }
    
    /**
     * 复制对象
     * @return Query
     */
    public function copySearch()
    {
        //复制对象，为查询对应学期、年级、版本
        $queryCopy = $this->search()->addSelect([
            'GROUP_CONCAT(DISTINCT Course.term SEPARATOR \'_\') AS term',
            'GROUP_CONCAT(DISTINCT Course.grade SEPARATOR \'_\') AS grade',
            'GROUP_CONCAT(DISTINCT Course.tm_ver SEPARATOR \'_\') AS tm_ver'
        ]);
        
        return $queryCopy;
    }
    
    /**
     * 补充搜索查询
     * @return array
     */
    public  function addSearch()
    {
        //复制对象，为对应属性查询条件
        $query = $this->search();
        //按课程id分组
        $query->groupBy("Course.id");                   
        //查总数量
        $totalCount = count($query->all());            
        //额外字段属性
        $query->addSelect(['Course.courseware_name AS cour_name','Course.term','Course.unit','Course.grade','Course.tm_ver',
            'Course.play_count','Subject.img AS sub_img','Teacher.img AS tea_img',
            'IF(Attribute.index_type=1,GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \'|\'),\'\') as attr_values']);
        //关联课程学科
        $query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id');  
        //关联课程老师
        $query->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id');
        //关联查询课程属性
        $query->leftJoin(['CourseAttr' => CourseAttr::tableName()],'CourseAttr.course_id = Course.id');
        //关联查询属性
        $query->leftJoin(['Attribute' => CourseAttribute::tableName()],'Attribute.id = CourseAttr.attr_id');
        //课程排序，条件判断
        if($this->sort_order == 'sort_order')
            $query->orderBy(['Course.courseware_sn' => SORT_ASC, "Course.$this->sort_order" => SORT_ASC]);               
        else
            $query->orderBy(["Course.$this->sort_order" => SORT_DESC]);
        //显示数量 
        $query->offset(($this->page-1)*$this->limit)->limit($this->limit); 
        //课程分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $this->limit]);
        
        return [
            'query' => $query,
            'total' => $totalCount,
            'pages' => $pages,
        ];
    }
}
