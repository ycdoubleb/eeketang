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
        $attrs = ArrayHelper::getValue($params, 'attrs', []);                       //附加属性
        $is_study = ArrayHelper::getValue($params, 'is_study', 1);                  //附加属性
        $keywords = ArrayHelper::getValue($params, 'keyword');                      //搜索的关键字
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
        //判断所需课程分类条件
        if($catids == null && $cat_id == null)
            $query->andFilterWhere(['Course.cat_id' => $par_id]);
        else if($cat_id == null)
            $query->andFilterWhere(['Course.cat_id' => $catids]);
        else 
            $query->andFilterWhere(['Course.cat_id' => $cat_id]);
        //需求条件查询
        $query->andFilterWhere(['Course.subject_id' => $sub_id]);
        $query->andFilterWhere(['Course.term' => $term]);
        $query->andFilterWhere(['Course.grade' => $grade]);
        $query->andFilterWhere(['Course.tm_ver' => $tm_ver]);
        //$query->andFilterWhere(['Course.is_study' => $is_study]);
        $query->andFilterWhere(['or',['like', 'Course.name', $keywords],
            ['like', 'Course.courseware_name', $keywords],
            ['like', 'Course.keywords', $keywords]
        ]);
        //添加属性过滤条件
        if(count($attrs)>0){
            foreach ($attrs as $key => $attr_arrs){
                //合并所有已经选择的属性id
                $query->leftJoin(['CourseAtt_'.$key => CourseAttr::tableName()], "CourseAtt_{$key}.course_id = Course.id");
                $query->andFilterWhere([
                    "CourseAtt_{$key}.attr_id" => explode('_', $attr_arrs['attr_id']), 
                    "CourseAtt_{$key}.value" => $attr_arrs['attr_value']
                ]);
            }
        }
        //复制对象，为查询对应分类
        $categoryCopy = clone $query;          
        //复制对象，为查询对应学科
        $subjectCopy = clone $query; 
        //复制对象，为查询对应附加属性
        $attrCopy = clone $query; 
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
        $query->addSelect(['Course.courseware_name AS cour_name','Course.term','Course.unit','Course.grade','Course.tm_ver',
            'Course.play_count','Subject.img AS sub_img','Teacher.img AS tea_img','IF(StudyLog.course_id IS NUll,0,1) AS is_study']);
        //关联课程学科
        $query->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id');  
        //关联课程老师
        $query->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id');
        //关联课程学习记录
        $query->leftJoin(['StudyLog' => StudyLog::tableName()], 'StudyLog.course_id = Course.id');     
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
        //查询附加属性
        $attr_query = (new Query())->select([
            'Attribute.name','GROUP_CONCAT(DISTINCT CourseAttr.attr_id SEPARATOR \'_\') as attr_id',
            'GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \',\') as value'])->from(['AttrCopy' => $attrCopy]);
        //关联查询课程属性
        $attr_query->leftJoin(['CourseAttr' => CourseAttr::tableName()],'CourseAttr.course_id = AttrCopy.id');
        //关联查询属性
        $attr_query->leftJoin(['Attribute' => CourseAttribute::tableName()],'CourseAttr.attr_id = Attribute.id');
        //只查询添加筛选的属性
        $attr_query->where(['Attribute.index_type' => 1]);               
        $attr_query->groupBy('Attribute.name')->orderBy('CourseAttr.sort_order');
        //添加已经过滤的属性条件
        foreach($attrs as $attr){
            $attr_query->andFilterWhere(['NOT IN','CourseAttr.attr_id',explode('_', $attr['attr_id'])]);
            $attr_query->andFilterWhere(['NOT IN','CourseAttr.value',explode('_', $attr['attr_value'])]);
        }
        //查询后的结果
        $category_result = $cat_query->all();
        $subject_result = $sub_query->all();
        $copy_result = $queryCopy->all();
        $course_result = $query->all();
        
        //用属性的 name 作键分组
        $attr_result = [];
        foreach($attr_query->all() as $attr_arr){
            $attr_result[$attr_arr['name']] = [
                'attr_id' => $attr_arr['attr_id'],                       //把相同属性名的属性id组合以'_'字符连接
                'value' => explode(',', $attr_arr['value']),             //合并相同属性值
            ];
        }
        
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
                'attrs' => $attr_result,            //可选属性
            ]
        ];
    }
}
