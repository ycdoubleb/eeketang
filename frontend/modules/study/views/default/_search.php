<?php

use frontend\modules\study\assets\SearchAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>
<div class="study-default-search _search">
    
    <div class="body-content">
        <div class="row">
        
            <div class="search-prompt">
                <div class="sp-result"><i class="icon icon-search"></i><span>搜索结果</span></div>
                <div class="sp-content">搜索“<?= $filter['keyword'] == null ? '全部' : $filter['keyword'] ?>”得出的结果如下：</div>
            </div>

            <!--过滤器-->
            <?= $this->render('/layouts/_filter', ['filter' => $filter]) ?>
            <!--过滤器-->

            <div class="goods-column">
               <?php foreach ($result['courses'] as $index => $course): ?>
                
                <div class="<?= ($index % 5 == 4 ) ? 'none-margin' : 'gc-item'; ?>">
                    <?= Html::a('<div class="gc-img">'.Html::img([$course['img']], ['width' => '100%']).'</div>', ['view', 'parent_cat_id' => $course['parent_cat_id'], 'cat_id' => $course['cat_id'], 'id' => $course['id']], ['title' => "【{$course['unit']}】{$course['courseware_name']}"]) ?>
                    <div class="gc-name course-name"><?= "【{$course['unit']}】{$course['courseware_name']}" ?></div>
                    <div class="gc-see">
                        <i class="glyphicon glyphicon-play-circle"></i>
                        <span><?= $course['play_count'] ?></span>
                    </div>
                </div>

                <?php endforeach; ?>

            </div>

            <!--分页-->
            <?= $this->render('/layouts/_page', ['filter' => $filter, 'pages' => $pages]) ?>
            <!--分页-->
            
        </div>    
    </div>
    
</div>

<?php
$js = <<<JS
   
    
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php 
    SearchAsset::register($this);
?>