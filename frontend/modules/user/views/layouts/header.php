<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

?>

<div class="user-header">
    <div class="container">
        <div class="avatars">
            <div class="avatars-circle">
                <div class="img-circle"></div>
            </div>
            <div class="data-info">
                <p>姓名：<span>何千千</span></p>
                <p>年级：<span>五年级</span></p>
                <p>班级：<span>12班</span></p>
                <?= Html::a('<i class="fa fa-cog" aria-hidden="true"></i>个人资料', '#', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="ranking">
            <div class="placing">
                <p><span class="number">15</span></p>
                <p><span class="words">名次</span><i id="example" data-toggle="tooltip" data-placement="right" title="该名次为全校排名。">？</i></p>
            </div>
            <div class="course-num">
                <p><span class="number">35</span></p>
                <p><span class="words">学习课程数</span></p>
            </div>
            <div class="first">
                <div class="img-circle"></div>
                <span>夺得了第<em>1</em>名</span>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $("#example").tooltip('show');
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>