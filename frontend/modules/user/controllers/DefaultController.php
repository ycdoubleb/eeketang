<?php

namespace frontend\modules\user\controllers;

use yii\web\Controller;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if(\Yii::$app->user->identity->isRoleStudent())
            return $this->redirect(['student/sync', 'cat_id' => 1]);
        else
            return $this->redirect(['teacher/sync', 'cat_id' => 1]);
    }
}
