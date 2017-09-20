<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\Subject */
$this->title = Yii::t(null, '{Update} {Subject}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Subject' => Yii::t('app', 'Subject'),
        ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Subject}: ', [
        'Subject' => Yii::t('app', 'Subject'),
    ]), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subject-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
