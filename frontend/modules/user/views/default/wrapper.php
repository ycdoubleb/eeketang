<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

//$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="content">

    <div class="wrapper">
        <div class="main">
            <?php foreach ($results as $month => $datas): ?>
            <div class="year">
                <h2><a href="#"><?= $month ?><i></i></a></h2>
                <div class="list">
                    <ul>
                        <?php foreach ($datas as $day => $items): ?>
                        <li class="cls highlight">
                            <p class="date"><?= $day ?></p>
                            <div class="more">
                                <div class="goods">
                                    <?php foreach ($items as $index => $coutse): ?>
                                    <div class="<?= ($index%3 == 2)?'goods-list none':'goods-list' ?>">
                                        <div class="goods-pic">
                                        </div>
                                        <div class="goods-name"><span><?= $coutse['cou_name'] ?></span></div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php
$js = <<<JS
    $(".main .year .list").each(function(e, target){
        var target=  $(target),
        ul = target.find("ul");
        target.height(ul.outerHeight()), ul.css("position", "absolute");
    }); 
    $(".main .year>h2>a").click(function(e){
            e.preventDefault();
            $(this).parents(".year").toggleClass("closed");
    });

JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
UserAsset::register($this);
?>