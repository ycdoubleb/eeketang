<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseModel */

$this->title = Html::a(Yii::t('app', Yii::t(null, '{Create}{Course}{Model}', [
                            'Create' => Yii::t('app', 'Create'),
                            'Course' => Yii::t('app', 'Course'),
                            'Model' => Yii::t('app', 'Model'),
        ])));
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Models}', [
        'Course' => Yii::t('app', 'Course'),
        'Models' => Yii::t('app', 'Models'),
    ]), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-model-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
