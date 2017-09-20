<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\Course */

$this->title = Yii::t('app', '{Update} {Course}: ', [
    'Update' => Yii::t('app', 'Update'),
    'Course' => Yii::t('app', 'Course'),
]) . $model->courseware_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Course}{List}',[
    'Course' => Yii::t('app', 'Course'),
    'List' => Yii::t('app', 'List'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->courseware_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-update">

    <?= $this->render('_form', [
        'model' => $model,
        'templates' => $templates,          //模板
        'course_models' => $course_models,  //课程模型
        'teachers' => $teachers,            //教师
    ]) ?>

</div>
