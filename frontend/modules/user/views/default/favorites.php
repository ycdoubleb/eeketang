<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="user-default-sync user-default favorites">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止共收藏<em><?= count($favorites) ?></em>门课程</span></div>
        <ul class="subject-nav">
            <li>
                <?= Html::a('清除', 'javascript:;', ['class' => 'btn btn-primary btn-sm']) ?>
            </li>
        </ul>
    </div>
    
    <div class="goods">
        <?php foreach ($favorites as $index => $item): ?>
        <div class="<?= $index%4 == 3?'goods-list none':'goods-list'?>">
            <div class="goods-pic">
                <span><?= $item['cou_name'] ?></span>
                <?php if($item['is_study']): ?>
                <i class="icon icon-7"></i>
                <?php endif; ?>
                <div class="goods-name">
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', 'javascript:;', ['data-id' => $item['id']]) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>

<?php

$js = <<<JS
       
    $(".favorites .subject-nav>li>a").click(function(){
        $.get("/user/default/delete", function(data){
            $("body").load("/user/default/favorites");
        });
    }); 
        
    $(".favorites .goods-name>a").click(function(){
        $.get("/user/default/delete?id="+$(this).attr("data-id"), function(data){
            $("body").load("/user/default/favorites");
        });
    }); 
        
    $(".favorites .goods-pic").each(function(){
        $(this).hover(function(){
            $(this).children('.goods-name').stop().animate({bottom: 0});            
        }, function(){
            $(this).children('.goods-name').stop().animate({bottom: '-30px'});
        });
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>