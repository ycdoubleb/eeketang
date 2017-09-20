<?php

use common\models\Buyunit;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php 
    $buyunit = Buyunit::getCurrentBuyunit();
    $params = [
        [
            'label' => $buyunit['is_experience'] ? Html::img(['/filedata/site/image/logo-1.png']) : Html::img("{$buyunit['buyunity_logo']}"),
            'options' => ['class' => 'pull-left'],
        ],
        [
            'label' => !$buyunit['is_experience'] ? '中小学数字化资源云平台' : '',
            'options' => ['class' => 'pull-right'],
        ],
    ];
    
    echo $this->render('_header', ['params' => $params]); 
?>
    
<div class="wrap k12">
        
    <div class="container">
        <?= Alert::widget() ?>
        <!--<h3>欢迎使用数字化资源云平台</h3>-->
        <?= $content ?>
    </div>
</div>

<?php echo $this->render('_footer'); ?>   
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
