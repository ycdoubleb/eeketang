<?php

use common\models\course\CourseTemplate;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model CourseTemplate */

$this->title = Yii::t('app', '{Update} {Course}{Templates}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Course' => Yii::t('app', 'Course'),
            'Templates' => Yii::t('app', 'Templates'),
        ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Course}{Templates}: ', [
            'Course' => Yii::t('app', 'Course'),
            'Templates' => Yii::t('app', 'Templates'),
        ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-template-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
