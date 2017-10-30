<?php

use common\models\WebUser;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model WebUser */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Web Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-user-view">

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
            'create_time:datetime',
            [
                'attribute' => 'role',
                'value' => function($m){
                    return WebUser::$roleName[$m->role];
                }
            ],
            'avatar',
            'access_token',
            'last_login_time:datetime',
            'auth_key',
        ],
    ]) ?>

</div>
