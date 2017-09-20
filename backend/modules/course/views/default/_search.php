<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\course\searchs\CourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_cat_id') ?>

    <?= $form->field($model, 'cat_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'img') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'learning_objectives') ?>

    <?php // echo $form->field($model, 'introduction') ?>

    <?php // echo $form->field($model, 'teacher_id') ?>

    <?php // echo $form->field($model, 'is_recommend') ?>

    <?php // echo $form->field($model, 'is_publish') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'play_count') ?>

    <?php // echo $form->field($model, 'zan_count') ?>

    <?php // echo $form->field($model, 'favorites_count') ?>

    <?php // echo $form->field($model, 'comment_count') ?>

    <?php // echo $form->field($model, 'publish_time') ?>

    <?php // echo $form->field($model, 'publisher') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'course_model_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
