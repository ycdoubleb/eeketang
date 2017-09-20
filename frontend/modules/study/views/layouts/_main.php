<?php

/* @var $this View */
/* @var $content string */

use common\models\Buyunit;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

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
    
    echo $this->render('@frontend/views/layouts/_header', ['params' => $params]); 
?>
    
<div class="wrap k12">
    
    <?php echo $this->render('@app/views/layouts/_navbar'); ?>
    
    <div class="container">
        
        <?= Alert::widget() ?>
        <?= $content ?>
        
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
