<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseAppraise;
use common\models\course\CoursewaveNode;
use common\models\course\CoursewaveNodeResult;
use common\models\ExamineResult;
use common\models\Favorites;
use common\models\Note;
use common\widgets\players\CourseData;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
                    'get-course-studyinfo' => ['get'],
                    'update-node' => ['post'],
                    'save-examine' => ['post'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $response = Yii::$app->getResponse();
        $response->on('beforeSend', function ($event) {
            $response = $event->sender;
            $response->data = [
                'code' => $response->getStatusCode(),
                'data' => $response->data,
                'message' => $response->statusText
            ];
            $response->format = Response::FORMAT_JSON;
        });
    }

    /**
     * 获取课程学习信息
     */
    public function actionGetCourseStudyinfo($course_id, $user_id = null) {
        if ($user_id == null) {
            $user_id = Yii::$app->user->id;
        }

        $noteQuery = Note::find()
                ->select(['title', 'content'])
                ->where(['course_id' => $course_id, 'user_id' => $user_id]);
        $noteCount = $noteQuery->count();
        $notes = $noteQuery->limit(5)
                ->asArray()
                ->all();
        return [
            'study_info' => CourseData::getStudyInfo($course_id, $user_id),
            'note' => [
                'max_count' => $noteCount,
                'notes' => $notes,
            ],
        ];
    }

    /**
     * 添加课程收藏
     * @throws ServerErrorHttpException
     */
    public function actionFavorites() {
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'Favorites.course_id');
        $user_id = ArrayHelper::getValue($post, 'Favorites.user_id');

        $favorite = Favorites::findOne(['course_id' => $course_id, 'user_id' => $user_id]);
        if ($favorite == null) {
            $favorite = new Favorites(['course_id' => $course_id, 'user_id' => $user_id]);
            if (!$favorite->save()) {
                throw new ServerErrorHttpException('收藏失败！');
            }
        }

        return '';
    }

    /**
     * 取消收藏
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionCancelFavorites() {
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'Favorites.course_id');
        $user_id = ArrayHelper::getValue($post, 'Favorites.user_id');

        $favorite = Favorites::findOne(['course_id' => $course_id, 'user_id' => $user_id]);
        if ($favorite == null) {
            throw new NotFoundHttpException('找不到对应收藏！');
        }
        if (!$favorite->delete()) {
            throw new ServerErrorHttpException('取消收藏失败！');
        }
        return '';
    }

    /**
     * 记录点赞
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionCourseAppraise() {
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'CourseAppraise.course_id');
        $user_id = ArrayHelper::getValue($post, 'CourseAppraise.user_id');
        $model = Course::findOne($course_id);

        $appraise = CourseAppraise::findOne(['course_id' => $course_id, 'user_id' => $user_id]);
        if ($appraise == null) {
            $appraise = new CourseAppraise(['course_id' => $course_id, 'user_id' => $user_id]);
            if (!$appraise->save()) {
                throw new ServerErrorHttpException('点赞失败！');
            } else {
                $model->zan_count ++;
                $model->update();
            }
            return [
                'number' => $model->zan_count
            ];
        }
    }

    /**
     * 取消点赞
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionCancelCourseAppraise() {
        $post = Yii::$app->request->post();
        $course_id = ArrayHelper::getValue($post, 'CourseAppraise.course_id');
        $user_id = ArrayHelper::getValue($post, 'CourseAppraise.user_id');
        $model = Course::findOne($course_id);

        $appraise = CourseAppraise::findOne(['course_id' => $course_id, 'user_id' => $user_id]);
        if ($appraise == null) {
            throw new NotFoundHttpException('找不到对应点赞！');
        }
        if (!$appraise->delete()) {
            throw new ServerErrorHttpException('取消点赞失败！');
        } else {
            $model->zan_count --;
            $model->update();
        }
        return [
            'number' => $model->zan_count
        ];
    }

    /**
     * 更新环节状态
     * @return string
     */
    public function actionUpdateNode() {
        $post = Yii::$app->getRequest()->post();
        $user_id = Yii::$app->user->id;
        $identifier = ArrayHelper::getValue($post, 'id');
        $result = ArrayHelper::getValue($post, 'result');

        $node_result = CoursewaveNodeResult::find()
                ->where(['user_id' => $user_id, 'node_id' => $identifier])
                ->one();
        if ($node_result == null) {
            $node_result = new CoursewaveNodeResult();
            $node_result->node_id = $identifier;
            $node_result->user_id = $user_id;
        }
        $node_result->result = $result;
        if (!$node_result->save()) {
            foreach ($node_result->errors as $error) {
                foreach ($error as $name => $mes) {
                    $mes .= "$name:$mes";
                }
            }
            throw new HttpException(500, $mes);
        }
        return '';
    }

    /**
     * 保存考核成绩
     */
    public function actionSaveExamine() {
        $post = Yii::$app->getRequest()->post();
        $user_id = Yii::$app->user->id;
        $course_id = ArrayHelper::getValue($post, 'course_id');
        $score = ArrayHelper::getValue($post, 'score');
        //找出课后测试的环节，拿环节ID
        $node = (new Query())
                ->select('Node.id')
                ->from(['Node' => CoursewaveNode::tableName()])
                ->leftJoin(['PNode' => CoursewaveNode::tableName()], 'Node.parent_id = PNode.id')
                ->where([
                    'PNode.course_id' => $course_id,
                    'PNode.sign' => 'khcs',])
                ->one();

        if ($node) {
            //保存记录
            $examineResult = new ExamineResult();
            $examineResult->node_id = $node['id'];
            $examineResult->user_id = Yii::$app->user->id;
            $examineResult->score = $score;

            //更改课后测试状态
            $node_result = CoursewaveNodeResult::find()
                    ->where(['user_id' => $user_id, 'node_id' => $node['id']])
                    ->one();
            if ($node_result == null) {
                $node_result = new CoursewaveNodeResult();
                $node_result->node_id = $node['id'];
                $node_result->user_id = $user_id;
            }
            $node_result->result = 2;

            if (!$examineResult->save()) {
                foreach ($examineResult->errors as $error) {
                    foreach ($error as $name => $mes) {
                        $mes .= "$name:$mes";
                    }
                }
                throw new HttpException(500, $mes);
            }
            $node_result->save();
        } else {
            throw new NotFoundHttpException('找不到对应环节！');
        }

        return "";
    }

}
