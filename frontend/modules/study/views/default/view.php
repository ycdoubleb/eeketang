<?php

use common\models\course\Course;
use common\models\Menu;
use common\widgets\players\PlayerAssets;
use frontend\modules\study\assets\StudyAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $menu Menu */
/* @var $model Course */

$this->title = $model->courseware_name;
$coursePlath = $model->path;

?>

<div class="study-default-view _view">
    
    <div class="body-content">
        
        <div class="crumbs-bar">
            <div class="cb-nav">
                
                <div class="cbn-icon"><i class="icon icon-book"></i></div>
                <div class="cbn-item"><span class="position">所在位置：</span></div>
                <div class="cbn-item"><?= Html::a('首页', ['/site/index']) ?><i>&gt;</i></div>
                <div class="cbn-item"><?= Html::a($model->category->fullPath) ?><i>&gt;</i></div>
                <div class="cbn-item"><span><?= Html::encode($model->category->name) ?></span><i style="color: #8a8a8a;">&gt;</i></div>
                <?php foreach ($attrs as $attr_value): ?>
                <div class="cbn-item"><span><?= Html::encode($attr_value['value']) ?></span><i style="color: #8a8a8a;">&gt;</i></div>
                <?php endforeach; ?>
                <div class="cbn-item"><span><?= Html::encode("【{$model->unit}】{$this->title}") ?></span></div>
                
            </div>
        </div>
        
        <div class="video-player">
            <div class="vp-background box-shadow-1">
                <div class="vp-background box-shadow-2">
                    <div id="main" class="vp-play"></div>
                </div>
            </div>
        </div>
        
    </div>
        
</div>

<?php
$subject = ArrayHelper::getValue($filter, 'parent_cat_id');
$js = <<<JS
    
    var subjectArray = new Array("sites", "yellow", "green", "blue", "purple", "brown");
    $("body").addClass(subjectArray[0]);
JS;
    $this->registerJs($js, View::POS_READY);
?>

<script type="text/javascript">
        var domain = 'http://course.tutor.eecn.cn';
	var id = encodeURIComponent("x")                                                    //用户id
	var name = encodeURIComponent("e")                                                  //用户名
	var netpath = encodeURIComponent(domain+"<?= $coursePlath ?>")                                        //课程资源网络路径
	var templetNetPath = encodeURIComponent(domain+"<?= trim($model->template->path) ?>")         //课程资源网络路径
	var webserver = encodeURIComponent("x")                                                 //webservice 服务路径
        var player = domain + "<?= trim($model->template->player) ?>";                                //播放器路径 
        var version = "<?= trim($model->template->version) ?>";
	//======================    
	// 课件变量
			/*获取学习记录的接口:/nes/course/nesCourseStudyrecord/getStudyStatusJson.ee?formMap.courseId=df935ae658a1461aaebf067b47db209d&formMap.memberId=05fc37ce2c6*04e689f8cb5af4f50a2aa&formMap.termId=2bac580b58a64760b9f15dd8cde69b04
	*/
	window.onload = function(){
	    //提交时一定需要的参数每一个健值使用|隔开
	    var staticFormField = encodeURIComponent("courseId=1") 
					
	    var flashvars = '?id='+id+'&name='+name+'&netpath='+netpath+'&templetNetPath='+templetNetPath+'&webserver='+webserver+'&staticFormField='+staticFormField+"&debug=true&version="+version;
            var params = {allowFullScreen:"true",allowScriptAccess:"always"};
            swfobject.embedSWF(player+flashvars, "main", "1000", "574", "9.0.0", "expressInstall.swf",null,params);
	};
</script>

<?php 
    PlayerAssets::register($this);
    StudyAsset::register($this);
?>