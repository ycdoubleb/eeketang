<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\user\searchs;

use common\models\CategoryJoin;
use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\Subject;
use common\models\Favorites;
use common\models\StudyLog;
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
    /**
     * 同步课堂
     * @param array $params
     * @return array
     */
    public function syncSearch($params)
    {
        //put your code here
        $psub_id = ArrayHelper::getValue($params, 'psub_id');
        $psubIds = explode('_', $psub_id);
        $par_id = isset($psubIds[0]) ? $psubIds[0] : null;                      //二级分类
        $sub_id = isset($psubIds[1]) ? $psubIds[1] : null;                      //学科
        $grade_keys = Yii::$app->user->identity->profile->getGrade(false);      //年级
        $page = ArrayHelper::getValue($params, 'page', 1);                      //分页
        $limit = ArrayHelper::getValue($params, 'limit', 12);                   //限制显示数量
        //查找所有课程分类id
        $catids = CourseCategory::getCatChildrenIds($par_id);
        //查找课程        
        $query = (new Query())->select(['Course.id', 'Course.subject_id'])
            ->from(['Course' => Course::tableName()]);
        //查询的必要条件
        $query->where(['is_publish' => 1, 'is_recommend' => 1]);    
        $query->andWhere(['Course.grade' => $grade_keys]);
        //复制对象，为查询对应学科
        $subjectCopy = clone $query;          
        //需求条件查询
        $query->andFilterWhere(['Course.cat_id' => $catids]);
        $query->andFilterWhere(['Course.subject_id' => $sub_id]);
        //查询上、下、全一册条件判断
        if(date('n', time()) <= 2 || date('n', time()) >= 9)
            $query->andFilterWhere(['Course.term' => 1]);
        else if(date('n', time()) >= 3 && date('n', time()) <= 8)
            $query->andFilterWhere(['Course.term' => 2]);
        else
            $query->andFilterWhere(['Course.term' => 3]);
        //关联课程分类
        $query->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = Course.cat_id');   
        //关联课程学习记录
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], 'StudyLog.course_id = Course.id');      
        //按课程id分组
        $query->groupBy(['Course.id']);    
        //课程排序
        $query->orderBy(['Course.courseware_sn' => SORT_ASC, "Course.sort_order" => SORT_ASC]);     
        //查课程总数
        $totalCount = count($query->all());     
        //课程分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $limit]);        
        //额外字段属性
        $query->addSelect(['StudyLog.course_id', 'Course.courseware_name AS cou_name', 'Course.img','Course.play_count',
            'IF(StudyLog.course_id IS NUll,0,1) AS study']);
        //复制对象，为查询对应学习记录
        $studyCopy = clone $query;     
        //显示数量 
        //$query->offset(($page-1)*$limit)->limit($limit);        
        //查询学科
        $sub_query = (new Query())->select(['Subject.id', 'Subject.name'])
            ->from(['SubjectCopy' => $subjectCopy]);
        //关联学科
        $sub_query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = SubjectCopy.subject_id');  
        //学科分组排序
        $sub_query->groupBy('Subject.id')->orderBy('sort_order');          
        //查询学习记录
        $stu_query = (new Query())->select(['COUNT(StudyCopy.course_id) AS num'])
            ->from(['StudyCopy' => $studyCopy]);
        //查询后的结果
        $subject_result= $sub_query->all();
        $course_result = $query->all();
        $study_result = $stu_query->all();
        
        return [
            'filter' => $params,                    //把原来参数也传到view，可以生成已经过滤的条件
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
        $query = (new Query())
            ->select([
                'CategoryJoin.id', 'CategoryJoin.category_id AS cate_id',
                'GROUP_CONCAT(DISTINCT CategoryJoin.user_id SEPARATOR \',\') as users',
                'Category.name', 'Category.image', 'COUNT(CategoryJoin.id) AS totalCount'
            ])->from(['Category' => CourseCategory::tableName()]);
        
        $query->leftJoin(['CategoryJoin' => CategoryJoin::tableName()], 'CategoryJoin.category_id = Category.id');
        $query->where(['Category.id' => $catIds]);
        $query->groupBy(['Category.id']);
        $query->orderBy(['Category.sort_order' => SORT_ASC]);
        //查询结果
        $join_results = $query->all();
        //计算加入学院的人数和判断该用户是否加入学院
        foreach($join_results as $item){
            $count += $item['totalCount'];
            $is_join[$item['cate_id']] = in_array(Yii::$app->user->id, explode(',', $item['users']));
        }
        //var_dump($join_results);exit;
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
        //查询收藏
        $qurey = (new Query())
            ->select(['StudyLog.id'])->from(['StudyLog' => StudyLog::tableName()]);
        //查询条件
        $qurey->filterWhere(['StudyLog.user_id' => Yii::$app->user->id]);
        $qureyCopy = clone $qurey;
        //关联查询
        $qurey->addSelect(['Course.courseware_name AS cou_name', 'Course.img','StudyLog.created_at AS date']);
        $qurey->leftJoin(['Course' => Course::tableName()], 'Course.id = StudyLog.course_id');
        $qurey->orderBy(['StudyLog.created_at' => SORT_DESC]);      //排序
        $totleCount = $qureyCopy->all();    //计算总数
        //组装学习日志
        $study_results = [];
        foreach ($qurey->all() as $item){
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
        $qurey = (new Query())
            ->select([
                'Favorites.id', 'Course.courseware_name AS cou_name', 'Course.img', 
                'IF(StudyLog.id IS NULL,0,1) AS is_study'
            ])->from(['Favorites' => Favorites::tableName()]);
        //关联查询
        $qurey->leftJoin(['StudyLog' => StudyLog::tableName()], 'StudyLog.course_id = Favorites.course_id');
        $qurey->leftJoin(['Course' => Course::tableName()], 'Course.id = Favorites.course_id');
        //查询条件
        $qurey->filterWhere(['Favorites.user_id' => Yii::$app->user->id]);
        
        return [
            'favorites' => $qurey->all(),
        ];
    }
}
