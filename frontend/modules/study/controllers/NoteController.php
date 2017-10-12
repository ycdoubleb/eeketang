<?php

namespace frontend\modules\study\controllers;

use common\models\Note;
use common\models\WebUser;
use Exception;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class NoteController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'search', 'delete', 'update'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 创建笔记
     */
    public function actionCreate() {
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $post = Yii::$app->getRequest()->post();
        try {
            $user = WebUser::findIdentityByAccessToken(ArrayHelper::getValue($post, 'token', ''));
        } catch (Exception $ex) {
            return [
                'code' => '401',
                'message' => $ex->getMessage(),
            ];
        }
        $note = new Note();
        $note->title = ArrayHelper::getValue($post, 'title', '');
        $note->content = ArrayHelper::getValue($post, 'content', '');
        $note->data = ArrayHelper::getValue($post, 'data', '');
        $note->user_id = $user->id;
        $note->course_id = ArrayHelper::getValue($post, 'course_id', null);
        $code = 200;
        $message = '创建成功！';
        if ($note->course_id == null || !$note->save()) {
            $message = '';
            if ($note->course_id == null) {
                $message = 'course_id 不能为NUll';
            } else {
                foreach ($note->errors as $error) {
                    foreach ($error as $key => $value) {
                        $message .= $key . ':' . $value . "\n";
                    }
                }
            }
            $code = 400;
            $message = "创建失败！\n" . $message;
        }

        return [
            'code' => $code,
            'message' => $message,
        ];
    }

    /**
     * 创建笔记
     */
    public function actionUpdate($id) {
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $post = Yii::$app->getRequest()->post();
        try {
            $user = WebUser::findIdentityByAccessToken(ArrayHelper::getValue($post, 'token', ''));
        } catch (Exception $ex) {
            return [
                'code' => '401',
                'message' => $ex->getMessage(),
            ];
        }
        $note = Note::findOne($id);
        $note->title = ArrayHelper::getValue($post, 'title', '');
        $note->content = ArrayHelper::getValue($post, 'content', '');
        $note->data = ArrayHelper::getValue($post, 'data', '');
        $code = 200;
        $message = '更新成功！';
        if (!$note->save()) {
            $message = '';
            foreach ($note->errors as $error) {
                foreach ($error as $key => $value) {
                    $message .= $key . ':' . $value . "\n";
                }
            }
            $code = 400;
            $message = "更新失败！\n" . $message;
        }

        return [
            'code' => $code,
            'message' => $message,
        ];
    }

    /**
     * 搜索笔记
     */
    public function actionSearch() {

        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $params = Yii::$app->getRequest()->getQueryParams();


        $query = (new Query())->from(['Note' => Note::tableName()]);

        $token = ArrayHelper::getValue($params, 'token', null);                //访问令牌
        $page = ArrayHelper::getValue($params, 'page', 1);                     //第几页
        $page_size = ArrayHelper::getValue($params, 'page_size', 10);          //一页几条数据
        $show_all = ArrayHelper::getValue($params, 'show_all', 0);             //是否包括所有人数据
        $total_page = 1;
        try {
            
            $user = WebUser::findIdentityByAccessToken($token);
        } catch (Exception $ex) {
            
        }
        $course_id = ArrayHelper::getValue($params, 'course_id');              //指定的课程
        if (isset($user) && $user != null) {
            $query->leftJoin(['User' => WebUser::tableName()], 'Note.user_id = User.id');
            $query->select(['Note.*', 'User.real_name', 'User.avatar']);
            $query->andFilterWhere([
                'user_id' => $show_all ? null : $user->id,
                'course_id' => $course_id,
            ]);
            //分页
            $query->offset(($page - 1) * $page_size);
            $query->limit($page_size);
            //符合条件的笔记总数
            $total_num = $query->count();
            $total_page = ceil($total_num / $page_size);
            $total_page != 0 ? : $total_page = 1;
        } else {
            return [
                'code' => '401',
                'message' => '非法获取！',
            ];
        }

        return [
            'code' => $response->getStatusCode(),
            'data' => isset($user) ? [
                'page' => $page,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'total_num' => $total_num,
                'notes' => $query->all(),
                    ] : [],
            'message' => $response->statusText
        ];
    }

    public function actionDelete($id) {
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $post = Yii::$app->getRequest()->post();
        try {
            $user = WebUser::findIdentityByAccessToken(ArrayHelper::getValue($post, 'token', ''));
        } catch (Exception $ex) {
            return [
                'code' => '401',
                'message' => $ex->getMessage(),
            ];
        }

        if (($model = Note::findOne($id)) != null) {
            $model->delete();
            return [
                'code' => 200,
                'message' => '删除成功',
            ];
        } else {
            return [
                'code' => 400,
                'message' => '删除失败！找不到 id = ' . $id . ' 笔记',
            ];
        }
    }

}
