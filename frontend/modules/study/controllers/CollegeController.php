<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseAppraise;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\Favorites;
use common\models\PlayLog;
use common\models\SearchLog;
use common\models\StudyLog;
use common\models\WebUser;
use common\widgets\players\CourseData;
use frontend\modules\study\searchs\CourseListSearch;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `study` module
 */
class CollegeController extends Controller {

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
        $results = $search->collegeSearch();
        $filterItem = $search->filterSearch();
        $parModel = CourseCategory::findOne($results['filter']['par_id']);
        if($parModel->level <= 1)
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        
        return $this->render('index', array_merge($results, 
                array_merge(array_merge(['parModel' => $parModel], ['filterItem' => $filterItem]), 
                ['tm_logo' => Course::$tm_logo])));
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
                        'isFavorite' => Favorites::findOne(['course_id' => $model->id, 'user_id' => Yii::$app->user->id]) != null,
                        'isAppraise' => CourseAppraise::findOne(['course_id' => $model->id, 'user_id' => Yii::$app->user->id]) != null,
                        'examines' => CourseData::getTestInfoRecord(['course_id' => $model->id, 'user_id' => Yii::$app->user->id]),
                        'manNum' => $this->getCourseStudyManNum($model->id), //获取看过该课件的学生的所有数据和学生头像
                        'studyNum' => $this->getStudyNum($model->id), //学生学习该课件的次数
                        'lastStudyTime' => $this->getLastStudyTime($model->id), //学生上一次学习该课件时间是？天前
                        'totalLearningTime' => $this->getTotalLearningTime($model->id), //学生学习该课件的总时长
                        'studytime' => $this->getTodayStudyTime($model->id), //今天的学习时长
                        'cosdate' => $coursedata,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            //$this->layout = '@frontend/modules/study/views/layouts/_main';
            //return $this->render('/layouts/_error');
        }
    }

    /**
     * Renders Search the index view for the module
     * @return string
     */
    public function actionSearch() {

        $results = $this->saveSearchLog(Yii::$app->request->queryParams);
        
        return $this->redirect(array_merge(['index'], array_merge($results['filter'], ['page'=>1,'#' => 'scroll'])));
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
     * 保存播放记录
     * @param string $course_id     课程ID
     * @return type
     */
    public function actionPlayLog($course_id) {
        /* @var $model PlayLog */
        Yii::$app->getResponse()->format = 'json';
        $user_id = Yii::$app->user->id;
        $model = PlayLog::find()->where([
                    'course_id' => $course_id,
                    'user_id' => $user_id
                ])
                ->one();
        $value = [
            'course_id' => $course_id,
            'user_id' => $user_id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        /** 添加$searchLogs数组到表里 */
        if ($value != null)
            Yii::$app->db->createCommand()->insert(PlayLog::tableName(), $value)->execute();
        return [
            'code' => '200',
            'data' => $model,
            'message' => '',
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
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
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
                ->one();

        if ($studyTime != null) {
            $lasttime = $studyTime["updated_at"];                  //上一次学习的具体时间
        } elseif ($studyTime == null) {
            $lasttime = strtotime(date("Y-m-d H:i:s"));
        }

        $currenttime = strtotime(date("Y-m-d H:i:s"));              //当前时间

        $day = intval(($currenttime - $lasttime) / 86400);              //相隔天数
        $hour = intval(($currenttime - $lasttime) % 86400 / 3600);       //相隔时间
        $minute = intval(($currenttime - $lasttime) % 86400 / 60);       //相隔时间

        if ($day > 0) {
            return $day . '天前';
        } elseif ($hour > 0) {
            return $hour . '小时前';
        } elseif ($minute > 0) {
            return $minute . '分钟前';
        } else {
            return '刚刚';
        }
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
    public static function getCourseStudyManNum($id) {
        $query = (new Query())
                ->select(['StudyLog.user_id', 'User.avatar', 'User.real_name'])
                ->from(['StudyLog' => StudyLog::tableName()])
                ->leftJoin(['User' => WebUser::tableName()], 'User.id = StudyLog.user_id')
                ->distinct()                 //去除重复的数据
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
        if ($studytimelen != null) {
            $studytime = $studytimelen["studytime"];
        } else {
            $studytime = 0;
        }
        return $studytime;
    }

}
