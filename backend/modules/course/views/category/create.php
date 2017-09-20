<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\course\CourseCategory */

$this->title = sprintf('%s%s%s', Yii::t('app', 'Create'), Yii::t('app', 'Course'), Yii::t('app', 'Category'));
$this->params['breadcrumbs'][] = ['label' => sprintf('%s%s', Yii::t('app', 'Course'), Yii::t('app', 'Category')), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
