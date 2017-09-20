<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseTemplate */

$this->title = Yii::t(null, '{Create} {Course}{Template}', [
            'Create' => Yii::t('app', 'Create'),
            'Course' => Yii::t('app', 'Course'),
            'Template' => Yii::t('app', 'Template'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Templates}', ['Course' => Yii::t('app', 'Course'), 'Templates' => Yii::t('app', 'Templates')]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-template-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
