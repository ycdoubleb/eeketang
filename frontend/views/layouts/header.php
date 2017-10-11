<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
?>

<header class="header">
    
<?php 
    if(!isset($items) || empty($items)){
        $items = ['label' => Html::img(['/filedata/site/image/schoollogo.png'])];
    }
    
    if(!isset($menus) || empty($menus)){
        $menus = [
            ['label' => '学院首页', 'url' => ['/site/cases']],
            ['label' => '直播课', 'url' => ['/site/#']],
            ['label' => '录播课', 'url' => ['/site/#']]
        ];
    }
        
    echo $this->render('navbar', ['items' => $items, 'menus' => $menus]); 
?>
    
</header>

<?php
$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>