<?php

use backend\modules\course\assets\CourseAssets;
use common\models\course\Course;
use common\widgets\players\PlayerAssets;
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
                    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" />
                </a>
            </p>
        </div>
    </div>
</div>
<?php
    $coursePlath = trim($model->path);
    if(substr($coursePlath, 0, 1) != '/'){
        $coursePlath = '/'.$coursePlath;
    }
    if(substr($coursePlath, -1, 1) != '/'){
        $coursePlath = $coursePlath.'/';
    }
?>
<script type="text/javascript">
        var domain = 'http://course.tutor.eecn.cn';
	var id = encodeURIComponent("x")                                                    //用户id
	var name = encodeURIComponent("e")                                                  //用户名
	var netpath = encodeURIComponent(domain+"<?= $coursePlath ?>")                                        //课程资源网络路径
	var templetNetPath = encodeURIComponent(domain+"<?= trim($model->template->path) ?>")         //课程资源网络路径
	var webserver = encodeURIComponent("x")                                                 //webservice 服务路径
        var player = domain + "<?= trim($model->template->player) ?>";                                //播放器路径 
	//======================    
	// 课件变量
			/*获取学习记录的接口:/nes/course/nesCourseStudyrecord/getStudyStatusJson.ee?formMap.courseId=df935ae658a1461aaebf067b47db209d&formMap.memberId=05fc37ce2c6*04e689f8cb5af4f50a2aa&formMap.termId=2bac580b58a64760b9f15dd8cde69b04
	*/
	window.onload = function(){
	    //提交时一定需要的参数每一个健值使用|隔开
	    var staticFormField = encodeURIComponent("courseId=1") 
					
	    var flashvars = '?id='+id+'&name='+name+'&netpath='+netpath+'&templetNetPath='+templetNetPath+'&webserver='+webserver+'&staticFormField='+staticFormField+"&debug=true";
            var params = {allowFullScreen:"true",allowScriptAccess:"always"};
            swfobject.embedSWF(player+flashvars, "main", "1000", "574", "9.0.0", "expressInstall.swf",null,params);
	};
</script>
<?php 
    PlayerAssets::register($this);
    CourseAssets::register($this);
?>
