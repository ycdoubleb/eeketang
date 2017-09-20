<?php

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\searchs\CourseSearch;
use common\widgets\depdropdown\DepDropdown;
use common\widgets\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Course */

$this->title = Yii::t('app', '{Course}{List}', [
            'Course' => Yii::t('app', 'Course'),
            'List' => Yii::t('app', 'List'),
        ]);
?>
<div class="course-index">

    <p>
        <?=
        Html::a(Yii::t('app', '{Create}{Course}', [
                    'Create' => Yii::t('app', 'Create'),
                    'Course' => Yii::t('app', 'Course'),
                ]), ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['style' => ['width' => '60px']],
            ],
            [
                'attribute' => 'cat_id',
                'options' => ['style' => ['width' => '300px']],
                'value' => function($model) {
                    return $model->category->fullPath;
                },
                'filter' => DepDropdown::widget([
                        'model' => $searchModel,
                        'attribute' => 'cat_id',
                        'plugOptions' => [
                            'url' => Url::to('search-children', false),
                            'level' => 3,
                        ],
                        'items' => CourseCategory::getSameLevelCats($searchModel->cat_id,true),
                        'values' => $searchModel->cat_id == 0 ? [] : array_values(array_filter(explode(',', CourseCategory::getCatById($searchModel->cat_id)->parent_id_path))),
                        'itemOptions' => [
                            'style' => 'display:inline-block;',
                        ],
                    ]),
            ],
            [
                'attribute' => 'courseware_name',
                'options' => ['style' => ['width' => '130px']],
            ],
            [
                'attribute' => 'path',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '120px']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'keywords',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '120px']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            // 'img',
            // 'path',
            // 'learning_objectives:ntext',
            // 'introduction:ntext',
            // 'teacher_id',
            [
                'attribute' => 'is_recommend',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '80px']],
                'filter' => [Yii::t('app', 'No'), Yii::t('app', 'Yes')],
            ],
            [
                'attribute' => 'is_publish',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '80px']],
                'filter' => [Yii::t('app', 'No'), Yii::t('app', 'Yes')],
            ],
            // 'content:ntext',
            [
                'attribute' => 'course_order',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '40px']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '40px']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'play_count',
                'options' => ['style' => ['width' => '75px']],
            ],
            // 'zan_count',
            // 'favorites_count',
            // 'comment_count',
            // 'publish_time',
            // 'publisher',
            // 'keywords',
            // 'create_by',
            // 'created_at',
            // 'updated_at',
            // 'course_model_id',
            [
                'label' => Yii::t('app', 'Preview'),
                'options' => ['style' => ['width' => '50px']],
                'format' => 'raw',
                'value' => function ($model, $key) {
            return Html::a('<i class="glyphicon glyphicon-play-circle" style="font-size: 29px;"></i>', ['preview', 'id' => $model->id], ['target' => '_blank']);
        },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style' => ['width' => '70px']],
            ],
        ],
    ]);
    ?>
</div>