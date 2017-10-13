<?php

use frontend\modules\study\assets\LayoutsAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

//$this->title = Yii::t('app', 'My Yii Application');
$defaultUrl = Url::to(array_merge([Yii::$app->controller->action->id], array_merge($filter, ['sort_order' => 'sort_order'])));
$mostUrl = Url::to(array_merge([Yii::$app->controller->action->id], array_merge($filter, ['sort_order' => 'play_count'])));
?>

<div class="filter">
    <?php if(Yii::$app->request->url != $mostUrl): ?>
    <div class="sort-order"><?= Html::a('默认', $defaultUrl, ['class' => 'active']) ?></div>
    <div class="sort-order"><?= Html::a('播放最多', $mostUrl) ?></div>
    <?php else: ?>
    <div class="sort-order"><?= Html::a('默认', $defaultUrl) ?></div>
    <div class="sort-order"><?= Html::a('播放最多', $mostUrl, ['class' => 'active']) ?></div>
    <?php endif; ?>
</div>

<?php
$js = <<<JS

   
        
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php 
    LayoutsAsset::register($this);
?>