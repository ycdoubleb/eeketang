<?php

namespace frontend\modules\user\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\Favorites;
use frontend\modules\user\searchs\UserCourseSearch;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    public $layout = 'main';
    
    /**
     * 同步课堂
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['sync', 'cat_id' => 1]);
    }
    
    /**
     * 同步课堂
     * Renders the index view for the module
     * @return string
     */
    public function actionSync()
    {
        $params = Yii::$app->request->queryParams;
        $search = new UserCourseSearch();
        $results = $search->syncSearch($params);
        
        if(Yii::$app->request->isAjax){
            Yii::$app->getResponse()->format = 'json';
            return [
                'tot' => $results['totalCount'],
                'cou' => $results['result']['courses'],
                'stu' => $results['result']['study'],
            ];
        }else{        
            return $this->render('sync', [
                'filter' => $results['filter'],
                //'pages' => $results['pages'],
                'category' => $this->getCourseCategory($params),
                'subject' => $results['result']['subject'],
            ]);
        }
    }
    
    /**
     * 学科培优
     * Renders the index view for the module
     * @return string
     */
    public function actionSubject()
    {
        $search = new UserCourseSearch();
        $results = $search->collegeSearch(Yii::$app->request->queryParams);
        
        return $this->render('college', $results);
    }
    
    /**
     * 素质提升
     * Renders the index view for the module
     * @return string
     */
    public function actionDiathesis()
    {
        $search = new UserCourseSearch();
        $results = $search->collegeSearch(Yii::$app->request->queryParams);
        
        return $this->render('college', $results);
    }
    
    /**
     * 学习轨迹
     * Renders the index view for the module
     * @return string
     */
    public function actionStudy()
    {
        $search = new UserCourseSearch();
        $results = $search->studySearch();
        
        return $this->render('study', $results);
    }
    
    /**
     * 我的收藏
     * Renders the index view for the module
     * @return string
     */
    public function actionFavorites()
    {
        $search = new UserCourseSearch();
        $results = $search->favoritesSearch();
        
        return $this->render('favorites', $results);
    }
    
    /**
     * 删除我的收藏
     * Renders the index view for the module
     * @return string
     */
    public function actionDelete($id = null)
    {
        if($id !== null)
            Favorites::findOne ($id)->delete ();
        else
            Favorites::deleteAll(['user_id' => Yii::$app->user->id]);
    }

    /**
     * 获取所有课程的分类的二级分类
     * @param array $params                      
     * @return array
     */
    public function getCourseCategory($params)
    {
        //顶级分类
        $cat_id = ArrayHelper::getValue($params, 'cat_id');
        $categorys = CourseCategory::getCatChildren($cat_id, false, false, false, false);
        $catIds = ArrayHelper::getColumn($categorys, 'id');
        //查询分类下的所有课程总数
        $query = (new Query())->select(['CourseCategory.parent_id', 'COUNT(Course.id) AS total'])
                ->from(['Course' => Course::tableName()]);
        $query->leftJoin(['CourseCategory' => CourseCategory::tableName()], 'CourseCategory.id = Course.cat_id');
        $query->where(['CourseCategory.parent_id' => $catIds]);
        $query->groupBy('CourseCategory.parent_id');
        $category_total = ArrayHelper::map($query->all(), 'parent_id', 'total');
        $category_result = [];
        foreach($categorys as $cate){
            $cate['total'] = isset($category_total[$cate['id']]) ? $category_total[$cate['id']] : '';
            $category_result[] = $cate;
        }
       
        return  $category_result;
    }
}
