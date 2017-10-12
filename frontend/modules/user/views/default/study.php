<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div id="default" class="user-default-sync user-default study">
    
    <div class="category">
        <div class="cat-name prompt"><span>到目前为止你共有<em><?= $totleCount ?></em>次学习记录</span></div>
    </div>
    
    <?= $this->render('wrapper', ['results' => $studyLog]) ?>
    
</div>

<?php

$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>