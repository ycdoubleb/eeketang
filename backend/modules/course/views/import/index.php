<?php

use common\models\course\Course;
use common\models\course\searchs\CourseSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel CourseSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Course */

$this->title = Yii::t('app', '{Course}{Import}',[
    'Course' => Yii::t('app', 'Course'),
    'Import' => Yii::t('app', 'Import'),
]);
?>
<div class="course-import-index">

    <?php ActiveForm::begin([
         'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    <div class="form-group">
        <label class='control-label'>选择要导入的表格：</label>
        <?= Html::input('file', 'course-data') ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>