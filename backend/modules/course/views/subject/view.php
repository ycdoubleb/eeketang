<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\course\Subject */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Subject}', [
        'Subject' => Yii::t('app', 'Subject'),
    ]), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-view">

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
            'name',
            'sort_order',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
