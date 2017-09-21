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
    echo $this->render('_header', $buyunit['is_experience'] ? ['items' => null] : null); 
 ?>
    
<div class="wrap">
        
    <div class="container">
        
        <?= Alert::widget() ?>
        <?= $content ?>
        
    </div>
</div>

<?php echo $this->render('_footer'); ?>   
   
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
