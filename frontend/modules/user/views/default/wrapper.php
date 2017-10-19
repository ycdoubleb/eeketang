<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\helpers\Url;
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
                                        <a href="<?= Url::to(['/study/default/view', 'id' => $coutse['id']]) ?>" title="<?= $coutse['cou_name'] ?>">
                                        <div class="goods-pic" style="background-color:<?= Course::$backgroundColor[$coutse['id']%count(Course::$backgroundColor)] ?>">
                                            <?= Html::img([$coutse['sub_img']]) ?>
                                            <?= Html::img([$coutse['tea_img']], ['class' => 'course-teacher']) ?>
                                            <?= Html::img(["/filedata/course/tm_logo/{$tm_logo[$coutse['tm_ver']]}.png"], ['class' => 'tm-ver-logo']) ?>
                                            <div class="course-title">
                                                <?= Course::$grade_keys[$coutse['grade']].Course::$term_keys[$coutse['term']].$coutse['unit'] ?>
                                            </div>
                                            <div class="course-line-clamp course-lable"><?= $coutse['cou_name'] ?></div>
                                        </div>
                                        </a>
                                        <div class="goods-name course-name"><?= $coutse['cou_name'] ?></div>
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