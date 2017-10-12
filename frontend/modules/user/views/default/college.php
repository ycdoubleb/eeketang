<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="user-default-college user-default college">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止已经有<em><?= $count ?></em>人加入学习</span></div>
    </div>
    
    <div class="goods">
        <?php foreach($cateJoins as $index => $item): ?>
        <div class="<?= $index%4 == 3?'goods-list none':'goods-list'?>">
            <div class="goods-pic">
                <span><?= $item['name'] ?></span>
                <?php if($is_join[$item['cate_id']]): ?>
                <i class="icon icon-8"></i>
                <?php endif; ?>
            </div>
            <div class="goods-name"><span>已有<em><?= $item['totalCount'] ?></em>人加入学院</span></div>
        </div>
        <?php endforeach; ?>
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