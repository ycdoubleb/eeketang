<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="user-default-index user-default">
    
    <div class="category">
        <div class="cat-name"><span>广州特色资源（4055）</span></div>
        <ul class="subject-nav">
            <li class="active">
                <a href="#">语文</a>
            </li>
            <li>
                <a href="#">数学</a>
            </li>
            <li>
                <a href="#">英语</a>
            </li>
            <li>
                <a href="#">地理</a>
            </li>
            <li>
                <a href="#">化学</a>
            </li>
            <li>
                <a href="#">政治</a>
            </li>
            <li>
                <a href="#">生物</a>
            </li>
            <li>
                <a href="#">物理</a>
            </li>
        </ul>
    </div>
    <div class="prompt"><span>语文本学期课程推荐<em>19</em>门课，已完成<em>5</em>门</span></div>
    <div class="goods">
        <div class="goods-list">
            <div class="goods-pic">
                <i class="icon icon-7"></i>
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list none">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list none">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
        <div class="goods-list none">
            <div class="goods-pic">
            </div>
            <div class="goods-name"><span>摆火柴</span></div>
        </div>
    </div>
    
</div>

<?php

$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>