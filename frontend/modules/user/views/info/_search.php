<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\searchs\WebUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="web-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'real_name') ?>

    <?= $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'school_id') ?>

    <?php // echo $form->field($model, 'subjects') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'organization') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'usages') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'account_non_locked') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'max_user') ?>

    <?php // echo $form->field($model, 'purchase') ?>

    <?php // echo $form->field($model, 'edu_id') ?>

    <?php // echo $form->field($model, 'workgroup_id') ?>

    <?php // echo $form->field($model, 'workgroup_name') ?>

    <?php // echo $form->field($model, 'workgroup_code') ?>

    <?php // echo $form->field($model, 'access_token') ?>

    <?php // echo $form->field($model, 'last_login_time') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
