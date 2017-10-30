<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\SearchLog;
use common\models\TeacherCourse;
use frontend\modules\study\searchs\CourseListSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `study` module
 */
class ChoiceController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'search'],
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
     * Renders  the index view for the module
     * @return string
     */
    public function actionIndex() {
        $search = new CourseListSearch();
        $results = $search->choiceSearch();
        $filterItem = $search->filterSearch();
        $parModel = CourseCategory::findOne($results['filter']['par_id']);
        
        return $this->render('index', array_merge($results, 
            array_merge(array_merge(['parModel' => $parModel], ['filterItem' => $filterItem]), 
            ['tm_logo' => Course::$tm_logo])));
    }

    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionView() {
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Renders Search the index view for the module
     * @return string
     */
    public function actionSearch() {

        $results = $this->saveSearchLog(Yii::$app->request->queryParams);

        return $this->redirect(array_merge(['index'], array_merge($results['filter'], ['#' => 'scroll'])));
    }

    /**
     * 保存选课的课程
     * @return boolean
     */
    public function actionSave() {
        Yii::$app->getResponse()->format = 'json';
        $course = ArrayHelper::getValue(Yii::$app->request->post(), 'course_id');
        $courseIds = explode('&',str_replace('course_id=','',$course));
        $values = [];
        foreach ($courseIds as $id){
            $values[] = [
                'course_id' => $id,
                'user_id' => Yii::$app->user->id,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        /** 添加$values数组到表里 */
        $num = Yii::$app->db->createCommand()->batchInsert(TeacherCourse::tableName(),[
            'course_id', 'user_id', 'created_at',  'updated_at'
        ], $values)->execute();
        if($num > 0) {
            return [
                'code' => '200',
                'data' => '',
                'message' => '',
            ];
        }else {
            return [
                'code' => '400',
                'data' => '',
                'message' => '',
            ];
        }
    }

    /**
     * Finds the WorksystemTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * 保存搜索日志数据
     * @param array $params
     */
    public function saveSearchLog($params) {
        $par_id = ArrayHelper::getValue($params, 'par_id');             //二级分类
        $keywords = ArrayHelper::getValue($params, 'keyword');          //关键字
        //搜索记录数组
        $searchLogs = [
            'keyword' => $keywords,
            'created_at' => time(),
            'updated_at' => time()
        ];
        /** 添加$searchLogs数组到表里 */
        if ($searchLogs != null)
            Yii::$app->db->createCommand()->insert(SearchLog::tableName(), $searchLogs)->execute();
       
        //把原来参数也传到view，可以生成已经过滤的条件
        return ['filter' => $params];
    }
}
