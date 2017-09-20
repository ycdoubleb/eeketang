<?php

use yii\web\View;

/* @var $this View */
?>

<footer class="footer">
    <div class="container">
        <span>
            云服务提供商：<?= yii\helpers\Html::img(['/filedata/site/image/eelogo.png'], ['style' => 'margin-top: -15px;']) ?>&nbsp;&nbsp;广东易扬开泰网络科技有限公司
        </span>
    </div>
</footer>

<?php
$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>