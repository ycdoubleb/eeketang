<?php

use common\models\course\Course;
use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div id="default" class="user-default-sync user-default study">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止你共有<em><?= $totleCount ?></em>次观摩记录</span></div>
    </div>
    <?php if(count($studyLog) <= 0): ?>
    <h4>没有找到观摩记录，赶紧去观摩吧！</h4>
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