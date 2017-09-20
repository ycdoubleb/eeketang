<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\course\searchs;

use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use Yii;
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
        //参数格式
        // [
        //      'parent_cat_id' => 1 ,                                              //{int} 分类id
        //      'cat_id' => 1,                                                      //{int|string} 学科id或者学科组合以'_'连接
        //      'sort_order' => 'order',                                            //{string} 排序方式，默认为'order'课程的排序，还可以是'play_count'按播放量
        //      'attrs' => [
        //          [
        //              'attr_id' => 1,                                             //{int} 属性id
        //              'attr_value' => '初级',                                          //{string} 属性的值
        //          ],
        //          [
        //              'attr_id' => 2_3_15,                                        //{string} 属性id组合以'_'连接
        //              'attr_value' => '一年级_二年级',                                  //{array} 属性值组合以'_'连接
        //          ],
        //      ]
        // ]
        //
        $parent_cat_id = ArrayHelper::getValue($params, 'parent_cat_id', null);     //分类
        $cat_id = ArrayHelper::getValue($params, 'cat_id', null);                   //学科
        $sort_order = ArrayHelper::getValue($params, 'sort_order', 'order');        //排序
        $attrs = ArrayHelper::getValue($params, 'attrs', []);                       //过滤的属性
        $page = ArrayHelper::getValue($params, 'page', 1);                          //分页
        $limit = ArrayHelper::getValue($params, 'limit', 20);                       //限制显示数量
        
        //查寻过滤后的课程
        //[[id,cat_id,img,play_count],...]
        //
        $query = (new Query())
                ->select(['Course.id','Course.cat_id',] )
                ->from(['Course' => Course::tableName()]);

        // grid filtering conditions
        $query->andFilterWhere([
            'Course.parent_cat_id' => $parent_cat_id,
            //如果cat_id是'_'组合即为id组合
            'Course.cat_id' => $cat_id == null ? null : (strpos($cat_id, '_') ? explode('_', $cat_id) : $cat_id),
            'Course.is_publish' => 1,
        ]);
        
        
        //添加属性过滤条件
        if(count($attrs)>0){
            foreach ($attrs as $key => $attr_arrs){
                //合并所有已经选择的属性id
                //$attr_has_filter_ids = array_merge($attr_has_filter_ids,explode('_', $attr_arrs['attr_id']));
                $query->leftJoin(['CourseAtt_'.$key => CourseAttr::tableName()], "CourseAtt_{$key}.course_id = Course.id");
                $query->andFilterWhere([
                    "CourseAtt_{$key}.attr_id" => explode('_', $attr_arrs['attr_id']), 
                    "CourseAtt_{$key}.value" => $attr_arrs['attr_value']
                ]);
            }
        }
        
        $query->groupBy("Course.id");
        $queryCopy = clone $query;
        $totalCount = $query->all();      //查总数量
        
        $query->addSelect([ 'Course.parent_cat_id','Course.img', 'Course.unit', 'Course.courseware_name as name', 'Course.play_count']);
        $query->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = Course.cat_id');
        if($sort_order == 'order')
            $query->orderBy(['Category.sort_order' => SORT_ASC, 'Course.courseware_sn' => SORT_ASC, "Course.$sort_order" => SORT_ASC]);               
        else
            $query->orderBy(["Course.$sort_order" => SORT_DESC]);
        $query->offset(($page-1)*$limit)->limit($limit);
        
        $pages = new Pagination(['totalCount' => count($totalCount), 'defaultPageSize' => $limit]);
        $course_result = $query->all();
        //查找过滤后的学科
        //[['id','name'],...]
        //
        //拿到过滤后的学科id
        $cat_ids = array_unique(ArrayHelper::getColumn($totalCount, 'cat_id'));  
        $cats = (new Query())
                ->select(['id','name'])
                ->from(CourseCategory::tableName())
                ->where(['id' => $cat_ids, 'is_show' => 1])
                ->orderBy('sort_order')
                ->all();
        
        //查找过滤后的课程属性
        //返回格式：[
        //  '级别' => [
        //      'attr_id' => '1_25_1',
        //      'attr_value' => ['初级','中级',...]
        //   ],
        //   '单元' => ...   
        //   
        //查询结果：[[attr_id => 5_1, name => 册数  ,value => 上学期,下学期],...]
        $query = (new Query())
                ->select([
                    'GROUP_CONCAT(DISTINCT CourseAttr.attr_id SEPARATOR \'_\') as attr_id',
                    'Attribute.name',
                    'GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \',\') as value'])
                ->from(['Course' => $queryCopy])
                ->leftJoin(['CourseAttr' => CourseAttr::tableName()],'CourseAttr.course_id = Course.id')
                ->leftJoin(['Attribute' => CourseAttribute::tableName()],'CourseAttr.attr_id = Attribute.id')
                ->andFilterWhere(['Attribute.index_type' => 1])                 //只查询添加筛选的属性
                ->groupBy('Attribute.name')
                ->orderBy('CourseAttr.sort_order');
        //添加已经过滤的属性条件
        foreach($attrs as $attr){
            $query->andFilterWhere(['not in','CourseAttr.attr_id',explode('_', $attr['attr_id'])]);
            $query->andFilterWhere(['not in','CourseAttr.value',explode('_', $attr['attr_value'])]);
        }
        $attr_result = $query->all();
        
        //用属性的 name 作键分组
        $attr_map = [];
        foreach($attr_result as $attr_arr){
            $attr_map[$attr_arr['name']] = [
                'attr_id' => $attr_arr['attr_id'],                                  //把相同属性名的属性id组合以'_'字符连接
                'value' => explode(',', $attr_arr['value']),                        //合并相同属性值
                ];
        }
        
        return [
            'filter' => $params,                //把原来参数也传到view，可以生成已经过滤的条件
            'pages' => $pages,                  //分页
            'result' => [
                'courses' => $course_result,    //课程结果
                'cats' => $cats,                //可选学科
                'attrs' => $attr_map,           //可选属性
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
