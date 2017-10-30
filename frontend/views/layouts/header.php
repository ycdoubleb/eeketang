<?php

use common\models\course\Course;
use common\models\StudyLog;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
?>

<header class="header">
    
<?php    
    if(!isset($items) || empty($items)){
        $items = ['label' => Html::a(Html::img(['/filedata/site/image/schoollogo.png']), Url::to("/"))];
    }
    
    if(!isset($menus) || empty($menus)){
        $menus = [
            ['label' => '学校首页', 'url' => ['/site/index']],
            ['label' => '直播课', 'url' => ['/site/#']],
            ['label' => '录播课', 'url' => ['/site/#']]
        ];
    }
    
    //查询计算用户超过5分钟学习时长的课程
    $excess_query = (new Query())->select(['IF(SUM(StudyLog.studytime)/60<5,NULL,StudyLog.id) AS id'])
        ->from(['StudyLog' => StudyLog::tableName()]);
    $excess_query->filterWhere(['StudyLog.user_id' => Yii::$app->user->id]);
    $excess_query->groupBy('StudyLog.course_id');
    //计算用户学习过的课程总数
    $num_qurey = (new Query())->select(['COUNT(LogCourNum.id)'])
        ->from(['LogCourNum' => $excess_query]);
    //查询学习记录
    $all_query = (new Query())->select(['StudyLog.course_id','Course.courseware_name AS cour_name','StudyLog.updated_at AS upd_at',
        "({$num_qurey->createCommand()->getRawSql()}) AS cour_num",
    ])->from(['StudyLog' => StudyLog::tableName()]);
    $all_query->leftJoin(['Course' => Course::tableName()], 'Course.id = StudyLog.course_id');
    $all_query->where(['user_id' => Yii::$app->user->id]);
    $all_query->orderBy('StudyLog.updated_at DESC');
    
    echo $this->render('navbar', [
        'items' => $items, 
        'menus' => $menus, 
        'studyLogs' => $all_query->one(), 
    ]); 
    
?>
    
</header>

<?php
$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>