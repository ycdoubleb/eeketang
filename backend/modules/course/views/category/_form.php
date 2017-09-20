<?php

use common\models\course\CourseCategory;
use common\widgets\depdropdown\DepDropdown;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseCategory */
/* @var $form ActiveForm */
?>

<div class="course-category-form">

    <?php $form = ActiveForm::begin(['id' => 'category-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'parent_id')->widget(DepDropdown::className(),[
        'plugOptions' => [
            'url' => Url::to('search-children', false),
            'level' => 2,
        ],
        'items' => CourseCategory::getSameLevelCats($model->parent_id),
        'values' => $model->parent_id == 0 ? [] : array_values(array_filter(explode(',', CourseCategory::getCatById($model->parent_id)->parent_id_path))),
    ]) ?>
    
    <?php
    echo $form->field($model, 'is_show')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]);
    ?>

    <?php
    echo $form->field($model, 'is_hot')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]);
    ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
