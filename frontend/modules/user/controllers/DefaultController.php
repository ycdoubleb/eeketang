<?php

namespace frontend\modules\user\controllers;

use common\models\course\Course;
use common\models\course\Subject;
use frontend\modules\user\searchs\UserCourseSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        //$search = new UserCourseSearch();
        //$results = $search->syncSearch(Yii::$app->request->queryParams);
        
        return $this->render('sync', [
            //'filter' => $results['filter'],
            //'pages' => $results['pages'],
            //'results' => $results['result'],
        ]);
    }
    
    /**
     * 学科培优
     * Renders the index view for the module
     * @return string
     */
    public function actionSubject()
    {
        return $this->render('subject');
    }
    
    /**
     * 素质提升
     * Renders the index view for the module
     * @return string
     */
    public function actionDiathesis()
    {
        return $this->render('diathesis');
    }
    
    /**
     * 学习轨迹
     * Renders the index view for the module
     * @return string
     */
    public function actionStudy()
    {
        return $this->render('study');
    }
    
    /**
     * 学习轨迹
     * Renders the index view for the module
     * @return string
     */
    public function actionCollection()
    {
        return $this->render('collection');
    }
}
