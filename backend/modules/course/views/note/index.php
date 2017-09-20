<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\NoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <p>
        <?= Html::a(Yii::t('app', Yii::t(null, '{Create}{Note}',[
                'Create' => Yii::t('app', 'Create'),
                'Note' => Yii::t('app', 'Note'),
            ])), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'course_id',
            'user_id',
            'content',
            [
                'attribute' => 'is_show',
                'class' => \common\widgets\GridViewChangeSelfColumn::className(),
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
