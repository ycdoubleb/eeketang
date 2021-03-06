<?php

use common\models\Buyunit;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use kartik\widgets\AlertBlock;
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
    echo $this->render('header', $buyunit['is_experience'] ? ['items' => null] : null); 
 ?>
    
<div class="wrap">
    
    <?php // Alert::widget() ?>
    <?= AlertBlock::widget([
        'useSessionFlash' => TRUE,
        'type' => AlertBlock::TYPE_GROWL,
        'delay' => 0
    ]);?>
    <?= $content ?>
        
</div>

<?php echo $this->render('footer'); ?>   
   
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
