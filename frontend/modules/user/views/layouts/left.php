<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

?>

<div class="user-left">
    <ul class="navigation">
        <li class="active">
            <a href="#"><i class="icon icon-1"></i>同步课堂</a>
        </li>
        <hr/>
        <li>
            <a href="#"><i class="icon icon-2"></i>学科培优</a>
        </li>
        <hr/>
        <li>
            <a href="#"><i class="icon icon-3"></i>素质提升</a>
        </li>
        <hr/>
        <li>
            <a href="#"><i class="icon icon-4"></i>学习轨迹</a>
        </li>
        <hr/>
        <li>
            <a href="#"><i class="icon icon-5"></i>我的收藏</a>
        </li>
    </ul>
</div>

<?php
$js = <<<JS
   
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>