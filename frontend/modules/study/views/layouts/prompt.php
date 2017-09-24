<?php

use frontend\modules\study\assets\SearchAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="study-default-prompt study-prompt">
    
    <div class="body-content">
        <div class="row">
        
            <div class="search-prompt sp-background">
                <div class="sp-result"><i class="icon icon-search"></i><span>搜索结果</span></div>
                <div class="sp-content">搜索“<?= $filter['keyword'] ?>”得出的结果如下：</div>
            </div>

            <div class="result-prompt">
                
                <div class="prompt-content">
                    <div class="pc-return"><?= Html::a(null, ['/site/index'], ['class' => 'pc-button index-button']) ?><span>选择感兴趣的板块</span></div>
                    <div class="pc-return"><?= Html::a(null, 'javascript:;', ['class' => 'pc-button prev-button', 'onclick'=> 'history.go(-1);']) ?><span>换个关键词搜索</span></div>
                </div>
                
            </div>
            
        </div>    
    </div>
    
</div>

<?php
$js = <<<JS

   $("body").addClass("_prompt");
        
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    SearchAsset::register($this);
?>