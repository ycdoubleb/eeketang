<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="info-content">
    <div class="user-header">
        <div class="container">
            <div class="avatars">
                <div class="avatars-circle">
                    <?= Html::img([Yii::$app->user->identity->avatar], ['class' => 'img-circle']) ?>
                </div>       
            </div>
            <div class="base-info">
                <div class="base-infocontent">
                    <div class="name-info">
                        <div class="info-title">基本信息</div>
                        <div class="info-name">姓名</div>
                        <div class="info-username"><?= Yii::$app->user->identity->real_name ?></div>
                    </div>
                    <div class="zhanghao-info">
                        <div class="zhanghao-kongde"></div>
                        <div class="zhanghao">账号</div>
                        <div class="zhanghao-name"><?= Yii::$app->user->identity->username ?></div>
                    </div>
                </div>
            </div>
            <div class="grage">
                <div class="grage-content">
                    <div class="grage-info">
                        <div class="form-grage">所属年级</div>
                        <!--, ['prompt' => $prompt], ['class' => 'dropdownlist']-->
                        <div class="seclct-grage">
                            <?= $this->render('_form', ['model' => $model]) ?>
                        </div>
                    </div>
                </div>
                <div class="grage-button">
                    <p>
                        <?= Html::a(Yii::t('app', 'Save'), 'javascrip:;', ['id' => 'submit', 'class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('app', 'Back'), ['default/index'], ['class' => 'btn btn-success']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $("#submit").click(function(){
        $('#user-profile-form').submit();
        alert('选择年级成功！');
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>


<?php
    UserAsset::register($this);
?>
