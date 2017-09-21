<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */


$content = $content.$this->render('header')

?>

<?php
$js = <<<JS
   
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>