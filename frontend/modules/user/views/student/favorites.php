<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="user-student-favorites user-default favorites">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止共收藏<em><?= count($favorites) ?></em>门课程</span></div>
        <ul class="subject-nav">
            <li>
                <?= Html::a('清除', 'javascript:;', ['class' => 'btn btn-primary btn-sm']) ?>
            </li>
        </ul>
    </div>
    
    <div class="goods">
        <?php if(count($favorites) <= 0): ?>
        <div class="error-404 error-404_4"></div>
        <?php endif; ?>
        <?php foreach ($favorites as $index => $item): ?>
        <div class="<?= $index%4 == 3?'goods-list none':'goods-list'?>">
            <div class="goods-pic" style="background-color:<?= Course::$backgroundColor[$item['course_id']%count(Course::$backgroundColor)] ?>">
                <a href="<?= Url::to(['/study/college/view', 'id' => $item['course_id']]) ?>" title="<?= $item['cou_name'] ?>">
                    <?= Html::img([$item['sub_img']]) ?>
                    <?= Html::img([$item['tea_img']], ['class' => 'course-teacher']) ?>
                    <?= Html::img(["/filedata/course/tm_logo/{$tm_logo[$item['tm_ver']]}.png"], ['class' => 'tm-ver-logo']) ?>
                    <div class="course-title">
                        <?= Course::$grade_keys[$item['grade']].$item['attr_values'].Course::$term_keys[$item['term']].$item['unit'] ?>
                    </div>
                    <div class="course-line-clamp course-lable"><?= $item['cou_name'] ?></div>
                    <?php if($item['is_study']): ?>
                    <i class="icon icon-7"></i>
                    <?php endif; ?>
                </a>
                <div class="goods-delete">
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', 'javascript:;', ['data-id' => $item['id']]) ?>
                </div>
            </div>
            <div class="goods-name course-name"><?= $item['cou_name'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>

<?php

$js = <<<JS
    /** 清除全部收藏的课程 */
    $(".favorites .subject-nav>li>a").click(function(){
        $.get("/user/student/delete-favorites", function(data){
            $("body").load("/user/student/favorites");
        });
    }); 
    /** 单个删除收藏的课程 */ 
    $(".favorites .goods-delete>a").click(function(){
        $.get("/user/student/delete-favorites?id="+$(this).attr("data-id"), function(data){
            $("body").load("/user/student/favorites");
        });
    }); 
    /** 显示和关闭删除图标 */
    $(".favorites .goods-pic").each(function(){
        $(this).hover(function(){
            $(this).children('.goods-delete').stop().animate({top: 0});            
        }, function(){
            $(this).children('.goods-delete').stop().animate({top: '-30px'});
        });
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>