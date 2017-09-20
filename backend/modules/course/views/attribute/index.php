<?php

use backend\modules\course\assets\CourseAssets;
use common\models\course\CourseAttribute;
use common\models\course\searchs\CourseAttributeSearch;
use common\widgets\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseAttributeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Course}{Attributes} ', [
            'Course' => Yii::t('app', 'Course'),
            'Attributes' => Yii::t('app', 'Attributes'),
        ]);
?>
<div class="course-attribute-index">

    <p>
        <?= Html::a(Yii::t(null, '{Create} {Course}{Attribute}', [
            'Create' => Yii::t('app', 'Create'),
            'Course' => Yii::t('app', 'Course'),
            'Attribute' => Yii::t('app', 'Attribute'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style'=>['width' => '50px']],
            ],    
             [
                'attribute' => 'name',
                'options' => ['style'=>['width' => '120px']],
            ],   
            [
                'attribute' => 'course_model_id',
                'value' => function ($_model) use($course_models){
                    return  !isset($course_models[$_model->course_model_id]) ? null : $course_models[$_model->course_model_id];
                },
                'filter' => $course_models,
            ],
            [
                'attribute' => 'type',
                'options' => ['style'=>['width' => '100px']],
                'value' => function ($_model){
                    return CourseAttribute::$type_keys[$_model->type];
                },
               'filter' => CourseAttribute::$type_keys,
            ],
           [
                'attribute' => 'input_type',
                'options' => ['style'=>['width' => '100px']],
                'value' => function ($_model){
                    return CourseAttribute::$input_type_keys[$_model->input_type];
                },
                'filter' => CourseAttribute::$input_type_keys,
            ],
            [
                'attribute' => 'index_type',
                'options' => ['style'=>['width' => '100px']],
                'class' => GridViewChangeSelfColumn::className(),
                'filter' => CourseAttribute::$index_type_keys,
            ],
            [
                'attribute' => 'values',
                'format' => 'raw',
                'value' => function ($_model){
                    return Html::tag('span', $_model->values, ['style'=>'display: block;text-overflow: ellipsis;white-space: nowrap; overflow: hidden;']);
                },
            ],
            [
                'attribute' => 'sort_order',
                'options' => ['style'=>['width' => '100px']],
                'class' => GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>
<?php 
    CourseAssets::register($this);
?>
