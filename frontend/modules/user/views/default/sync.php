<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

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
        
    </div>
    <div class="page">
        <?= Html::a('加载更多', 'javascript:;') ?>
    </div>
    <?php endforeach; ?>
    
    
</div>

<?php

$cates = json_encode(ArrayHelper::getColumn($category, 'id'));
$grade_keys = json_encode(Course::$grade_keys);
$term_keys = json_encode(Course::$term_keys);
$b_color = json_encode(Course::$backgroundColor);
$js = <<<JS
    var cate = $cates;    
    var grade_keys = $grade_keys;    
    var term_keys = $term_keys;
    var bcolor = $b_color;
    var tm_logos = {"人教版":"01","广州版":'02',"牛津上海版":"03","粤教版":"04"};
    var goods_item = '<a href="/study/default/view?id={%goods_id%}">'+
        '<div class="{%goods_list%}">'+
            '<div class="goods-pic" style="background-color:{%bcolor%}">'+
                '<i class="icon {%is_study%}"></i>'+
                '<img src="{%sub_img%}">'+
                '<img  class="course-teacher" src="{%tea_img%}">'+
                '<img  class="tm-ver-logo" src="/filedata/course/tm_logo/{%tm_logo%}.png">'+
                '<div class="course-title">{%grade%}{%term%}{%unit%}</div>'+
                '<div class="course-line-clamp course-lable">{%cou_name%}</div>'+
            '</div>'+
        '</div>'+
        '</a>';
    
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
                var html = renderDom(goods_item,{
                    goods_id: this['id'],
                    goods_list: (index%4==3?'goods-list none':'goods-list'),
                    bcolor: bcolor[this['id']%bcolor.length],
                    is_study: (this['study']==1?'icon-7':''),
                    sub_img: this['sub_img'],
                    tea_img: this['tea_img'],
                    tm_logo: tm_logos[this['tm_ver']],
                    grade: grade_keys[this['grade']],
                    term: term_keys[this['term']],
                    unit: this['unit'],
                    cou_name: this['cou_name']
                });
                $(html).appendTo($("#goods-"+n));
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
                $.each(data['cou'], function(index){
                    var html = renderDom(goods_item,{
                        goods_id: this['id'],
                        goods_list: (index%4==3?'goods-list none':'goods-list'),
                        bcolor: bcolor[this['id']%bcolor.length],
                        is_study: (this['study']==1?'icon-7':''),
                        sub_img: this['sub_img'],
                        tea_img: this['tea_img'],
                        tm_logo: tm_logos[this['tm_ver']],
                        grade: grade_keys[this['grade']],
                        term: term_keys[this['term']],
                        unit: this['unit'],
                        cou_name: this['cou_name']
                    });
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
