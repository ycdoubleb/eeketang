<?php

namespace backend\modules\course\controllers;

use backend\components\BaseController;
use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\CourseModel;
use common\models\course\CourseTemplate;
use common\models\course\searchs\CourseSearch;
use common\models\Teacher;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Course model.
 */
class DefaultController extends BaseController
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
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Course model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $attrs = (new Query())
                ->select(['Attribute.name','CourseAtt.value','CourseAtt.sort_order'])
                ->from(['CourseAtt' => CourseAttr::tableName()])
                ->leftJoin(['Attribute' => CourseAttribute::tableName()], 'CourseAtt.attr_id = Attribute.id')
                ->where(['CourseAtt.course_id' => $id])
                ->orderBy('CourseAtt.sort_order')
                ->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'attrs' => $attrs,
        ]);
    }

    /**
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Course();
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            //插入属性
            CourseAttr::insterAttr($model->id, $post['CourseAtts'], true);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->loadDefaultValues();
            $model->create_by = Yii::$app->user->id;
            return $this->render('create', [
                'model' => $model,
                'templates' =>  CourseTemplate::getTemplate(),                 //模板
                'course_models' => ArrayHelper::map(CourseModel::find()->all(), 'id', 'name'),
                'teachers' => ArrayHelper::map(Teacher::find()->all(), 'id', 'name'),
            ]);
        }
    }
    
    /**
     * Updates an existing Course model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            if(isset($post['CourseAtts'])){
                //更新属性
                CourseAttr::insterAttr($model->id, $post['CourseAtts']);
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'templates' => CourseTemplate::getTemplate(),                 //模板
                'course_models' => ArrayHelper::map(CourseModel::find()->all(), 'id', 'name'),
                'teachers' => ArrayHelper::map(Teacher::find()->all(), 'id', 'name'),
            ]);
        }
    }

    /**
     * Deletes an existing Course model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * 预览
     * @param int $id  课程id
     */
    public function actionPreview($id){
        $model = $this->findModel($id);
        
        return $this->render('preview', [
            'model' => $model,
        ]);    
    }
    
    /**
     * 获取模型属性
     * @param type $model_id        课程模型id  
     * @param type $course_id       
     */
    public function actionSearchAttr($model_id,$course_id = null){
        //获取模型相关属性
        $attributes = CourseAttribute::find()->where(['course_model_id' => $model_id])->orderBy('sort_order')->all();
        //获取课程相关属性值
        $course_attrs = $course_id == null ? [] : ArrayHelper::map(CourseAttr::find()->where(['course_id' => $course_id])->all(), 'attr_id', 'value');
        
        return $this->renderAjax('_form_attribute', [
            'attributes' => $attributes,
            'course_attrs' => $course_attrs,
        ]);
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
