<?php

use frontend\modules\study\assets\StudyAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="study-default-error _error">
    
    <div class="body-content">
        <div class="row">
        
            <div class="study-error"></div>
            
        </div>    
    </div>
    
</div>

<?php
$params = Yii::$app->request->queryParams;
$subject = ArrayHelper::getValue($params, 'parent_cat_id');
$js = <<<JS
    
    var subjectArray = new Array("sites", "yellow", "green", "blue", "purple", "brown");
    $("body").addClass(subjectArray[$subject]);
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    StudyAsset::register($this);
?>

