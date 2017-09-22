<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WebUser */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Web Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password',
            'real_name',
            'sex',
            'tel',
            'school_id',
            'subjects',
            'source',
            'organization',
            'create_time',
            'status',
            'end_time',
            'role',
            'avatar',
            'usages',
            'name',
            'account_non_locked',
            'remarks:ntext',
            'max_user',
            'purchase',
            'edu_id',
            'workgroup_id',
            'workgroup_name',
            'workgroup_code',
            'access_token',
            'last_login_time',
            'auth_key',
        ],
    ]) ?>

</div>
