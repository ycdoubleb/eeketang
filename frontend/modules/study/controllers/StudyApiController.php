<?php

namespace frontend\modules\study\controllers;

use common\models\course\CoursewaveNode;
use common\models\course\CoursewaveNodeResult;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `study` module
 */
class StudyApiController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 更新环节状态
     * @return string
     */
    public function actionUpdateNote() {
        
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $post = Yii::$app->getRequest()->post();
        $user_id = \Yii::$app->user->id;
        $course_id = \yii\helpers\ArrayHelper::getValue($post, 'course_id');
        $identifier = \yii\helpers\ArrayHelper::getValue($post, 'identifier');
        $sign = \yii\helpers\ArrayHelper::getValue($post, 'sign');
        $result = \yii\helpers\ArrayHelper::getValue($post, 'result');
        
        $node = (new Query())
                ->select(['NodeResult' => CoursewaveNodeResult::tableName()])
                ->leftJoin(['Node' => CoursewaveNode::tableName()], 'NodeResult.node_id = Node.id')
                ->where([
                    'Node.course_id' => $course_id,
                    'Node.identifier' => $identifier,
                    'Node.sign' => $sign,
                    'NodeResult.user_id' => $user_id,])
                ->one();
    }
    
    public function actionSaveExamine(){
        
    }
}
