<?php

namespace frontend\modules\study\controllers;

use common\models\Note;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class NoteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * 创建笔记
     */
    public function actionCreate(){
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $post = Yii::$app->getRequest()->post();
        
        $note = new Note();
        
    }
    
    /**
     * 搜索笔记
     */
    public function actionSearch(){
        
        $response = Yii::$app->getResponse();
        $response->format = 'json';
        $params = Yii::$app->getRequest()->getQueryParams();
        
        
        $query = (new Query())->from(['Note' => Note::tableName()]);
        
        $token = ArrayHelper::getValue($params, 'token' , null);                //访问令牌
        $page = ArrayHelper::getValue($params, 'page' , 1);                     //第几页
        $page_size = ArrayHelper::getValue($params, 'page_size' , 10);          //一页几条数据
        $show_all = ArrayHelper::getValue($params, 'show_all' , 0);             //是否包括所有人数据
        try{
            $user = User::findIdentityByAccessToken($token);
        } catch (\Exception $ex) {
            return [
                'code' => '401',
                'message' => $ex->getMessage(),
            ];
        }
        $course_id = ArrayHelper::getValue($params, '$course_id');              //指定的课程
        if(isset($user)){
            $query->leftJoin(['User' => User::tableName()], 'Note.user_id = User.id');
            $query->select(['Note.*','User.nickname','User.avatar']);
            $query->andFilterWhere([
                'user_id' => $show_all ? null : $user->id,
                'course_id' => $course_id,
            ]);
            //分页
            $query->offset(($page-1) * $page_size);
            $query->limit($page_size);
            //符合条件的笔记总数
            $total_num = $query->count();
        }
        
        return [
            'code' => $response->getStatusCode(),
            'data' => isset($user) ? [
                'page' => $page,
                'page_size' => $page_size,
                'total_page' => ceil($total_num/$page_size),
                'total_num' => $total_num,
                'notes' => $query->all(),
            ] : [],
            'message' => $response->statusText
        ];
    }
}
