<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseAppraise;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use frontend\modules\study\searchs\CourseListSearch;
use common\models\Favorites;
use common\models\SearchLog;
use common\models\StudyLog;
use common\models\WebUser;
use common\widgets\players\CourseData;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `study` module
 */
class DefaultController extends Controller {

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
     * Renders  the index view for the module
     * @return string
     */
    public function actionIndex() {
        
        $search = new CourseListSearch();
        $results = $search->search(Yii::$app->request->queryParams);
        
        return $this->render('index', $results);
    }

    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionView() {
        $params = Yii::$app->request->queryParams;
        $model = $this->findModel(ArrayHelper::getValue($params, 'id'));
        $isBuy = true; //Buyunit::checkAuthorize($model->cat_id);
        $coursedata = CourseData::getCourseData($model->id);
        if ($isBuy) {
            $model->play_count += 1;
            $model->save(false, ['play_count']);

            return $this->render('view', [
                        'model' => $model,
                        'filter' => $params,
                        'attrs' => $this->getCourseAttr($model->id),
                        'manNum' => $this->getCourseStudyManNum($model->id),            //获取看过该课件的学生的所有数据和学生头像
                        'studyNum' => $this->getStudyNum($model->id),                   //学生学习该课件的次数
                        'lastStudyTime' => $this->getLastStudyTime($model->id),         //学生上一次学习该课件时间是？天前
                        'totalLearningTime' => $this->getTotalLearningTime($model->id), //学生学习该课件的总时长
                        'studytime' => $this->getTodayStudyTime($model->id),            //今天的学习时长
                        'cosdate' => $coursedata,
            ]);
        } else {
            $this->layout = '@frontend/modules/study/views/layouts/_main';
            return $this->render('/layouts/_error');
        }
    }

    /**
     * Renders Search the index view for the module
     * @return string
     */
    public function actionSearch() {
        
        $results = $this->saveSearchLog(Yii::$app->request->queryParams);
        
        return $this->redirect(['index', 'par_id'=>$results[0],'keyword'=>$results[1],'#'=>'scroll']);
    }

    /**
     * 记录学习结果（学习时长）
     * @return boolean
     */
    public function actionStudyLog($course_id) {
        /* @var $model StudyLog */
        Yii::$app->getResponse()->format = 'json';
        $user_id = Yii::$app->user->id;
        $model = StudyLog::find()->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->andWhere(['between', 'created_at', strtotime('today'), strtotime('tomorrow')])
                ->one();
        if (!$model) {
            $model = new StudyLog();
            $model->course_id = $course_id;
            $model->user_id = $user_id;
            $model->studytime = 30;
            if ($model->validate() && $model->save()) {
                return [
                    'code' => '200',
                    'data' => $model,
                    'message' => '保存成功',
                ];
            } else {
                var_dump($model->errors);
                return [
                    'code' => '400',
                    'message' => '保存失败',
                ];
            }
        } else if ($model != null) {
            $model->studytime = $model->studytime + 30;
            $model->save(false);
        }
        return [
            'code' => '200',
            'data' => $model,
            'message' => '',
        ];
    }

    /**
     * 点赞功能
     * @return type       是否成功：0为否，1为是
     */
    public function actionCourseAppraise() {
        Yii::$app->getResponse()->format = 'json';
        $type = 0;              //是否成功：0为否，1为是
        $message = '点赞失败';   //消息
        $errors = [];           //错误
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'CourseAppraise.course_id');
        $user_id = ArrayHelper::getValue($post, 'CourseAppraise.user_id');
        $number = ArrayHelper::getValue($post, 'Course.zan_count');
        $values = [
            'course_id' => $course_id,
            'user_id' => $user_id,
            'result' => '1',
            'created_at' => time(),
            'updated_at' => time(),
        ];
        $num = Yii::$app->db->createCommand()->insert(CourseAppraise::tableName(), $values)->execute();
        try {
            if ($num > 0) {
                $model = $this->findModel($course_id);
                $model->zan_count = $number + 1;
                $is = $model->update();
                $type = 1;
                $number = $model->zan_count;
                $message = '点赞成功';
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type' => $type,
            'number' => $number,
            'message' => $message,
            'error' => $errors
        ];
    }

