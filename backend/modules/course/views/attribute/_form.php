<?php

use common\models\course\CourseAttribute;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseAttribute */
/* @var $form ActiveForm */
?>

<div class="course-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'course_model_id')->dropDownList($course_models) ?>

    <?= $form->field($model, 'type')->dropDownList(CourseAttribute::$type_keys) ?>

    <?= $form->field($model, 'input_type')->dropDownList(CourseAttribute::$input_type_keys)?>

    <?php echo $form->field($model, 'index_type')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]);
    ?>

    <?= $form->field($model, 'values')->textarea(['rows' => 6,'placeholder'=>'录入方式为手工或者多行文本时，此输入框不需填写。每行一项']) ?>
    
    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
