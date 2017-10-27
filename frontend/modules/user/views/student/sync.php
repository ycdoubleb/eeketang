<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');

?>

<div class="user-student-sync user-default sync">
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
<!--    <div class="page">
        <?php // Html::a('加载更多', 'javascript:;') ?>
    </div>-->
    <?php endforeach; ?>
    
    
</div>

<?php
$cates = json_encode(ArrayHelper::getColumn($category, 'id'));
$grade_keys = json_encode(Course::$grade_keys);
$term_keys = json_encode(Course::$term_keys);
$tm_logo = json_encode(Course::$tm_logo);
$b_color = json_encode(Course::$backgroundColor);
$goods_item = json_encode(str_replace(array("\r\n", "\r", "\n"),"",$this->renderFile('@frontend/modules/user/views/student/_sync_goods.php')));
$goods_note = json_encode(str_replace(array("\r\n", "\r", "\n"),"",$this->renderFile('@frontend/modules/user/views/student/_sync_note.php')));

$js = <<<JS
    var cate = $cates;    
    var grade_keys = $grade_keys;    
    var term_keys = $term_keys;
    var bcolor = $b_color;
    var tm_logos = $tm_logo;
    var goods_items = $goods_item;
    var goods_notes = $goods_note;
    /** 循环加载所有课程 */
    $.each(cate, function(i,n){
        var category = $("#category-"+n+" li");
        var htmlElem = category.first().children("a");          //获取第一li的子级a标签
        var htmlClick = $("#category-"+n+">li>a");              //获取对应li的子级a标签
        category.first().addClass("active");                    //给第一li标签加样式
        /** 页面刷新就加载数据 */
        $.get(htmlElem.attr('href'), function(data){
            $("#prompt-"+n+" span>b").text(htmlElem.text());
            $("#prompt-"+n+" span>em").eq(0).text(data['tot']);
            $("#prompt-"+n+" span>em").eq(1).text(data['stu']['num']);
            if(data['cou'].length > 0){
                /** 循环显示所有课程 */
                goodsitem(data['cou'], n);
            }else{
                var goods_item = '<div class="error-404 error-404_1"></div>';
                $(goods_item).appendTo($("#goods-"+n));
            }
            /** 鼠标经过离开显示或关闭笔记记录 */
            noteTooltip(n);
        });
        /** 单击后就加载数据 */
        htmlClick.click(function(e){
            var htmlText = $(this).text();
            e.preventDefault();
            $("#goods-"+n).html("");
            $.get($(this).attr('href'), function(data){
                $("#prompt-"+n+" span>b").text(htmlText);
                $("#prompt-"+n+" span>em").eq(0).text(data['tot']);
                $("#prompt-"+n+" span>em").eq(1).text(data['stu']['num']);
                if(data['cou'].length > 0){
                    /** 循环显示所有课程 */
                    goodsitem(data['cou'], n);
                }else{
                    var goods_item = '<div class="error-404 error-404_1"></div>';
                    $(goods_item).appendTo($("#goods-"+n));
                }
                /** 鼠标经过离开显示或关闭笔记记录 */
                noteTooltip(n);
            });
            $(this).parent("li").siblings().removeClass("active");
            $(this).parent("li").addClass("active");
        });
    });
    
    /** 循环显示所有课程 */
    function goodsitem (objArray, number) {
        $.each(objArray, function(index){
            var goods_item = renderDom(goods_items,{
                goods_id: this['id'],
                goods_list: (index%4==3?'goods-list none':'goods-list'),
                bcolor: bcolor[this['id']%bcolor.length],
                is_study: (this['is_study']==1?'icon-10':''),
                sub_img: this['sub_img'],
                tea_img: this['tea_img'],
                tm_logo: tm_logos[this['tm_ver']],
                grade: grade_keys[this['grade']],
                attr_values: (this['attr_values']!=null?this['attr_values']:""),
                term: term_keys[this['term']],
                unit: this['unit'],
                cou_name: this['cou_name']
            });
            $(goods_item).appendTo($("#goods-"+number));
        });
    }    
    /** 鼠标经过离开显示或关闭笔记记录 */
    function noteTooltip(number){
        var tooltip = $('<div />');
        $("#goods-"+number+" .goods-list").each(function(key){
            $(this).children(".goods-pic").hover(function(){
                var elem = $(this);
                var notesHtml = "快去看课程，做笔记吧！";
                var goods_id = elem.attr("goods_id");
                $.get("/study/api/get-course-studyinfo?course_id="+goods_id,function(data){
                    if(data['code'] == 200){
                        if(data['data']['note']['notes'].length > 0){
                            notesHtml = "";
                            $.each(data['data']['note']['notes'],function(){
                                notesHtml += "<li>"+this['content']+"</li>";
                            });
                        }
                        var goods_note = renderDom(goods_notes,{
                            positions: ($("#goods-"+number+" .goods-list").length - key <=4?"top":"bottom"),
                            last_time:data['data']['study_info']['last_time'],
                            study_time:data['data']['study_info']['study_time'],
                            max_scroe:data['data']['study_info']['max_scroe'],
                            max_count:data['data']['note']['max_count'],
                            notes_html:notesHtml
                        });

                        tooltip.html(goods_note);
                        tooltip.appendTo($(elem));
                    }
                });
            },function(){
                tooltip.html("");
            });
        });
    }     
        
JS;
   $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>
