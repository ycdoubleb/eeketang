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
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of CourseListSearch
 *
 * @author Administrator
 */
class CourseListSearch {
    //put your code here
    public function search($params)
    {
        $par_id = ArrayHelper::getValue($params, 'par_id', null);                   //二级分类
        $cat_id = ArrayHelper::getValue($params, 'cat_id', null);                   //分类
        $sub_id = ArrayHelper::getValue($params, 'sub_id', null);                   //学科
        $term = ArrayHelper::getValue($params, 'term', null);                       //学期
        $grade = ArrayHelper::getValue($params, 'grade', null);                     //年级
        $tm_ver = ArrayHelper::getValue($params, 'tm_ver', null);                   //版本
        $keywords = ArrayHelper::getValue($params, 'keyword');                       //搜索的关键字
        $sort_order = ArrayHelper::getValue($params, 'sort_order', 'sort_order');   //排序
        $attrs = ArrayHelper::getValue($params, 'attrs', []);                       //过滤的属性
        $page = ArrayHelper::getValue($params, 'page', 1);                          //分页
        $limit = ArrayHelper::getValue($params, 'limit', 20);                       //限制显示数量
        //查找所有课程分类id
        $catids = CourseCategory::getCatChildrenIds($par_id);
        //查寻过滤后的课程
        //[[id,cat_id,img,play_count],...]
        $query = (new Query())->select(['Course.id', 'Course.cat_id', 'Course.subject_id'])
                ->from(['Course' => Course::tableName()]);
        //查询的必要条件
        $query->where(['is_publish' => 1]);
        //需求条件查询
        $query->andFilterWhere(['Course.cat_id' => $cat_id != null?$cat_id:$catids]);
        $query->andFilterWhere(['Course.subject_id' => $sub_id]);
        $query->andFilterWhere(['Course.term' => $term]);
        $query->andFilterWhere(['Course.grade' => $grade]);
        $query->andFilterWhere(['Course.tm_ver' => $tm_ver]);
        $query->andFilterWhere(['or',['like', 'Course.name', $keywords],
            ['like', 'Course.courseware_name', $keywords],
            ['like', 'Course.keywords', $keywords]
        ]);
        //复制对象，为查询对应分类
        $categoryCopy = clone $query;          
        //复制对象，为查询对应学科
        $subjectCopy = clone $query; 
        //复制对象，为查询对应学期、年级、版本
        $query->addSelect([
            'GROUP_CONCAT(DISTINCT Course.term SEPARATOR \'_\') AS term',
            'GROUP_CONCAT(DISTINCT Course.grade SEPARATOR \'_\') AS grade',
            'GROUP_CONCAT(DISTINCT Course.tm_ver SEPARATOR \'_\') AS tm_ver'
        ]);
        $queryCopy = clone $query;
        //按课程id分组
        $query->groupBy("Course.id");                   
        //查总数量
        $totalCount = count($query->all());            
        //额外字段属性
        $query->addSelect(['Course.img', 'Course.unit', 'Course.courseware_name AS cour_name', 'Course.play_count']);
        //课程排序，条件判断
        if($sort_order == 'sort_order')
            $query->orderBy(['Course.courseware_sn' => SORT_ASC, "Course.$sort_order" => SORT_ASC]);               
        else
            $query->orderBy(["Course.$sort_order" => SORT_DESC]);
        //显示数量 
        $query->offset(($page-1)*$limit)->limit($limit); 
        //课程分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $limit]);
        //查询分类
        $cat_query = (new Query())->select(['Category.id', 'Category.name'])
            ->from(['CategoryCopy' => $categoryCopy]);
        //关联课程分类
        $cat_query->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = CategoryCopy.cat_id');
        //过滤已选择分类条件
        $cat_query->andFilterWhere(['NOT IN', 'Category.id', $cat_id]);
        //分类分组排序
        $cat_query->groupBy('Category.id')->orderBy('sort_order');  
        //查询学科
        $sub_query = (new Query())->select(['Subject.id', 'Subject.name'])
            ->from(['SubjectCopy' => $subjectCopy]);
        //关联学科
        $sub_query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = SubjectCopy.subject_id');
        //过滤已选择学科条件
        $sub_query->filterWhere(['NOT IN', 'Subject.id', $sub_id]);
        //学科分组排序
        $sub_query->groupBy('Subject.id')->orderBy('sort_order');               
        
        //查询后的结果
        $category_result = $cat_query->all();
        $subject_result = $sub_query->all();
        $copy_result = $queryCopy->all();
        $course_result = $query->all();
        
        return [
            'filter' => $params,                    //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $pages,                      //分页
            'totalCount' => $totalCount,            //总数
            'results' => [
                'category' => $category_result,     //可选分类
                'subject' => $subject_result,       //可选学科
                'term' => explode('_', ArrayHelper::getColumn($copy_result, 'term')[0]),            //可选册数
                'grade' => explode('_', ArrayHelper::getColumn($copy_result, 'grade')[0]),          //可选年级
                'tm_ver' => explode('_', ArrayHelper::getColumn($copy_result, 'tm_ver')[0]),        //可选版本
                'courses' => $course_result,        //课程结果
                //'attrs' => $attr_map,               //可选属性
            ]
        ];
    }
    
