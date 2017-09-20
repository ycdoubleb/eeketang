<?php

use common\models\course\searchs\SubjectSearch;
use common\widgets\GridViewChangeSelfColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SubjectSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Subject');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', Yii::t(null, '{Create}{Subject}',[
                'Create' => Yii::t('app', 'Create'),
                'Subject' => Yii::t('app', 'Subject'),
            ])), ['create'], ['class' => 'btn btn-success']) ?>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'sort_order',
                'class' => GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ]
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
