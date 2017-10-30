<?php

use backend\modules\course\assets\CoursenodeAssets;
use common\models\course\Course;
use common\models\course\searchs\CourseSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Course */

$this->title = Yii::t('app', '{Course}{Check}', [
            'Course' => Yii::t('app', 'Course'),
            'Check' => Yii::t('app', 'Check'),
        ]);
$_csrf = Yii::$app->getRequest()->getCsrfToken();
?>
<div class="course-check-index">
    <div>
        <span>总量：</span>
        <span>共有<?= count($courses) ?>门课程需要处理！</span>
    </div>
    <div>
        <span>处理进度：</span>
        <span id="act_log"></span>
    </div>
    <div>
        <span>正在进行：</span>
        <span id="act_status"></span>
    </div>

    <div>
        <br/>
        <span>失败表：</span>
        <table id="act_fail" border="1" class="table table-striped table-bordered">
            <tr>
                <th>课程ID</th>
                <th>标题</th>
                <th>获取XML结果</th>
                <th>保存环节结果</th>
                <th>返馈信息</th>
            </tr>
        </table>
    </div>
    
    <div>
        <span>结果表：</span>
        <table id="act_success" border="1" class="table table-striped table-bordered">
            <tr>
                <th>课程ID</th>
                <th>标题</th>
                <th>获取XML结果</th>
                <th>保存环节结果</th>
                <th>返馈信息</th>
            </tr>
        </table>
    </div>
</div>
<script>
    var courseParse;
    var wrong = '<span style="color:#ff0000">×</span>';
    var right = '<span style="color:#0000ff">√</span>';
    window.onload = function () {
        courseParse = new Wskeee.course.CoursePathCheck({
            proxy_server:'/course/coursenode/proxy-getxml',
            addActLog: function (course_id, title, getxmlresult, postresult, message) {
                $tr = $("<tr></tr>");
                $("<td>" + course_id + "</td>").appendTo($tr);
                $("<td>" + title + "</td>").appendTo($tr);
                $("<td>" + (getxmlresult == 0 ? wrong : right) + "</td>").appendTo($tr);
                $("<td>" + (postresult == 0 ? wrong : right) + "</td>").appendTo($tr);
                $("<td>" + message + "</td>").appendTo($tr);
                
                if (getxmlresult == 0 || postresult == 0) {
                    $tr.appendTo($('#act_fail'));
                }else{
                    //$tr.appendTo($('#act_success'));
                }
            },
            updateActStatus: function (mes) {
                $('#act_status').html(mes);
                $("#act_log").html("已处理：" + (courseParse.currentIndex + 1) + " 门, " + (courseParse.maxNum - courseParse.failNum) + " 成功！" + courseParse.failNum + " 失败！" + "剩 " + (courseParse.maxNum - courseParse.currentIndex - 1) + " 待处理!");
            },
            finish:function(){
                $('#act_status').html("所有操作已完成！");
            }
        });
        courseParse.setCourse(<?= Json::encode($courses) ?>);
    }
</script>
<?php
CoursenodeAssets::register($this);
?>