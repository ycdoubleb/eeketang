<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\WebUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Web Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Web User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'password',
            'real_name',
            'sex',
            // 'tel',
            // 'school_id',
            // 'subjects',
            // 'source',
            // 'organization',
            // 'create_time',
            // 'status',
            // 'end_time',
            // 'role',
            // 'avatar',
            // 'usages',
            // 'name',
            // 'account_non_locked',
            // 'remarks:ntext',
            // 'max_user',
            // 'purchase',
            // 'edu_id',
            // 'workgroup_id',
            // 'workgroup_name',
            // 'workgroup_code',
            // 'access_token',
            // 'last_login_time',
            // 'auth_key',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
