<?php

namespace frontend\modules\user\controllers;

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
        return $this->redirect(['sync', 'id' => 1]);
    }
    
    /**
     * 同步课堂
     * Renders the index view for the module
     * @return string
     */
    public function actionSync()
    {
        return $this->render('sync');
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
