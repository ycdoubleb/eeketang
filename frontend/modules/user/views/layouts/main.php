<?php

use frontend\modules\user\assets\UserAsset;
use yii\web\View;

/* @var $this View */


$content = $this->render('header').'<div class="container">'.$this->render('left').$content.'</div>';

echo $this->render('@app/views/layouts/main',['content' => $content]);

?>

<?php
$js = <<<JS
   
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>