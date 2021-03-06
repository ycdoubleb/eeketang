<?php

use common\models\course\Course;
use common\models\Favorites;
use common\models\Menu;
use common\widgets\players\PlayerAssets;
use frontend\modules\study\assets\StudyAsset;
use wskeee\utils\DateUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $menu Menu */
/* @var $model Course */

$this->title = $model->courseware_name;
$coursePlath = $model->path;

?>

<div class="study-choice-view _view has-title">
    <div class="container">
        <div class="body-content">
            <div class="crumbs-bar">
                <div class="cb-nav">
                    <div class="cbn-icon"><i class="icon icon-book"></i></div>
                    <div class="cbn-item"><span class="position">所在位置：</span></div>
                    <div class="cbn-item"><?= Html::a('首页', ['/site/index']) ?><i>&gt;</i></div>
                    <div class="cbn-item"><?= Html::a($model->category->parent->name, ['index', 'par_id' => $model->category->parent_id]) ?><i>&gt;</i></div>
                    <div class="cbn-item"><span><?= Html::a($model->category->name, ['index', 'par_id' => $model->category->parent_id, 'cat_id' => $model->cat_id]) ?></span><i style="color: #8a8a8a;">&gt;</i></div>
                    <?php foreach ($attrs as $attr_value): ?>
                        <div class="cbn-item"><span><?= Html::encode($attr_value['value']) ?></span><i style="color: #8a8a8a;">&gt;</i></div>
                    <?php endforeach; ?>
                    <div class="cbn-item"><span><?= Html::encode("【{$model->unit}】{$this->title}") ?></span></div>

                    <!--学习时长部分-->
                    <div class="nav-timer dropdown">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <label>今日学习时长:</label>
                        <div class="timer-content">
                            <?php $form = ActiveForm::begin([
                                'id' => 'timer-form'
                            ]); ?>
                                <?= Html::input('text','StudyLog[studytime]', DateUtil::intToTime($studytime) ? DateUtil::intToTime($studytime) : '00:00:00', ['id' => 'timer','disabled' => 'disabled'] )?>
                            <?php ActiveForm::end() ?>
                        </div>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                        <div class="dropdown-content">
                            <a href="#" class="total">总共学习次数：<font class="font-color"><?= count($studyNum) ?>次</font></a>
                            <a href="#" class="last">上次学习时间：<font class="font-color"><?= $lastStudyTime ?></font></a>
                            <a href="#" class="add-up-time">累计学习时长：<font class="font-color"><?= DateUtil::intToTime($totalLearningTime) ? DateUtil::intToTime($totalLearningTime) : '00:00:00'?></font></a>
                            <a href="#" class="score">最高成绩：<font class="font-color"><?= $examines['MaxScore'] ?>分</font></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="video-player">
                <div class="vp-background box-shadow-1">
                    <div class="vp-background box-shadow-2">
                        <div id="main" class="vp-play">
                            <div class='download-player'>
                                <image src="/filedata/study/icon/warning.jpg"/>
                                <br/><br/>
                                <span>
                                由于浏览器缺少播放插件，需下载安装后才能显示相关内容！<br/>
                                请<a href="https://get2.adobe.com/cn/flashplayer/">点击此处</a>下载插件并安装，安装完毕后重启浏览器即可播放！</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--视频下方的信息栏-->
                <div class="bottom-info">
                    <div class="bottom-info-content">
                        <span class="study"><font>还学过本课的人(<?= count($manNum) ?>):&nbsp;</font>
                            <?php foreach ($manNum as $key => $value): ?>
                                <?php if($key >= 10) break;?>
                                <a href="javastrap:;" title="<?= $value['real_name']?>">
                                    <li class="study-people"><?= Html::img([$value['avatar']], ['width' => 26, 'height' => 26, 'class' => 'img-circle', 'style' => 'margin-right: 5px;']) ?></li>
                                </a>
                            <?php endforeach;?>
                        </span>
                        <div class="pull-right">
                            <!--分享部分-->
                            <span class="share" id="share"><font>分享:&nbsp;</font>
                                <span class="share-content bdsharebuttonbox bdshare-button-style0-16" data-bd-bind="1506334717468">
                                    <a title="分享到微信" href="#" class="share-course bds_weixin" data-cmd="weixin"></a>
                                    <a title="分享到QQ空间" href="#" class="share-course bds_qzone" data-cmd="qzone"></a>
                                    <a title="分享到新浪微博" href="#" class="share-course bds_tsina" data-cmd="tsina"></a>
                                    <a title="分享到QQ好友" href="#" class="share-course bds_sqq" data-cmd="sqq"></a>
                                    <a href="#" class="share-course bds_more" data-cmd="more"></a>
                                </span>
                            </span>
                            <!--分享部分-->
                            <!--收藏部分-->
                            <span class="collection">
                                <a id="favorite" href="#" data-add="<?= $isFavorite ? 'true' : 'false'?>">
                                    <i class="fa <?= $isFavorite ? 'fa-star' : 'fa-star-o'?>"></i>
                                    <font class="font">收藏</font>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'favorites-form'
                                    ]); ?>
                                    
                                    <?= Html::hiddenInput('Favorites[course_id]', $model->id) ?>
                                    <?= Html::hiddenInput('Favorites[user_id]', Yii::$app->user->id) ?>
                                    
                                    <?php ActiveForm::end(); ?>
                                </a>
                            </span>
                            <!--收藏部分-->
                            <!--点赞部分-->
                            <span class="thumbs-up">
                                <a id="thumbs-up" href="#" data-add="<?= $isAppraise ? 'true' : 'false'?>">
                                    <i class="fa <?= $isAppraise ? 'fa-thumbs-up' : 'fa-thumbs-o-up'?>"></i>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'thumbs-up-form'
                                    ]); ?>

                                    <?= Html::hiddenInput('CourseAppraise[course_id]', $model->id) ?>
                                    <?= Html::hiddenInput('CourseAppraise[user_id]', Yii::$app->user->id) ?>

                                    <?php ActiveForm::end(); ?>
                                </a>
                                <font class="font"><?= $model['zan_count'] <= 99999 ? number_format($model['zan_count']) : substr(number_format((($model['zan_count'] / 10000) * 10) / 10, 4), 0, -3) . '万'; ?></font>
                            </span>
                            <!--点赞部分-->
                            <!--教学视频播放量-->
                            <span class="play-volume">
                                <i class="fa fa-play-circle-o"></i>
                                <font class="font">
                                    <?= $model['play_count'] <= 99999 ? number_format($model['play_count']) : substr(number_format((($model['play_count'] / 10000) * 10) / 10, 4), 0, -3) . '万'; ?>
                                </font>
                            </span>
                            <!--教学视频播放量-->
                        </div>    
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
$js = <<<JS
    
    //学习时长  
    var log_id,studytime;
    setInterval(function () {
        $.get("/study/default/study-log?course_id="+$model->id,function(result){
            if(result['code'] == '200'){
                log_id = result.data['id'];
                studytime = result.data['studytime'];
            }else{

            }
        })
    },30000); 
    /*$("#timer-form").serialize());*/
        
    $.get("/study/default/play-log?course_id="+$model->id,function(result){
        if(result['code'] == '200'){
            log_id = result.data['id'];
        }else{

        }
    })
   timer($studytime);
