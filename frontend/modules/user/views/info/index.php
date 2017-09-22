<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */

?>

<div class="info-content">
    <div class="container">
        <div class="avatars">
            <img src="/filedata/user/images/avatar.png">
            <p class="user-name">heqianqian</p>
        </div>
        <div class="base-info">
            
        </div>
        <div class="grage">
            
        </div>
    </div>
</div>

<?php
    UserAsset::register($this);
