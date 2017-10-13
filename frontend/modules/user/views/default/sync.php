<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');

?>

<div class="user-default-sync user-default sync">
    <?php foreach($category as $cate): ?>
    <div class="category">
        <div class="cat-name"><span><?="{$cate['name']} （{$cate['total']}）" ?></span></div>
        <ul id="category-<?= $cate['id'] ?>" class="subject-nav">
            <?php foreach ($subject as $sub): ?>
            <li>
                <?= Html::a($sub['name'], array_merge(['sync'], array_merge($filter, ['psub_id' => $cate['id'].'_'.$sub['id']]))) ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div id="prompt-<?= $cate['id'] ?>" class="prompt">
        <span><b></b>本学期课程推荐<em></em>门课，已学过<em></em>门</span>
    </div>
    
    <div id="goods-<?= $cate['id'] ?>" class="goods"> 
        <div class="goods-list">
        <div class="goods-pic">
            <img  class="course_elem" src="/filedata/course/subject_element/aoshu.png">
            <img  class="course-teacher" src="/filedata/course/teacher_avatar/anran.png">
            <img  class="tm_ver_logo" src="/filedata/course/tm_logo/01.png"> 
            <div class="course-title">一年级上册第一单元</div>
            <div class="course-lable">认识汉字认识汉字</div>
        </div>
        </div>
    </div>
    <div class="page">
        <?= Html::a('加载更多', 'javascript:;') ?>
    </div>
    <?php endforeach; ?>
    
    
</div>

<?php

$cates = json_encode(ArrayHelper::getColumn($category, 'id'));

$js = <<<JS
        
    var cate = $cates;
    $.each(cate, function(i,n){
        var category = $("#category-"+n+" li");
        var htmlElem = category.first().children("a");          //获取第一li的子级a标签
        var htmlClick = $("#category-"+n+">li>a");              //获取对应li的子级a标签
        category.first().addClass("active");                    //给第一li标签加样式
        /** 页面刷新就加载数据 */
        $.get(htmlElem.attr('href'), function(data){
            $("#prompt-"+n+" span>b").text(htmlElem.text());
            $("#prompt-"+n+" span>em").eq(0).text(data['tot']);
            $("#prompt-"+n+" span>em").eq(1).text(data['stu'][0]['num']);
            $.each(data['cou'], function(index){
                var html = '<div class="'+(index%4==3?'goods-list none':'goods-list')+'"><div class="goods-pic">'+(this['study']==1?'<i class="icon icon-7"></i>':'')+'</div><div class="goods-name course-name"><span>'+this['cou_name']+'</span></div></div>';    
//                $(html).appendTo($("#goods-"+n));
            });
        });
        /** 单击后就加载数据 */
        htmlClick.click(function(e){
            var htmlText = $(this).text();
            e.preventDefault();
            $("#goods-"+n).html("");
            $.get($(this).attr('href'), function(data){
                $("#prompt-"+n+" span>b").text(htmlText);
                $("#prompt-"+n+" span>em").eq(0).text(data['tot']);
                $("#prompt-"+n+" span>em").eq(1).text(data['stu'][0]['num']);
                $.each(data['cou'], function(i){
                    var html = '<div class="'+(i%4==3?'goods-list none':'goods-list')+'"><div class="goods-pic">'+(this['study']==1?'<i class="icon icon-7"></i>':'')+'</div><div class="goods-name course-name"><span>'+this['cou_name']+'</span></div></div>';    
                    $(html).appendTo($("#goods-"+n));
                });
            });
            $(this).parent("li").siblings().removeClass("active");
            $(this).parent("li").addClass("active");
        });
    });
    
JS;
   $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>