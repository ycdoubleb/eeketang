<?php

use common\models\course\searchs\CourseModelSearch;
use common\widgets\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseModelSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Course}{Models}',[
    'Course' => Yii::t('app', 'Course'),
    'Models' => Yii::t('app', 'Models'),
]);
?>
<div class="course-model-index">

    <p>
        <?= Html::a(Yii::t('app', Yii::t(null, '{Create}{Course}{Models}',[
                'Create' => Yii::t('app', 'Create'),
                'Course' => Yii::t('app', 'Course'),
                'Models' => Yii::t('app', 'Models'),
            ])), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{Attr} {view} {update} {delete}',
                'options'=>['style'=>'width:100px'],
                'buttons' => [
                    'Attr' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', 'Attribute'),
                            'aria-label' => Yii::t('app', 'Attribute'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="fa fa-cogs"></span>', ['/course/attribute', 'CourseAttributeSearch[course_model_id]' => $key], $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
