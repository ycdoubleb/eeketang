<?php

use backend\modules\course\assets\CourseAssets;
use common\models\course\Course;
use common\widgets\players\PlayerAssets;
use yii\helpers\Json;
use yii\web\View;

/* @var $this View */
/* @var $model Course */

$this->title = Yii::t('app', 'Preview').':'.$model->courseware_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Course}{List}', [
        'Course' => Yii::t('app', 'Course'),
        'List' => Yii::t('app', 'List'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="course-view" class="c-skin-green">
    <div class="title">
        <p><?= $model->courseware_name ?></p>
    </div>
    <div class="content">
        <div id="main">
            <p>
                <a href="http://www.adobe.com/go/getflashplayer">
                    <img src="" alt="Get Adobe Flash player" width="112" height="33" />
                </a>
            </p>
        </div>
    </div>
</div>
<?php
    $coursePlath = trim($model->path);
?>
<script type="text/javascript">
    var domain = 'http://course.tutor.eecn.cn';
    var id = "<?= Yii::$app->user->id ?>";                                                  //用户id
    var name = "<?= Yii::$app->user->identity->nickname ?>";                               //用户名
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
    CourseAssets::register($this);
?>
