<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="user-default-index" style="padding: 60px 0 20px;">
    
    <?= $this->render('/layouts/header') ?>
    
    <div class="container">
        
        <?= $this->render('/layouts/left') ?>
    
    </div>
    
</div>

<?php

$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>