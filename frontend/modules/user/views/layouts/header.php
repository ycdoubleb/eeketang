<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
$is_student = Yii::$app->user->identity->isRoleStudent();   //是否是学生
?>

<div class="user-header">
    <div class="container">
        <div class="avatars">
            <div class="avatars-circle">
                <?= Html::img([Yii::$app->user->identity->avatar], ['class' => 'img-circle']) ?>
            </div>
            <div class="data-info">
                <p><?= Yii::t('app', 'Surname') ?>：<span><?= Yii::$app->user->identity->real_name ?></span></p>
                <?php if($is_student): ?>
                <p><?= Yii::t('app', 'Grade') ?>：<span><?= Yii::$app->user->identity->profile->getGrade() ?></span></p>
                <p><?= Yii::t('app', 'Class') ?>：<span>12班</span></p>
                <?php else: ?>
                <p><?= Yii::t('app', 'Job Title') ?>：<span><?= null ?></span></p>
                <?php endif;?>
                <?= Html::a('<i class="fa fa-cog" aria-hidden="true"></i>个人资料', ['info/index'], ['class' => 'btn btn-primary'.(!$is_student ?' disabled':'')]) ?>
            </div>
        </div>
        <div class="ranking">
            <?php if($is_student): ?>
            <div class="placing">
                <p><span class="number"><?= $webUserRank['rank'] ?></span></p>
                <p><span class="words">名次</span><i class="south-east" title="累积学习时长在全校的排名。">？</i></p>
            </div>
            <div class="course-num">
                <p><span class="number"><?= $webUserRank['cour_num'] ?></span></p>
                <p><span class="words">学习课程数</span></p>
            </div>
            <div class="first">
                <?= Html::img([$rankFirst['avatar']], ['class' => 'img-circle', 'title' => $rankFirst['real_name']]) ?>
                <span>夺得了第<em>1</em>名</span>
            </div>
            <?php else: ?>
            <div class="course-num">
                <p><span class="number"><?= $webUserRank['cour_num'] ?></span></p>
                <p><span class="words">观摩课程数</span></p>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $(function () {
        $('.south-east').powerTip({placement: 'se'});
    });  
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
UserAsset::register($this);
?>
