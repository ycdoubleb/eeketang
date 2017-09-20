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
//$prevLinks = Url::to(array_merge(['index'], array_merge($filter, ['page' => $pages->page])));
//$nextLinks = Url::to(array_merge(['index'], array_merge($filter, ['page' => $pages->page+2])));
?>

<div class="page">
    <div class="p-wrap">
        <div class="p-num">
            <?= LinkPager::widget([
                'pagination' => $pages,
                'options' => ['class' => 'pagination', 'style' => 'margin: 0px;border-radius: 0px;'],
                'prevPageCssClass' => 'pn-prev',
                'nextPageCssClass' => 'pn-next',
                'prevPageLabel' => '<i>&lt;</i>'.Yii::t('app', 'Prev Page'),
                'nextPageLabel' => Yii::t('app', 'Next Page').'<i>&gt;</i>',
                'maxButtonCount' => 5,
            ]); ?>
        </div>
        <div class="p-skip">
            <?php if($pages->pageCount >= 2): ?>
            共<b><?= $pages->pageCount; ?></b>页&nbsp;&nbsp;到第<?= Html::textInput('page', $pages->page+1, ['class' => 'input-txt']) ?>页
            <?= Html::a(Yii::t('app', 'Save'), "javascript:;", ['id' => 'submit', 'class' => 'btn btn-default']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
unset($filter['page']);
$url = Url::to(array_merge([Yii::$app->controller->action->id], array_merge($filter)));

$js = <<<JS
    $("#submit").click(function(){
        var pageValue = $(".input-txt").val();
        window.location.href="$url&page="+pageValue;
    });
    
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    LayoutsAsset::register($this);
?>