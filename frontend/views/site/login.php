<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\SiteAsset;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="sign-overlay"></div>
<div class="signpanel"></div>-->

<div class="login-content">
    <div class="login-con">
        <div class="container">
            <div class="login">
                <div class="log signin">
                    <div class="log-heading">
                        <h4 class="log-title">Welcome</h4>
                    </div>
                    <div class="user-photo">
                        <?= Html::img(['/filedata/site/image/photo.png']) ?>
                    </div>
                    <div class="log-body">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?=
                        $form->field($model, 'username', [
                            'inputOptions' => [
                                'placeholder' => '请输入账户',
                            ],
                            'inputTemplate' =>
                            '<div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>{input}
                        </div>',
                        ])->label(false)
                        ?>

                        <?=
                        $form->field($model, 'password', [
                            'inputOptions' => [
                                'placeholder' => '请输入密码',
                            ],
                            'inputTemplate' =>
                            '<div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>{input}
                        </div>',
                        ])->passwordInput()->label(false)
                        ?>

                        <div class="form-group">
                            <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-success btn-quirk btn-block', 'name' => 'login-button']) ?>
                        </div>  

                        <div><a href="#" class="forgot">忘记密码？</a></div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- panel -->
<?php
SiteAsset::register($this);
?>