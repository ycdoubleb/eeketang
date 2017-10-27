<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div id="default" class="user-student-study user-default study">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止你共有<em><?= $totleCount ?></em>次学习记录</span></div>
    </div>
    <?php if(count($studyLog) <= 0): ?>
    <div class="goods" style="margin-top: 20px;">
        <div class="error-404 error-404_3"></div>
    </div>
    <?php endif; ?>
    <?= $this->render('/layouts/wrapper', ['results' => $studyLog, 'tm_logo' => Course::$tm_logo]) ?>
    
</div>

<?php

$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>