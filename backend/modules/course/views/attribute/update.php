<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseAttribute */

$this->title = Yii::t(null, '{Update} {Course}{Attribute}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Course' => Yii::t('app', 'Course'),
            'Attribute' => Yii::t('app', 'Attribute'),
        ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Course Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-attribute-update">

    <?= $this->render('_form', [
        'model' => $model,
        'course_models' => $course_models,
    ]) ?>

</div>
