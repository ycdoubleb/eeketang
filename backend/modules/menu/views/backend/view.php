<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu\models\MenuBackend */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Backends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-backend-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

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
            //'parent_id',
            [
                'attribute' => 'parent_id',
                'value' => $model->parent_id == 0 ? '顶级菜单' : $model->parent->name,
            ],
            'name',
            'alias',
            'link',
            'icon',
            //'is_show',
            [
                'attribute' => 'is_show',
                'value' => $model->is_show == 0 ? Yii::t('app', 'No') : Yii::t('app', 'Yes'),
            ],
            'level',
            'sort_order',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
