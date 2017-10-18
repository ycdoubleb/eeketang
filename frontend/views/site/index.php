<?php

use common\models\course\Course;
use common\models\Menu;
use frontend\assets\CollegeAsset;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $item Menu */

$this->title = Yii::t('app', 'My Yii Application');
?>
<?php if (TRUE): ?>

    <div class="site-index">

        <div class="wrap">
            <div class="body-content has-title">
                <div class="banner">
                    <div class="banner-inner">
                        <img src="/filedata/site/image/banner_1_1.jpg" alt="" name="0" width="" height="">
                        <img src="/filedata/site/image/banner_1_2.jpg" alt="" name="1" width="" height="">
                    </div>
                    <div class="ctrlbtn">
                        <a href="javascript:;" class="prev" style="display: none">></a>
                        <a href="javascript:;" class="next" style="display: none"><</a>
                    </div>
                    <ul class="pageCtrl">
                        <li class="active"></li>
                        <li></li>
                    </ul>
                </div>
                <!--资源-->
                <div class="system">
                    <div class="container">
                        <div class="tablist">
                            <ul>
                                <li class="active">
                                    <a href="#sync" onclick="tabClick($(this));return false;">
                                        <i class="sync"></i><em>同步课堂</em>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#study" onclick="tabClick($(this));return false;">
                                        <i class="study"></i><em>学科培优</em>
                                    </a>
                                </li>
                                <li class="none">
                                    <a href="#diathetic" onclick="tabClick($(this));return false;">
                                        <i class="diathetic"></i><em>素质提升</em>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabcontent">
                            <div id="sync" class="tabpane show">
                                <div class="resource">
                                    <img src="/filedata/site/image/image_1_1.jpg" width="100%">
                                    <?= Html::a('<div class="elem-hover">' . Html::img(['/filedata/site/image/icon_8_1.png']) . '</div>', ['/study/default/index', 'par_id' => 4]) ?>
                                </div>
                                <div class="resource">
                                    <img src="/filedata/site/image/image_1_2.jpg" width="100%">
                                    <a href="#">  
                                        <div class="elem-hover">
                                            <img src="/filedata/site/image/icon_8_1.png">
                                        </div>
                                    </a>  
                                </div>
                                <div class="resource none">
                                    <img src="/filedata/site/image/image_1_3.jpg" width="100%">
                                    <a href="#">
                                        <div class="elem-hover">
                                            <img src="/filedata/site/image/icon_8_1.png">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div id="study" class="tabpane">
                                <div class="resource">
                                    <img src="/filedata/site/image/image_2_1.png" width="100%">
                                    <a href="http://tt.k12.gzedu.net/study/default/index?parent_cat_id=4&cat_id=28&page=1#scroll">
                                        <div class="elem-hover">
                                            <img src="/filedata/site/image/icon_8_1.png">
                                        </div>
                                    </a>
                                </div>
                                <div class="resource">
                                    <img src="/filedata/site/image/image_2_2.png" width="100%">
                                    <a href="http://tt.k12.gzedu.net/study/default/index?parent_cat_id=4&page=1&cat_id=27#scroll">
                                        <div class="elem-hover">
                                            <img src="/filedata/site/image/icon_8_1.png">
                                        </div>
                                    </a>
                                </div>
                                <div class="resource none">
                                    <img src="/filedata/site/image/image_2_3.jpg" width="100%">
                                    <a href="http://tt.k12.gzedu.net/study/default/index?parent_cat_id=4&page=1&cat_id=29#scroll">
                                        <div class="elem-hover">
                                            <img src="/filedata/site/image/icon_8_1.png">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div id="diathetic" class="tabpane" style="position: relative">
                                <div class="navlist">
                                    <ul>
                                        <li class="active">
                                            <a href="#diat-1" onclick="tabClick($(this));return false;"></a>
                                        </li>
                                        <li class="">
                                            <a href="#diat-2" onclick="tabClick($(this));return false;"></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tabcontent">
                                    <div id="diat-1" class="tabpane show">
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_1.jpg" width="100%">
                                            <a href="http://tt.k12.gzedu.net/study/default/index?parent_cat_id=5&cat_id=30&page=1#scroll">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_2.jpg" width="100%">
                                            <a href="http://tt.k12.gzedu.net/study/default/index?parent_cat_id=5&page=1&cat_id=31#scroll">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_3.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat none">
                                            <img src="/filedata/site/image/image_3_4.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="diat-2" class="tabpane">
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_5.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_6.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat">
                                            <img src="/filedata/site/image/image_3_7.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="resource diat none">
                                            <img src="/filedata/site/image/image_3_8.jpg" width="100%">
                                            <a href="#">
                                                <div class="elem-hover">
                                                    <img src="/filedata/site/image/icon_8_1.png">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--最新-->   
                <div class="newest">
                    <div class="container">
                        <div class="live-broadcast">
                            <div class="nb-title">
                                <span class="title"><i></i>最新直播</span>
                                <span class="more"><a href="#">更多</a></span>
                            </div>
                            <div class="nb-content">
                                <ul>
                                    <li class="active">
                                        <div class="time">
                                            <span><i></i><hr><span><em>今天&nbsp;15:00</em></span><br/>
                                                <a href="javascript:;">已预约</a>
                                        </div>
                                        <div class="imgInfo">
                                            <img src="/filedata/site/image/image_4_1.jpg"  width="100%"/>
                                        </div>
                                        <div class="words">
                                            <h4>两节课带你搞定初中数学</h4>
                                            <p>革命性的数学学习方式，数学公式不再枯燥、图像不再坑D、函数不再难搞！游戏背后的数学你懂吗？</p>
                                            <p>已报名<span>40</span>人</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="time">
                                            <span><i></i><hr><span><em>今天&nbsp;18:00</em></span><br/>
                                                <a href="javascript:;">预约</a>
                                        </div>
                                        <div class="imgInfo">
                                            <img src="/filedata/site/image/image_4_2.jpg"  width="100%"/>
                                        </div>
                                        <div class="words">
                                            <h4>两节课带你搞定初中数学</h4>
                                            <p>革命性的数学学习方式，数学公式不再枯燥、图像不再坑D、函数不再难搞！游戏背后的数学你懂吗？</p>
                                            <p>已报名<span>40</span>人</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="time">
                                            <span><i></i><hr><span><em>今天&nbsp;20:00</em></span><br/>
                                                <a href="javascript:;">预约</a>
                                        </div>
                                        <div class="imgInfo">
                                            <img src="/filedata/site/image/image_4_3.jpg"  width="100%"/>
                                        </div>
                                        <div class="words">
                                            <h4>两节课带你搞定初中数学</h4>
                                            <p>革命性的数学学习方式，数学公式不再枯燥、图像不再坑D、函数不再难搞！游戏背后的数学你懂吗？</p>
                                            <p>已报名<span>40</span>人</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="recorded-broadcast">
                            <div class="nb-title">
                                <span class="title"><i></i>最新录播</span>
                                <span class="more"><a href="#">更多</a></span>
                            </div>
                            <div class="nb-content">
                                <div class="imgInfo">
                                    <img src="/filedata/site/image/image_5_1.jpg"  width="100%"/>
                                    <div class="elem-hover">
                                        <p><i class="glyphicon glyphicon-time"></i>&nbsp;3课时&nbsp;&nbsp;<i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;5.6万次学习</p>
                                        <p><a href="javascript:;"></a></p>
                                    </div>
                                </div>
                                <div class="words">
                                    <span>小学</span><h4>小学汉语拼音复习课</h4>
                                    <p>主讲：黄曼君</p>
                                    <p>汉语拼音是拼写汉民族标准语的拼音方案，它以北京语音系统作为语音标准的，包括a，o，e，i，u，ü……</p>
                                </div>
                                <div class="imgInfo">
                                    <img src="/filedata/site/image/image_5_2.jpg"  width="100%"/>
                                    <div class="elem-hover">
                                        <p><i class="glyphicon glyphicon-time"></i>&nbsp;3课时&nbsp;&nbsp;<i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;5.6万次学习</p>
                                        <p><a href="javascript:;"></a></p>
                                    </div>
                                </div>
                                <div class="words">
                                    <span>小学</span><h4>小学汉语拼音复习课</h4>
                                    <p>主讲：黄曼君</p>
                                    <p>汉语拼音是拼写汉民族标准语的拼音方案，它以北京语音系统作为语音标准的，包括a，o，e，i，u，ü……</p>
                                </div>
                                <div class="imgInfo">
                                    <<img src="/filedata/site/image/image_5_3.jpg"  width="100%"/>
                                    <div class="elem-hover">
                                        <p><i class="glyphicon glyphicon-time"></i>&nbsp;3课时&nbsp;&nbsp;<i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;5.6万次学习</p>
                                        <p><a href="javascript:;"></a></p>
                                    </div>
                                </div>
                                <div class="words">
                                    <span>小学</span><h4>小学汉语拼音复习课</h4>
                                    <p>主讲：黄曼君</p>
                                    <p>汉语拼音是拼写汉民族标准语的拼音方案，它以北京语音系统作为语音标准的，包括a，o，e，i，u，ü……</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--排行-->
                <div class="rankboard">
                    <div class="container">
                        <div class="tablist">
                            <ul>
                                <li class="active">
                                    <a href="#total" onclick="tabClick($(this));return false;">
                                        <i class="total"></i><em>总排行</em>
                                    </a>
                                </li>
                                <li class="none">
                                    <a href="#week" onclick="tabClick($(this));return false;">
                                        <i class="week"></i><em>周排行</em>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabcontent">
                            <div id="total" class="tabpane show">
                                <?php foreach ($totalQuery as $index => $item): ?>
                                    <div class="resource">
                                        <div class="imgInfo goods"> 
                                            <i class="first"></i>
                                            <a href="<?= Url::to(['/study/default/view', 'id' => $item['course_id']]) ?>" title="<?= $item['cour_name'] ?>">
                                                <div class="<?= $index % 4 == 3 ? 'goods-list none' : 'goods-list' ?>">
                                                    <div class="goods-pic" style="background-color:<?= Course::$backgroundColor[$item['course_id'] % count(Course::$backgroundColor)] ?>">
                                                        <div style="height:115px"><?= Html::img([$item['sub_img']]) ?></div>
                                                        <?= Html::img([$item['tea_img']], ['class' => 'course-teacher']) ?>
                                                        <?= Html::img(["/filedata/course/tm_logo/{$tm_logo[$item['tm_ver']]}.png"], ['class' => 'tm-ver-logo']) ?>
                                                        <div class="course-title">
                                                            <?= Course::$grade_keys[$item['grade']] . Course::$term_keys[$item['term']] . $item['unit'] ?>
                                                        </div>
                                                        <div class="course-lable"><?= $item['cour_name'] ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="words">
                                            <h4 class="cour-name"><?= $item['cour_name'] ?></h4>
                                            <p><img src="/filedata/site/image/icon_9_1.png" width="71" height="23">&nbsp;&nbsp;次播放</p>
                                            <p>来自：<?= $item['cate_name'] ?></p>
                                            <hr>
                                        </div>
                                        <div class="avatar">
                                            <div class="avatar-img-circle">
                                                <?php foreach ($manNum as $key => $value): ?>
                                                    <?php if ($key >= 7) break; ?>
                                                    <a href="javastrap:;" title="<?= $value['real_name'] ?>">
                                                        <li class="img-circle"><?= Html::img([$value['avatar']], ['class' => 'img-circle', 'style' => 'margin: -1px 0 0 -1px;']) ?></li>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="avatar-words">
                                                <p>共&nbsp;<span><?= count($manNum) ?></span>&nbsp;个人播放过</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div id="week" class="tabpane">
                                <?php foreach ($weekQuery as $index => $item): ?>
                                    <div class="resource">
                                        <div class="imgInfo goods"> 
                                            <i class="first"></i>
                                            <a href="<?= Url::to(['/study/default/view', 'id' => $item['course_id']]) ?>" title="<?= $item['cour_name'] ?>">
                                                <div class="<?= $index % 4 == 3 ? 'goods-list none' : 'goods-list' ?>">
                                                    <div class="goods-pic" style="background-color:<?= Course::$backgroundColor[$item['course_id'] % count(Course::$backgroundColor)] ?>">
                                                        <div style="height:115px"><?= Html::img([$item['sub_img']]) ?></div>
                                                        <?= Html::img([$item['tea_img']], ['class' => 'course-teacher']) ?>
                                                        <?= Html::img(["/filedata/course/tm_logo/{$tm_logo[$item['tm_ver']]}.png"], ['class' => 'tm-ver-logo']) ?>
                                                        <div class="course-title">
                                                            <?= Course::$grade_keys[$item['grade']] . Course::$term_keys[$item['term']] . $item['unit'] ?>
                                                        </div>
                                                        <div class="course-lable"><?= $item['cour_name'] ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="words">
                                            <h4><?= $item['cour_name'] ?></h4>
                                            <p><img src="/filedata/site/image/icon_9_1.png" width="71" height="23">&nbsp;&nbsp;次播放</p>
                                            <p>来自：<?= $item['cate_name'] ?></p>
                                            <hr>
                                        </div>
                                        <div class="avatar">
                                            <div class="avatar-img-circle">
                                                <?php foreach ($manNum as $key => $value): ?>
                                                    <?php if ($key >= 7) break; ?>
                                                    <a href="javastrap:;" title="<?= $value['real_name'] ?>">
                                                        <li class="img-circle"><?= Html::img([$value['avatar']], ['class' => 'img-circle', 'style' => 'margin: -1px 0 0 -1px;']) ?></li>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="avatar-words">
                                                <p>共&nbsp;<span><?= count($manNum) ?></span>&nbsp;个人播放过</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$js = <<<JS
    //轮播
    $(".banner").myCarousel({
        figureTime: 3000, //切换时间
        loopTime: 1000, //运动切换的时间
        figureBtnAct: "active", //切换按钮li样式    
        clickNode: $(".pageCtrl li"), //切换按钮li
        loopNode: $(".banner-inner"), // 轮播box
        loopCircle: "-100%", //轮播运动切换的距离
        sameNode: $(".banner-inner img"), // 轮播节点  
        attrData: "name", //自定义参数 
        leftBtn: $(".banner .prev"), // 左右按钮 
        rightBtn: $(".banner .next") //左右按钮
    });

    //单击切换标签
    window.tabClick = function (elem) {
        $(elem).parent().siblings("li").removeClass("active");
        $(elem).parent("li").addClass("active");
        var idName = $(elem).attr("href");
        $(idName).siblings("div").animate({opacity: 0}, 300).removeClass("show");
        $(idName).animate({opacity: 1}, 250).addClass("show");
    };

    /** 鼠标经过换图标背景颜色 */
    window.mouseOver = function (elem) {
        $(elem).each(function () {
            $(this).hover(function () {
                $(this).children('.elem-hover').stop().fadeTo(200, 1, 'linear');
            }, function () {
                $(this).children('.elem-hover').stop().fadeTo(200, 0, 'linear');
            });
        });
    };

    mouseOver(".recorded-broadcast .nb-content .imgInfo");
    mouseOver(".system .tabcontent .resource>a");
JS;
$this->registerJs($js, View::POS_READY);
?>


<?php
CollegeAsset::register($this);
UserAsset::register($this);
?>