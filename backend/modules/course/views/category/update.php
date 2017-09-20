<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseCategory */

$this->title = Yii::t(null, '{Update} {Course}{Category}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Course' => Yii::t('app', 'Course'),
            'Category' => Yii::t('app', 'Category'),
        ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Category}: ', [
        'Course' => Yii::t('app', 'Course'),
        'Category' => Yii::t('app', 'Category'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-category-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ])
    ?>

</div>