JS;
$this->registerJs($js, View::POS_READY);
?>


<script type="text/javascript">
    var cosdate = <?= Json::encode($cosdate) ?>;
    var domain = 'http://course.tutor.eecn.cn';
    var id = "<?= Yii::$app->user->id ?>";                                                  //用户id
    var name = "<?= Yii::$app->user->identity->real_name ?>";                               //用户名
    var course_id = "<?= $model->id ?>";                                                    //课程ID
    var token = "<?= Yii::$app->user->identity->access_token ?>";                           //访问令牌
    var netpath = encodeURIComponent(domain + "<?= $coursePlath ?>");                          //课程资源网络路径
    var templetNetPath = encodeURIComponent(domain + "<?= trim($model->template->path) ?>");   //课程资源网络路径
    var webserver = "<?= WEB_ROOT ?>";                                                      //webservice 服务路径
    var player = domain + "<?= trim($model->template->player) ?>";                          //播放器路径 
    var ver = "<?= $model->template->version ?>";                                           //模板版本
    //======================    
    // 课件变量
    /*获取学习记录的接口:/nes/course/nesCourseStudyrecord/getStudyStatusJson.ee?formMap.courseId=df935ae658a1461aaebf067b47db209d&formMap.memberId=05fc37ce2c6*04e689f8cb5af4f50a2aa&formMap.termId=2bac580b58a64760b9f15dd8cde69b04
     */
    window.onload = function () {
        //提交时一定需要的参数每一个健值使用|隔开
        var staticFormField = encodeURIComponent("token=" + token);

        var flashvars = '?id=' + id + '&name=' + name + '&course_id=' + course_id + '&netpath=' + netpath + '&templetNetPath=' + templetNetPath + '&webserver=' + webserver + '&token=' + token + '&version=' + ver + "&debug=true";
        var params = {allowFullScreen: "true", allowScriptAccess: "always"};
        swfobject.embedSWF(player + flashvars, "main", "1000", "574", "9.0.0", "expressInstall.swf", null, params);
    };
</script>

<?php
PlayerAssets::register($this);
StudyAsset::register($this);
?>
