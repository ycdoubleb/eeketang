<?php

use common\models\course\CourseTemplate;
use common\models\course\searchs\CourseTemplateSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseTemplateSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Templates');
?>
<div class="course-template-index">
    <p>
        <?= Html::a(Yii::t(null, '{Create} {Course}{Template}',[
            'Create' => Yii::t('app', 'Create'),
            'Course' => Yii::t('app', 'Course'),
            'Template' => Yii::t('app', 'Template'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','options' => ['style' => ['width' => '60px']],],
            [
                'attribute' => 'name',
                'options' => ['style' => ['width' => '140px']],
                'class' => common\widgets\GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'sn',
                'options' => ['style' => ['width' => '70px']],
            ],
            [
                'attribute' => 'version',
                'options' => ['style' => ['width' => '180px']],
                'class' => common\widgets\GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ], 
            'path',
            'player',
            [
                'attribute' => 'sort_order',
                'options' => ['style' => ['width' => '70px']],
                'class' => common\widgets\GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ], 
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','options' => ['style' => ['width' => '70px']],],
        ],
    ]); ?>
</div>
