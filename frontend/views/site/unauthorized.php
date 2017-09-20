<?php

use frontend\assets\HomeAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="site-unauthorized">
    
    <div class="body-content">
        <div class="row">
        
            <div class="unauthorized">
                <div class="unauthorized-prompt">
                    <span>该IP（<?= $ip; ?>）未授权！</span>
                    <?php $form = ActiveForm::begin([
                        'options'=>[
                            'id' => 'experience-form',
                            'class'=>'form-horizontal',
                        ],
                        'action' => ['site/experience'],
                        'method' => 'get'
                    ]) ?>
                    <div class="form-group required">
                        
                        <label class="col-lg-12 col-md-12 control-label form-label" for="experience-code">输入体验码</label>
                        <div class="col-lg-8 col-md-8">
                            <?= Html::textInput('experience_code', null, ['id' => 'experience-code', 'class' => 'form-control', 'placeholder' => '请输入体验码', 'aria-required' => true, 'aria-invalid' => true]) ?>
                        </div>
                        <div class="col-lg-4 col-md-4" style="padding: 0px;">
                            <div class="help-block"></div>
                        </div>
                        
                    </div>
                    <?php ActiveForm::end(); ?>
                    
                    <div class="col-lg-12" style="padding: 0px">
                    <?= Html::a(null, 'javascript:;', ['id' => 'submit', 'class' => 'btn experience disabled', 'role' => 'button']) ?>
                    </div>
                    
                </div>
            </div>
            
        </div>    
    </div>
    
</div>

<?php
$url = Yii::$app->request->url;
$js = <<<JS
    $("#submit").click(function(){
        $('#experience-form').submit();
    });
    
    $("#experience-code").focus(function(){
        $(".form-group").removeClass("has-success");
        $(".form-group").removeClass("has-error");
        $(".help-block").html(""); 
        $("#submit").addClass("disabled");
    });    
        
    $("#experience-code").blur(function(){
        var code = $(this).val();
        $.get("$url",{experience_code: code},function(data){
            if(data['type']){
                $(".form-group").addClass("has-success");
                $(".help-block").html('<i class="hb-icon yes"></i><span class="hb-prompt">'+data['message']+'</span>');
                $("#submit").removeClass("disabled");
            }else{
                $(".form-group").addClass("has-error");
                $(".help-block").html('<i class="hb-icon no"></i><span class="hb-prompt">'+data['message']+'</span>');
            }
                
        });
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    HomeAsset::register($this);
?>