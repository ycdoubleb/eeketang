<?php

use common\models\Menu;
use common\models\searchs\MenuSearch;
use common\widgets\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MenuSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Menus Frontend');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <p>
        <?= Html::a(Yii::t(null, '{Create}{Menus}', ['Create' => Yii::t('app', 'Create'), 'Menus' => Yii::t('app', 'Menus')]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'options' => ['style' => ['width' => '30px']],
            ],
            [
                'attribute' => 'parent_id',
                'options' => ['style' => ['width' => '110px']],
                'value' => function($model) {
                    /* @var $model Menu */
                    return $model->parent_id == 0 ? '顶级菜单' : $model->parent->name;
                },
                'filter' => Menu::getCats(['level' => 1]),
            ],
            [
                'attribute' => 'relate_id',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '40px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'name',
                'options' => ['style' => ['width' => '140px']],
            ],
            [
                'attribute' => 'alias',
                'options' => ['style' => ['width' => '80px']],
            ],
            [
                'attribute' => 'module',
                'options' => ['style' => ['width' => '80px']],
            ],
            [
                'attribute' => 'link',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '170px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'image',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '170px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            [
                'attribute' => 'is_show',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '60px']],
                'filter' => [Yii::t('app', 'No'),  Yii::t('app', 'Yes')],
            ],
            [
                'attribute' => 'is_jump',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '60px']],
                'filter' => [Yii::t('app', 'No'),  Yii::t('app', 'Yes')],
            ],
            [
                'attribute' => 'position',
                'filter' => Menu::$positionName,
                'options' => ['style' => ['width' => '70px']],
                'value' => function($model){
                    /* @var $model Menu */
                    return Menu::$positionName[$model->position];
                }
            ],
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '40px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],            
            [
                'class' => 'yii\grid\ActionColumn', 
                'options' => ['style' => ['width' => '70px']],
            ],
        ],
    ]); ?>
</div>
