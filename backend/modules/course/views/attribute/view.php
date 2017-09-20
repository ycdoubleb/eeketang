<?php

use common\models\course\CourseAttribute;
use common\widgets\GridViewChangeSelfColumn;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model CourseAttribute */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course}{Attributes}: ', [
            'Course' => Yii::t('app', 'Course'),
            'Attributes' => Yii::t('app', 'Attributes'),
        ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-attribute-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'course_model_id',
                'value' => function ($_model) use($course_models){
                    return $course_models[$_model->course_model_id];
                },
            ],
            [
                'attribute' => 'type',
                'value' => function ($_model){
                    return CourseAttribute::$type_keys[$_model->type];
                },
            ],
           [
                'attribute' => 'input_type',
                'value' => function ($_model){
                    return CourseAttribute::$input_type_keys[$_model->input_type];
                },
            ],
            [
                'attribute' => 'index_type',
                'value' => function ($_model){
                    return CourseAttribute::$index_type_keys[$_model->index_type];
                },
            ],
            'values:ntext',
            'sort_order',
        ],
    ]) ?>

</div>
