<?php

use common\models\course\CourseTemplate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseTemplate */
/* @var $form ActiveForm */
$getCatUrl = Url::to(['/course/category/search-children'], true);
$prompt = Yii::t('app', 'Select Placeholder');
?>

<div class="course-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'player')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>