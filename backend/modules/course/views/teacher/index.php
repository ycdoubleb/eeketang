<?php

use common\models\searchs\TeacherSearch;
use common\models\Teacher;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel TeacherSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Teachers}', [
            'Teachers' => Yii::t('app', 'Teachers'),
        ]);
?>
<div class="teacher-index">

    <p>
        <?= Html::a(Yii::t(null, '{Create}{Teachers} ', [
            'Create' => Yii::t('app', 'Create'),
            'Teachers' => Yii::t('app', 'Teachers'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'sex',
                'value' => function($model){
                    return Teacher::$sex_keys[$model->sex];
                },
                'filter' => Teacher::$sex_keys,
            ],
            'job_title',
            [
                'attribute' => 'school',
                'format' => 'raw',
                'options' => ['style'=>['width' => '400px']],
                'value' => function ($_model){
                    return Html::tag('span', $_model->school, ['style'=>'display: block;text-overflow: ellipsis;white-space: nowrap; overflow: hidden;']);
                },
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
