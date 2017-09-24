<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */

?>

<div class="info-content " style="padding: 60px 0 20px;">
    <div class="container">
        <div class="avatars">
            <?= Html::img([Yii::$app->user->identity->avatar], ['class' => 'img-circle']) ?>
        </div>
        <div class="base-info">
            
        </div>
        <div class="grage">
            
        </div>
    </div>
</div>

<?php
    UserAsset::register($this);
?>
