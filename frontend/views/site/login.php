<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\SiteAsset;

$this->title = Yii::t('app', 'Login');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-body has-title">
    <?= Html::img(["/filedata/site/image/loginbg_{$model->role}.png"], ['width' => '100%', 'style'=> 'position: absolute;']) ?>
    <div class="login-content">
        <div class="container">
            <div class="login-box">
                <div class="signin">
                    <h4 class="log-title">Welcome</h4>
                    <div class="avatars">
                        <?= Html::img(['/filedata/site/image/photo.png']) ?>
                    </div>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username', [
                        'inputOptions' => ['placeholder' => Yii::t('app', 'Enter one user name')],
                        'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>{input}</div>',
                    ])->label(false)
                    ?>

                    <?= $form->field($model, 'password', [
                        'inputOptions' => ['placeholder' => Yii::t('app', 'Enter one password')],
                        'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>{input}</div>',
                    ])->passwordInput()->label(false)
                    ?>

                    <?= Html::activeHiddenInput($model, 'role') ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-success btn-quirk btn-block', 'name' => 'login-button']) ?>
                    </div>  

                    <?= Html::a(Yii::t('app', 'Forgot the password?'), 'javascript:;', ['class' => 'forgot']) ?>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
    SiteAsset::register($this);
?>