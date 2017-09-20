<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\course\CourseCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $this->title = sprintf('%s%s', Yii::t('app', 'Course'), Yii::t('app', 'Category')), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-category-view">
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
            'mobile_name',
            'parent_id',
            'parent_id_path',
            'level',
            'is_hot',
            'is_show',
            'image',
            'sort_order',
        ],
    ]) ?>

</div>
