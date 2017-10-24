<?php

use frontend\modules\study\assets\LayoutsAsset;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $pages Pagination */

//$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="page">
    <div class="page-wrap">
        <div class="page-num">
            <?= LinkPager::widget([
                'pagination' => $pages,
                'options' => ['class' => 'pagination', 'style' => 'margin: 0px;border-radius: 0px;'],
                'prevPageCssClass' => 'page-prev',
                'nextPageCssClass' => 'page-next',
                'prevPageLabel' => '<i>&lt;</i>'.Yii::t('app', 'Prev Page'),
                'nextPageLabel' => Yii::t('app', 'Next Page').'<i>&gt;</i>',
                'maxButtonCount' => 5,
            ]); ?>
        </div>
        <div class="page-skip">
            <?php if($pages->pageCount >= 2): ?>
            共<b><?= $pages->pageCount; ?></b>页&nbsp;&nbsp;到第<?= Html::textInput('page', $pages->page+1, ['class' => 'input-txt']) ?>页
            <?= Html::a(Yii::t('app', 'Sure'), "javascript:;", ['id' => 'submit-page', 'class' => 'btn btn-default']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$is_scroll = isset($filter['page']) ? 1 : 0;
unset($filter['page']);
$url = Url::to(array_merge([Yii::$app->controller->action->id], array_merge($filter)));
$js = <<<JS
    $("#submit-page").click(function(){
        var pageValue = $(".input-txt").val();
        window.location.href="$url&page="+pageValue+"#scroll";
    });
    if($is_scroll)
        window.location.href= "#scroll";
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    LayoutsAsset::register($this);
?>