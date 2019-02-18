<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
?>

<footer class="footer">
    <div class="container">
        <div class="pull-left">
            Copyright &copy; 2017 www.eeketang.com&nbsp;&nbsp;ee课堂&nbsp;&nbsp;版权所有&nbsp;&nbsp;
            广州远程教育中心有限公司<br/><?=Html::a('备案号粤ICP备17135407号-2','http://www.miitbeian.gov.cn/')?>
        </div>
        <div class="pull-right">
            <?= Html::a(Html::img(['/filedata/site/image/eeketang.png']), Url::to("http://eeketang.gzedu.com/")) ?>
        </div>
    </div>
</footer>

<?php
$js = <<<JS
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>