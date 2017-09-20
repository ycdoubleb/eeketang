<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\course\Course */

$this->title = Yii::t('app', '{Create} {Course}',[
    'Create' => Yii::t('app', 'Create'),
    'Course' => Yii::t('app', 'Course'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Course}{List}',[
    'Course' => Yii::t('app', 'Course'),
    'List' => Yii::t('app', 'List'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-create">

    <?= $this->render('_form', [
        'model' => $model,
        'templates' => $templates,          //模板
        'course_models' => $course_models,  //课程模型
        'teachers' => $teachers,            //教师
    ]) ?>

</div>
