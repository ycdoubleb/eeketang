<?php

use common\models\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus Frontend'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            [
                'attribute' => 'parent_id',
                'value' => $model->parent_id == 0 ? '顶级菜单' : $model->parent->name,
            ],
            'relate_id',
            'name',
            'alias',
            'module',
            'link',
            'image',
            [
                'attribute' => 'is_show',
                'value' => $model->is_show == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
            ],
            [
                'attribute' => 'is_jump',
                'value' => $model->is_jump == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
            ],
            'level',
            [
                'attribute' => 'position',
                'value' => Menu::$positionName[$model->position],
            ],
            'sort_order',
            [
                'attribute' => 'des',
                'value' => $model->des,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
