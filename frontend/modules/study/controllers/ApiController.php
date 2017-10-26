<?php

namespace frontend\modules\study\controllers;

use common\models\CategoryJoin;
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

    public $enableCsrfValidation = false;

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
                    'get-course-data' => ['get'],
                    'get-course-studyinfo' => ['get'],
                    'get-examine-result' => ['get'],
                    'get-course-examine-result' => ['get'],
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
     * 获取课程数据
     * @param integer $course_id    课程ID
     * @param string $user_id   用户ID
     */
    public function actionGetCourseData($course_id, $user_id = null) {
        if ($user_id == null) {
            $user_id = Yii::$app->user->id;
        }
        return CourseData::getCourseData($course_id,$user_id);
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
        $user_id = ArrayHelper::getValue($post, 'user_id', Yii::$app->user->id);
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
        $user_id = ArrayHelper::getValue($post, 'user_id', Yii::$app->user->id);
        $course_id = ArrayHelper::getValue($post, 'course_id');
        $score = ArrayHelper::getValue($post, 'score');
        $node_id = ArrayHelper::getValue($post, 'node_id', null);
        $data = ArrayHelper::getValue($post, 'data', '');
        //找出课后测试的环节，拿环节ID
        if ($node_id == null) {
            $node = (new Query())
                    ->select('Node.id')
                    ->from(['Node' => CoursewaveNode::tableName()])
                    ->leftJoin(['PNode' => CoursewaveNode::tableName()], 'Node.parent_id = PNode.id')
                    ->where([
                        'PNode.course_id' => $course_id,
                        'PNode.sign' => 'khcs',])
                    ->one();
            $node_id = !$node ? : $node['id'];
        }


        if ($node_id != null) {
            //保存记录
            $examineResult = new ExamineResult();
            $examineResult->node_id = $node_id;
            $examineResult->user_id = $user_id;
            $examineResult->score = $score;
            $examineResult->data = $data;

            //更改测试环节状态
            $node_result = CoursewaveNodeResult::find()
                    ->where(['user_id' => $user_id, 'node_id' => $node_id])
                    ->one();
            if ($node_result == null) {
                $node_result = new CoursewaveNodeResult();
                $node_result->node_id = $node_id;
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

    /**
     * 获取考核结果
     * @param string $node_id   环节ID，为空时
     * @param string $user_id   用户ID
     */
    public function actionGetExamineResult($node_id, $user_id = null) {
        if ($user_id == null) {
            $user_id = Yii::$app->user->id;
        }

        $query = ExamineResult::find()
                ->where(['user_id' => $user_id, 'node_id' => $node_id])
                ->orderBy('created_at')
                ->asArray();
        return $query->one();
    }

    /**
     * 获取该课程所有考核环节最近一次考核结果
     * @param int $course_id
     * @param string $user_id   用户ID
     */
    public function actionGetCourseExamineResult($course_id, $user_id = null) {
        if ($user_id == null) {
            $user_id = Yii::$app->user->id;
        }
        $subquery = (new Query())
                ->select(['Node.id', 'Node.title', 'ExamineResult.score', 'ExamineResult.data'])
                ->from(['ExamineResult' => ExamineResult::tableName()])
                ->leftJoin(['Node' => CoursewaveNode::tableName()], 'Node.id = ExamineResult.node_id')
                ->where(['Node.course_id' => $course_id, 'ExamineResult.user_id' => $user_id])
                ->orderBy('ExamineResult.created_at desc');
        $query = (new Query())
                ->from(['result' => $subquery])
                ->groupBy('id');

        return $query->all();
    }

    /**
     * 加入学院
     * @param integer $cate_id
     */
    public function actionJoinCollege($cate_id) {
        $join = CategoryJoin::findOne(['category_id'=>$cate_id, 'user_id'=> Yii::$app->user->id]);
        if($join != null){
            $this->redirect(['college/index', 'par_id' => $cate_id]);
        }else{
            $join = new CategoryJoin(['category_id'=>$cate_id,'user_id'=> Yii::$app->user->id]);
            if($join->save())
               $this->redirect(['college/index', 'par_id' => $cate_id]); 
            else
                throw new ServerErrorHttpException('加入学院失败！');
        }
    }
    
}
