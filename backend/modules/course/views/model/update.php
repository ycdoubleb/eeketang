<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseModel */

$this->title = Yii::t(null, '{Update} {Course}{Model}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Course' => Yii::t('app', 'Course'),
            'Model' => Yii::t('app', 'Model'),
        ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Model}: ', [
        'Course' => Yii::t('app', 'Course'),
        'Model' => Yii::t('app', 'Model'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-model-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
