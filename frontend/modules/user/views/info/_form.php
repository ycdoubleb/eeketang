<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\course\Course;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-profile-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-profile-form'
    ]); ?>

    <?= $form->field($model, 'start_time')
        ->dropDownList(Course::$grade_keys, ['value' => Yii::$app->user->identity->profile->getGrade(false)])->label('')?>
    
    <?= Html::activeHiddenInput($model, 'user_id', ['value' => Yii::$app->user->id]) ?>
    
    <?php ActiveForm::end(); ?>

</div>
