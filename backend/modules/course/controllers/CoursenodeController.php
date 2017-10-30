<?php

namespace backend\modules\course\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\CoursewaveNode;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Course model.
 */
class CoursenodeController extends Controller
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
                    'save-coursenode' => ['POST'],
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
        $cat_ids = array_merge(CourseCategory::getCatChildrenIds(4, true),CourseCategory::getCatChildrenIds(2, true),CourseCategory::getCatChildrenIds(3, true));
        $hasDoneCourseIds = (new Query())
                ->from(CoursewaveNode::tableName())
                ->select(['id'=>'course_id'])
                ->groupBy('course_id')
                ->all();
        $hasDoneCourseIds = \yii\helpers\ArrayHelper::getColumn($hasDoneCourseIds, 'id');
        $courses = (new Query())
                ->select(['Course.id','Course.courseware_name as title','Course.path'])
                ->from(['Course' => Course::tableName()])
                ->where(['Course.cat_id' => $cat_ids])
                ->andWhere(['not in','id',$hasDoneCourseIds])
                ->all();
        return $this->render('index',['courses' => $courses]);
    }
    
    public function actionPathCheck(){
        $cat_ids = array_merge(CourseCategory::getCatChildrenIds(4, true),CourseCategory::getCatChildrenIds(2, true),CourseCategory::getCatChildrenIds(3, true));
        $courses = (new Query())
                ->select(['Course.id','Course.courseware_name as title','Course.path'])
                ->from(['Course' => Course::tableName()])
                ->where(['Course.cat_id' => $cat_ids])
                ->all();
        return $this->render('check',['courses' => $courses]);
    }
    
    /**
     * 代理返回xml
     * @param type $url
     */
    public function actionProxyGetxml($url){
        header('Content-type:application/xml'); //这行代码不能少，但不知道我是不是都写对了
        $fp=file_get_contents($url);
        echo $fp;
    }
    
    /**
     * 添加课程
     */
    public function actionSaveCoursenode(){
        Yii::$app->response->format = 'json';
        $mes = '';
        $code = 200;
        
        try{
            $post = json_decode(Yii::$app->getRequest()->getRawBody(),true);
            if(count($post['nodes'])>0){
                $now = time();
                foreach($post['nodes'] as $node){
                    $node['created_at'] = $now;
                    $node['updated_at'] = $now;
                }
                Yii::$app->db->createCommand()->delete(CoursewaveNode::tableName(), 'course_id='.$post['nodes'][0]['course_id'])->execute();
                Yii::$app->db->createCommand()->batchInsert(CoursewaveNode::tableName(),  array_keys($post['nodes'][0]), $post['nodes'])->execute();
            }else{
                $code = 201;
                $mes = '没有数据需要保存！';
            }
            
        } catch (\Exception $ex) {
            $code = 201;
            $mes = $ex->getMessage();
        }
        
        return [
            'code' => $code,
            'message' => $mes
        ];
    }
}
