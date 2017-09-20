<?php

namespace backend\modules\course\controllers;

use backend\components\BaseController;
use common\models\course\CourseAttribute;
use common\models\course\CourseModel;
use common\models\course\searchs\CourseAttributeSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * AttributeController implements the CRUD actions for CourseAttribute model.
 */
class AttributeController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CourseAttribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourseAttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'course_models' => $this->getCouseModels(),
        ]);
    }

    /**
     * Displays a single CourseAttribute model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'course_models' => $this->getCouseModels(),
        ]);
    }

    /**
     * Creates a new CourseAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CourseAttribute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->loadDefaultValues();
            return $this->render('create', [
                'model' => $model,
                'course_models' => $this->getCouseModels(),
            ]);
        }
    }

    /**
     * Updates an existing CourseAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'course_models' => $this->getCouseModels(),
            ]);
        }
    }

    /**
     * Deletes an existing CourseAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    private function getCouseModels(){
        return ArrayHelper::map(CourseModel::find()->orderBy('sort_order')->all(), 'id', 'name');
    }

    /**
     * Finds the CourseAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CourseAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
