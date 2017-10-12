<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\CoursewaveNode;
use common\models\course\CoursewaveNodeResult;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `study` module
 */
class ApiController extends Controller {

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
    
    public function actionGetCourse(){
        Yii::$app->getResponse()->format = 'json';
        $cat_ids = array_merge(CourseCategory::getCatChildrenIds(4, true),CourseCategory::getCatChildrenIds(2, true),CourseCategory::getCatChildrenIds(3, true));
        $course = (new Query())
                ->select(['id','path'])
                ->from(Course::tableName())
                ->where(['cat_id' => $cat_ids])
                ->all();
        return [
            'code' => 200,
            'data' => $course,
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
        $user_id = Yii::$app->user->id;
        $course_id = ArrayHelper::getValue($post, 'course_id');
        $identifier = ArrayHelper::getValue($post, 'identifier');
        $sign = ArrayHelper::getValue($post, 'sign');
        $result = ArrayHelper::getValue($post, 'result');
        
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
