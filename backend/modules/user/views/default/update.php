<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', '{Update}{User}： ',[
    'Update' => Yii::t('app', 'Update'),
    'User' => Yii::t('app', 'User'),
]).$model->nickname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{User}{List} ',[
    'List' => Yii::t('app', 'List'),
    'User' => Yii::t('app', 'User'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->nickname]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
