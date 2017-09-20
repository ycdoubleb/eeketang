<?php

use common\models\Menu;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Menu */
/* @var $form ActiveForm */

$prompt = Yii::t('app', 'Select Placeholder');

?>

<div class="menu-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($parents, ['prompt' => $prompt]) ?>
    
    <?= $form->field($model, 'relate_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'module')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?php 
    echo $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple'=>false,
        ], 
        'pluginOptions' => [
            'resizeImages' => true,
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' =>  '选择上传图像...',
            'initialPreview'=>[
                $model->isNewRecord ? 
                       Html::img(Yii::getAlias('@filedata').'/filedata/avatars/timg.jpg',['class'=>'file-preview-image', 'width' => '100%']) : 
                       Html::img(WEB_ROOT . $model->image, ['class'=>'file-preview-image', 'width' => '100%']),
            ],
            'overwriteInitial'=>true,
        ],
    ]); 
    ?>
    
    <?php
    echo $form->field($model, 'is_show')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); 
    ?>

    <?php 
    echo $form->field($model, 'is_jump')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); 
    ?>

    <?= $form->field($model, 'position')->dropDownList(Menu::$positionName, ['prompt' => $prompt]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>
    
    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
