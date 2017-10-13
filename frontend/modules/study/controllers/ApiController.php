<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\CoursewaveNodeResult;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update-node' => ['post'],
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
    public function actionUpdateNode() {
        
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $code = 200;
        $mes = '';
        
        $post = Yii::$app->getRequest()->post();
        $user_id = Yii::$app->user->id;
        $identifier = ArrayHelper::getValue($post, 'id');
        $result = ArrayHelper::getValue($post, 'result');
        
        $node_result = CoursewaveNodeResult::find()
                ->where(['user_id' => $user_id,'node_id'=>$identifier])
                ->one();
        if($node_result == null){
            $node_result = new CoursewaveNodeResult();
            $node_result->node_id = $identifier;
            $node_result->user_id = $user_id;
        }
        $node_result->result = $result;
        if(!$node_result->save()){
            $code = 500;
            foreach($node_result->errors as $error){
                foreach($error as $name=>$mes){
                    $mes .= "$name:$mes";
                }
            }
        }
        return [
            'code' => $code,
            'message' => $mes,
        ];
    }
    /**
     * 保存考核成绩
     */
    public function actionSaveExamine(){
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $code = 200;
        $mes = '';
        
        $post = Yii::$app->getRequest()->post();
        $user_id = Yii::$app->user->id;
        $identifier = ArrayHelper::getValue($post, 'id');
        $result = ArrayHelper::getValue($post, 'result');
        
    }
}