    //put your code here
    public function searchKeywords($params)
    {
        //搜索所需参数
        $keyword = ArrayHelper::getValue($params, 'keyword');                       //搜索的关键字
        $sort_order = ArrayHelper::getValue($params, 'sort_order', 'order');        //排序
        $page = ArrayHelper::getValue($params, 'page', 1);                          //分页
        $limit = ArrayHelper::getValue($params, 'limit', 20);                       //限制显示数量
        $page = ArrayHelper::getValue($params, 'page', 1);                          //当前页
        
        //关联表查询
        $query = (new Query())
                ->select(['Course.id'])
                ->from(['Course' => Course::tableName()])
                ->leftJoin(['CourseAtt' => CourseAttr::tableName()], 'CourseAtt.course_id = id')
                ->leftJoin(['CourseAttribute' => CourseAttribute::tableName()], 'CourseAtt.attr_id = CourseAttribute.id')
                ->leftJoin(['CourseCat' => CourseCategory::tableName()], 'CourseCat.id = cat_id')
                ->leftJoin(['CourseParentCat' => CourseCategory::tableName()], 'CourseParentCat.id = parent_cat_id');
        
        //查询条件
        $query->orFilterWhere(['like', 'CourseParentCat.name', $keyword]);
        $query->orFilterWhere(['like', 'CourseCat.name', $keyword]);
        $query->orFilterWhere(['like', 'Course.name', $keyword]);
        $query->orFilterWhere(['like', 'Course.courseware_name', $keyword]);
        $query->orFilterWhere(['like', 'Course.keywords', $keyword]);
        $query->orFilterWhere(['like', 'CourseAtt.value', $keyword]);
        $query->andWhere(['Course.is_publish' => 1]);
        $query->andWhere(['CourseAttribute.index_type' => 1]);
        
        $query->groupBy("Course.id");                                   //分组
        $totalCount = $query->count();
        $query->addSelect(['Course.parent_cat_id', 'Course.cat_id','Course.img', 'Course.unit', 'Course.play_count', 'Course.courseware_name']);
        $query->orderBy("Course.$sort_order DESC");                     //排序
        $query->offset(($page-1)*$limit)->limit($limit);                //每页显示的数量
        $course_result = $query->all();                                 //课程查询结果
        //$queryPage = clone $query;
        
        //分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $limit]);
        //$course_result = $queryPage->all();
        
        return [
            'filter' => $params,                //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $pages,                  //分页
            'result' => [
                'courses' => $course_result,    //课程结果
            ]
        ];
    }
}
