<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\menu\models\MenuBackend;
use common\widgets\GridViewChangeSelfColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\menu\models\searchs\BackendMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menu Backends');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-backend-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Menu Backend'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'id',
                'options' => ['style' => ['width' => '30px']],
            ],
            // 'parent_id',
            [
                'attribute' => 'parent_id',
                'options' => ['style' => ['width' => '120px']],
                'value' => function($model){
                    return $model->parent_id == 0 ? '顶级菜单' : $model->parent->name;
                },
                'filter' => MenuBackend::getCats(['level' => 1]),
            ],
            //'name',
            [
                'attribute' => 'name',
                'options' => ['style' => ['width' => '120px']],
            ],
            //'alias',            
            [
                'attribute' => 'alias',
                'options' => ['style' => ['width' => '80px']],
            ],
            //'link',
            [
                'attribute' => 'link',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '170px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            //'icon',
            [
                'attribute' => 'icon',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '170px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            //'is_show',
            [
                'attribute' => 'is_show',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '70px']],
                'filter' => [Yii::t('app', 'No'), Yii::t('app','Yes')],
            ],            
            //'level',
            //'sort_order',
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'options' => ['style' => ['width' => '50px']],
                'contentOptions' => ['style' => ['padding-top' => '0px', 'padding-bottom' => '0px;']],
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],          
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70px'],
            ],
        ],
    ]); ?>
</div>
