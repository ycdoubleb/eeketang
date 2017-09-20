<?php

use common\models\Menu;
use frontend\assets\HomeAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $item Menu */

$this->title = Yii::t('app', 'My Yii Application');

?>
<?php if(false): ?>
<div class="site-index">
    
    <div class="banner">
        <div class="banner-background">
            <?php //echo Html::img(['/filedata/site/image/background.jpg'], ['class' => 'background-img']) ?>
        </div>
        <div class="banner-vertical">
            
            <?php foreach ($menus as $index => $item): ?>
            <div class="vertical <?= $index % 5 == 4 ? "vertical-background-{$item->id} none-margin" : "vertical-background-{$item->id}"?>"></div>
            <?php endforeach; ?>
            
        </div>
        <div class="banner-search">
            <div class="search-background">
                <div class="search-form">
                    <div class="search-input">
                        <?php $form = ActiveForm::begin([
                            'options'=>[
                                'id' => 'search-form',
                                'class'=>'form-horizontal',
                            ],
                            'action' => ['study/default/search'],
                            'method' => 'get'
                        ]) ?>
                        
                        <?= Html::textInput('keyword', null, ['class' => 'form-control', 'placeholder' => '请输入你想搜索的关键词']) ?>
                        
                        <?php ActiveForm::end(); ?>
                        
                    </div>
                    <a id="submit" onclick="submit();">
                        <div class="search-button">
                            <?= Html::img(['/filedata/site/image/search-icon.png']) ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content">
        <div class="row">
            
            <?php foreach ($menus as $index => $item): ?>
            <a href="/<?= $item->module.$item->link ?>">
            <div class="body-memu <?= $index % 5 == 4 ? "memu-background-{$item->id} none-margin" : "memu-background-{$item->id}"?>">
                <div class="memu-leave">
                    <div class="memu-leave-number">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>&nbsp;
                        <span><?= $totalCount[$item->id] <= 99999 ? number_format($totalCount[$item->relate_id]) : substr(number_format((($totalCount[$item->relate_id]/10000)*10)/10, 4), 0, -2).'万' ?></span>
                    </div>
                    <div class="memu-leave-icon">
                        <?= Html::img(["/filedata/site/memu/icon/memu-icon-{$item->id}.png"], ['class' => 'icon-big']) ?>
                    </div>
                    <div class="memu-leave-name">
                        <span><?= $item->name ?></span>
                    </div>
                </div>
                <div class="memu-hover <?= "hover-more-{$item->id}" ?>">
                    <div class="memu-hover-des">
                        <?= $item->des ?>
                    </div>
                    <div class="memu-hover-more">
                        <?= Html::img(["/filedata/site/memu/icon/memu-icon-{$item->id}.png"], ['class' => 'icon-small']) ?>
                        <span style="margin-left: 25px">进入学习</span>
                    </div>
                </div>
            </div>
            </a>
            <?php endforeach;?>
            
        </div>
    </div>
</div>
<?php endif; ?>
<?php
$js = <<<JS
    
    /** 单击提交表单 */
    window.submit = function(){
        var value = $("#search-form .form-control").val();
        //if(value == '')  return false; 
        $('#search-form').submit();
    }
    
    /** 回车提交表单 */
    $("#search-form .form-control").keydown(function(event) {  
         if (event.keyCode == 13) { 
            //if($(this).val() == '') return false; 
            $('#search-form').submit();
         }  
     }) 
        
    /** 鼠标经过换图标背景颜色 */      
    $('.body-content .row .body-memu').each(function(){
        $(this).hover(function(){
            $(this).animate({top: -22});
            $(this).children('.memu-hover').stop();
            $(this).children('.memu-leave').stop().fadeTo(200, 0, 'linear', function(){
                $(this).next('.memu-hover').fadeTo(200, 1);
            });            
        }, function(){
            $(this).animate({top: 0});
            $(this).children('.memu-leave').stop();
            $(this).children('.memu-hover').stop().fadeTo(200, 0, 'linear', function(){
                $(this).prev('.memu-leave').fadeTo(200, 1);
            });
        });
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    HomeAsset::register($this);
?>