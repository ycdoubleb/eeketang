<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu\models\MenuBackend */
/* @var $form yii\widgets\ActiveForm */

$prompt = Yii::t('app', 'Select Placeholder');
?>

<div class="menu-backend-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($parents, ['prompt' => $prompt]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'is_show')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); 
    ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
