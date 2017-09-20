<?php

use backend\modules\course\assets\CourseAssets;
use common\models\course\searchs\CourseCategorySearch;
use common\widgets\GridViewChangeSelfColumn;
use common\widgets\treegrid\TreegridAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseCategorySearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Course}{Category}',[
                    'Course' => Yii::t('app', 'Course'),
                    'Category' => Yii::t('app', 'Category'),
                ]);
?>
<div class="course-category-index">

    <p>
        <?= Html::a(Yii::t(null, '{Create}{Course}{Category}',[
            'Create' => Yii::t('app', 'Create'),
            'Course' => Yii::t('app', 'Course'),
            'Category' => Yii::t('app', 'Category'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $this){
            /* @var $model CourseCategorySearch */
            return ['class'=>"treegrid-{$key}".($model->parent_id == 0 ? "" : " treegrid-parent-{$model->parent_id}")];
        },
        'columns' => [
            'id',
            'name',
            'mobile_name',
            [
                'attribute' => 'is_hot',
                'class' => GridViewChangeSelfColumn::className(),
                'filter' => ['否','是'],
            ],
            [
                'attribute' => 'is_show',
                'class' => GridViewChangeSelfColumn::className(),
                'filter' => ['否','是'],
            ],
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php 
    TreegridAssets::register($this);
    CourseAssets::register($this);
    
    $js = <<<JS
        $('.table').treegrid({
            //initialState: 'collapsed',
        });
JS;
    $this->registerJs($js, View::POS_READY);
?>
