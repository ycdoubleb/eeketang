<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
?>

<header class="header">
    <div class="container">
        <?php 
        /**
         * $params = [
         *      [
         *          'label' => Html::img(['/filedata/site/image/logo.png']),
         *          'options' => ['class' => 'pull-right'],
         *          'childs' => [
         *              [
         *                  'label' => Html::img(['/filedata/site/image/logo.png'])
         *              ],
         *           ],
         *      ],
         *      [
         *          'label' => Html::img(['/filedata/site/image/logo.png']),
         *          'options' => ['class' => 'pull-right'],
         *      ],
         *   ]
         */
        if(!empty($params)){
            foreach ($params as $items){
                echo Html::beginTag('p', isset($items['options'])?$items['options']:null);
                echo $items['label'];
                if(isset($items['childs'])){
                    foreach ($items['childs'] as $childs) {
                        echo $childs['label'];
                    }
                }
                echo Html::endTag('p');
            }
        } ?>
    </div>
</header>

<?php
$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>