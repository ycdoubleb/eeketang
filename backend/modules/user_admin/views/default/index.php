<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider; */
/* @var $model common\models\User */

$this->title = '管理用户';
?>
<div class="user-index">
    <p>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'username',
            'nickname',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