    /**
     * 取消点赞功能
     * @return type       是否成功：0为否，1为是
     */
    public function actionCancelCourseAppraise() {
        Yii::$app->getResponse()->format = 'json';
        $type = 0;              //是否成功：0为否，1为是
        $message = '取消点赞失败';   //消息
        $errors = [];           //错误
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'CourseAppraise.course_id');
        $user_id = ArrayHelper::getValue($post, 'CourseAppraise.user_id');
        $number = ArrayHelper::getValue($post, 'Course.zan_count');
        $values = [
            'course_id' => $course_id,
            'user_id' => $user_id,
        ];
        $num = Yii::$app->db->createCommand()->delete(CourseAppraise::tableName(), $values)->execute();
        try {
            if ($num > 0) {
                $model = $this->findModel($course_id);
                $model->zan_count = $number - 1;
                $is = $model->update();
                $type = 1;
                $number = $model->zan_count;
                $message = '取消点赞成功';
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type' => $type,
            'number' => $number,
            'message' => $message,
            'error' => $errors
        ];
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
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * 获取过滤筛选的结果
     * @param array $params                 传参数
     * @return array
     */
    public function getFilterSearch($params) 
    {
        
        $cat_id = ArrayHelper::getValue($params, 'cat_id');                 //分类
        $sub_id = ArrayHelper::getValue($params, 'cat_id');                 //学科
        $term = ArrayHelper::getValue($params, 'term');                     //册数
        $grade = ArrayHelper::getValue($params, 'grade');                   //年级
        $tm_ver = ArrayHelper::getValue($params, 'tm_ver');                 //版本
        $filter = [];         
        //学科
        if ($cat_id != null) {
            $courseCats = (new Query())
                    ->select(['CourseCat.name AS filter_value'])
                    ->from(['CourseCat' => CourseCategory::tableName()])
                    ->where(['id' => $cat_id])
                    ->one();
            $paramsCopy = $params;
            unset($paramsCopy['cat_id']);
            $catItems = [Yii::t('app', 'Cat') => array_merge($courseCats, ['url' => Url::to(array_merge(['index'], $paramsCopy))])];
        }

        

        $resultItems = array_merge($catItems, $attrItems);
        return $resultItems;
    }

    /**
     * 获取该课件下的所有属性
     * @param integer $course_id               课件id
     * @return type
     */
    public function getCourseAttr($course_id) {
        return (new Query())
            ->select(['CourseAttr.value'])
            ->from(['CourseAttr' => CourseAttr::tableName()])
            ->leftJoin(['Attribute' => CourseAttribute::tableName()], 'Attribute.id = CourseAttr.attr_id')
            ->where(['CourseAttr.course_id' => $course_id, 'Attribute.index_type' => 1])
            ->orderBy(['Attribute.sort_order' => SORT_ASC])
            ->all();
    }

    /**
     * 保存搜索日志数据
     * @param array $params
     */
    public function saveSearchLog($params) 
    {
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
        //返回所需参数
        return [$par_id,$keywords];
    }

    /**
     * 获取学生学习该课件的次数
     * @param type $course_id   课件ID
     * @return type             学生学习该课件的次数
     */
    public function getStudyNum($course_id) {
        $user_id = Yii::$app->user->id;
        $studyNum = StudyLog::find()->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->all();
        return $studyNum;
    }

    /**
     * 获取学生上一次学习该课件时间是？天前
     * @param type $course_id   课件ID
     * @return type             学生上一次学习该课件时间是？天前
     */
    public function getLastStudyTime($course_id) {
        /* @var $studyTime StudyLog */
        $user_id = Yii::$app->user->id;
        $studyTime = (new Query())
                ->select('StudyLog.updated_at')
                ->from(['StudyLog' => StudyLog::tableName()])
                ->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->orderBy('id DESC')
                ->limit(2)
                ->all();
        
        if (count($studyTime) > 1) {
            $lasttime = $studyTime["1"]["updated_at"];              //上一次学习的具体时间
        } else if (count($studyTime) == null) {
            $lasttime = strtotime(date("Y-m-d"));
        } else {
            $lasttime = $studyTime["0"]["updated_at"];
        }
        $currenttime = strtotime(date("Y-m-d"));                    //当前时间
        $days = ceil(($currenttime - $lasttime) / 86400);          //相隔天数
        return $days;
    }

    /**
     * 获取学生学习该课件的总时长
     * @param type $course_id   课件ID
     * @return type             学生学习该课件的总时长
     */
    public function getTotalLearningTime($course_id) {
        $user_id = Yii::$app->user->id;
        $learningTime = (new Query())
                ->select(['SUM(StudyLog.studytime) as studytime'])
                ->from(['StudyLog' => StudyLog::tableName()])
                ->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->one();
        $totalLearningTime = $learningTime["studytime"];
        return $totalLearningTime;
    }

    /**
     * 获取看过该课件的学生的所有数据和学生头像
     * @param type $id      
     * @return type         看过该课件的学生的所有数据和学生头像
     */
    public function getCourseStudyManNum($id) {
        $query = (new Query())
                ->select(['StudyLog.user_id', 'User.avatar'])
                ->from(['StudyLog' => StudyLog::tableName()])
                ->leftJoin(['User' => WebUser::tableName()], 'User.id = StudyLog.user_id')
                ->where(['course_id' => $id])
                ->all();

        return $query;
    }

    /**
     * 获取今天的学习时长
     * @param type $course_id   课件ID
     * @return type             今天的学习时长
     */
    public function getTodayStudyTime($course_id) {
        $user_id = Yii::$app->user->id;
        $studytimelen = StudyLog::find()->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->andWhere(['between', 'created_at', strtotime('today'), strtotime('tomorrow')])
                ->one();
        if($studytimelen != null){
            $studytime = $studytimelen["studytime"];
        } else {
            $studytime = 0;
        }
        return $studytime;
    }
}
