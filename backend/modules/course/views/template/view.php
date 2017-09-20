<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseTemplate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Course} {Templates}',[
    'Course' => Yii::t('app', 'Course'),
    'Templates' => Yii::t('app', 'Templates'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-template-view">

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
            'sn',
            'version',
            'path',
            'player',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
