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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', Yii::t(null, '{Create}{Web User}',[
                'Create' => Yii::t('app', 'Create'),
                'Web User' => Yii::t('app', 'Web User'),
            ])), ['create'], ['class' => 'btn btn-success']) ?>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
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
