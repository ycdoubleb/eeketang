<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\course\CourseAttribute */

$this->title = Yii::t(null, '{Create} {Course}{Attribute}: ', [
            'Create' => Yii::t('app', 'Create'),
            'Course' => Yii::t('app', 'Course'),
            'Attribute' => Yii::t('app', 'Attribute'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Attributes}: ', [
            'Course' => Yii::t('app', 'Course'),
            'Attributes' => Yii::t('app', 'Attributes'),
        ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
        'course_models' => $course_models,
    ]) ?>

</div>
