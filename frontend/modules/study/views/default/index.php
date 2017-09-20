<?php

use frontend\modules\study\assets\StudyAsset;
use wskeee\utils\ArrayUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');
?>

<div class="study-default-index _index">

    <div class="banner">
        <div class="banner-background">
            <?php //echo Html::img(['/filedata/site/image/background.jpg'], ['class' => 'background-img']) ?>
        </div>
    </div>

    <div id="scroll" class="body-content">
        <div class="row">
            <!--面包屑-->
            <div class="crumbs-bar">
                <div class="cb-nav">
                    <div class="cbn-item">
                        <span>筛选条件：</span>
                        <b><?= $category->name ?></b>
                    </div>
                    
                    <?php foreach ($filters as $filter_name => $filter_value): ?>
                    <div class="cbn-item">
                        <i class="cbni-arrow">&gt;</i>
                        <?= Html::a("<b>{$filter_name}：</b><em>{$filter_value['filter_value']}</em><i>×</i>", $filter_value['url'], ['class' => 'cnbi-key']) ?>
                    </div>
                   <?php endforeach; ?>
                    
                </div>
            </div>
            <!--面包屑-->
            <!--条件选择-->
            <div class="selector-column">
                
                <?php if (!isset($filter['cat_id']) && count($result['cats'])>0): ?>
                    <div class="sc-attribute">
                        <div class="sc-key">
                            <span><?= Yii::t('app', 'Cat') ?>：</span>
                        </div>
                        <div class="sc-value">
                            <ul>
                                <?php foreach ($result['cats'] as $cat): ?>
                                    <li><?= Html::a($cat['name'], Url::to(array_merge(['index'], array_merge($filter, ['cat_id' => $cat['id'], 'page' => 1])))) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php foreach ($result['attrs'] as $attr_name => $attr_arr):?>
                    <div class="sc-attribute">
                        <div class="sc-key">
                            <span><?= $attr_name ?>：</span>
                        </div>
                        <div class="sc-value">
                            <ul>
                                <?php $attr_arr['value'] = ArrayUtil::sortCN($attr_arr['value']);?>
                                <?php foreach ($attr_arr['value'] as $attr_label): ?>
                                    
                                    <li>
                                    <?php
                                        //合并之前已选择的属性过滤条件
                                        $attrs = array_merge(isset($filter['attrs']) ? $filter['attrs'] : [] , [['attr_id' => $attr_arr['attr_id'], 'attr_value' => $attr_label]]);
                                        //过滤之前已选择过滤条件
                                        $params = array_merge($filter,['attrs' => $attrs, 'page' => 1]);
                                        echo Html::a($attr_label, Url::to(array_merge(['index'], $params)))
                                    ?>
                                    </li>
                                    
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <!--条件选择-->
            <!--过滤器-->
            <?= $this->render('/layouts/_filter', ['filter' => $filter]) ?>
            <!--过滤器-->
            <!--课程课件-->
            <div class="goods-column">
                <?php foreach ($result['courses'] as $index => $course):?>
                
                <div class="<?= ($index % 5 == 4 ) ? 'none-margin' : 'gc-item'; ?>">
                    <?= Html::a('<div class="gc-img">'.Html::img([$course['img']], ['width' => '100%']).'</div>', ['view', 'parent_cat_id' => $course['parent_cat_id'], 'cat_id' => $course['cat_id'], 'id' => $course['id']], ['title' => "【{$course['unit']}】{$course['name']}" ]) ?>
                    <div class="gc-name course-name"><?= "【{$course['unit']}】{$course['name']}" ?></div>
                    <div class="gc-see">
                        <i class="glyphicon glyphicon-play-circle"></i>
                        <span><?= $course['play_count'] <= 99999 ? number_format($course['play_count']) : substr(number_format((($course['play_count']/10000)*10)/10, 4),0,-3).'万'; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!--课程课件-->
            <!--分页-->
            <?= $this->render('/layouts/_page', ['filter' => $filter, 'pages' => $pages]) ?>
            <!--分页-->

        </div>   

    </div>

</div>

<?php
$subject = ArrayHelper::getValue($filter, 'parent_cat_id');
$scroll = isset($filter['cat_id']) || isset($filter['attrs']) || isset($filter['page']) ? 1 : 0;
$js = <<<JS
    var subjectArray = new Array("sites", "yellow", "green", "blue", "purple", "brown");
    $("body").addClass(subjectArray[$subject]);
        
    if($scroll) 
        window.location.hash = "#scroll"; 
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    StudyAsset::register($this);
?>