<?php

use common\models\course\Course;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Course */

$this->title = $model->courseware_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Course}{List}', [
        'Course' => Yii::t('app', 'Course'),
        'List' => Yii::t('app', 'List'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$attributes = [
    'id',
    'courseware_sn',
    [
        'attribute' => 'course_model_id',
        'value' => $model->courseModel->name,
    ],
    [
        'attribute' => 'type',
        'value' => Course::$type_keys[$model->type],
    ],
    [
        'attribute' => 'template_sn',
        'value' => $model->template->name . "（ $model->template_sn ）",
    ],
    [
        'attribute' => 'cat_id',
        'value' => $model->category->fullPath,
    ],
    'tm_ver',
    [
        'attribute' => 'subject_id',
        'value' => $model->subject->name,
    ],
    [
        'attribute' => 'grade',
        'value' => Course::$grade_keys[$model->grade],
    ],
    [
        'attribute' => 'term',
        'value' => Course::$term_keys[$model->term],
    ],
    'unit',
    'name',
    'courseware_name',
    'learning_objectives:ntext',
    'introduction:ntext',
    'synopsis:ntext',
    [
        'attribute' => 'teacher_id',
        'value' => isset($model->teacher) ? $model->teacher->name : null,
    ],
    [
        'attribute' => 'is_recommend',
        'value' => Yii::t('app', $model->is_recommend ? 'Yes' : 'No'),
    ],
    [
        'attribute' => 'is_publish',
        'value' => Yii::t('app', $model->is_publish ? 'Yes' : 'No'),
    ],
    'path',
    'course_order',
    'sort_order',
    'play_count',
    'zan_count',
    'favorites_count',
    'comment_count',
    'keywords',
    'publish_time:datetime',
    [
        'attribute' => 'publisher_id',
        'value' => isset($model->publisher) ? $model->publisher->nickname : null,
    ],
    [
        'attribute' => 'create_by',
        'value' => isset($model->creater) ? $model->creater->nickname : null,
    ],
    'created_at:datetime',
    'updated_at:datetime',
];
$attr_itmes = [];
foreach ($attrs as $attr){
    $attr_itmes []= ['label' => $attr['name'],'value' => $attr['value']];
}
array_splice($attributes, 4, 0, $attr_itmes);
?>
<div class="course-view">

    <p>
        <?= Html::a(Yii::t('app', 'Preview'), ['preview', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ])
    ?>

</div>
