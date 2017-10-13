<?php

use common\models\course\Course;
use frontend\modules\study\assets\StudyAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');

?>

<div class="study-default-index index">
    
    <div class="banner"></div>
    <div class="search" id="scroll">
        <div class="container">
            <div class="search-inner">
                <div class="search-input">
                    <?php $form = ActiveForm::begin([
                        'id' => 'search-form',
                        'action' => ['search', 'par_id' => $filter['par_id']],
                        'method' => 'get'
                    ]); ?>
                    <?= Html::textInput('keyword', ArrayHelper::getValue($filter, 'keyword'), ['class' => 'form-control', 'placeholder' => '请输入你想搜索的关键词']) ?>
                    <?php ActiveForm::end() ?>
                </div>
                <div class="search-button"><i class="icon icon-1"></i></div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <!--面包屑-->
            <div class="crumb">
                <ul class="crumb-nav">
                    <li><span>筛选条件：</span><b>广州特色资源库</b></li>
                    <li>
                        <i class="arrow">&gt;</i>
                        <a><b>分类:</b><em>小学</em><i>×</i></a>
                    </li>
                </ul>
            </div>
            <!--属性选择-->
            <div class="attribute">
                <ul class="attr-nav">
                    <?php if(!isset($filter['cat_id']) && count($results['category']) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Category') ?>：</span>
                        <ul class="attr-value">
                            <?php foreach ($results['category'] as $category): ?>
                            <li><?= Html::a($category['name'], Url::to(array_merge(['index'], array_merge($filter, ['cat_id' => $category['id'],'#'=>'scroll'])))) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['sub_id']) && count($results['subject']) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Subject') ?>：</span>
                        <ul class="attr-value">
                            <?php foreach ($results['subject'] as $subject): ?>
                            <li><?= Html::a($subject['name'], Url::to(array_merge(['index'], array_merge($filter, ['sub_id' => $subject['id'],'#'=>'scroll'])))) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['term']) && count(array_filter($results['term'])) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Term') ?>：</span>
                        <ul class="attr-value">
                            <?php asort($results['term']) ?>
                            <?php foreach ($results['term'] as $term): ?>
                            <li><?= Html::a(Course::$term_keys[$term], Url::to(array_merge(['index'], array_merge($filter, ['term' => $term,'#'=>'scroll'])))) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['grade']) && count(array_filter($results['grade'])) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Grade') ?>：</span>
                        <ul class="attr-value">
                            <?php asort($results['grade']) ?>
                            <?php foreach ($results['grade'] as $grade): ?>
                            <li><?= Html::a(Course::$grade_keys[$grade], Url::to(array_merge(['index'], array_merge($filter, ['grade' => $grade,'#'=>'scroll'])))) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['tm_ver']) && count(array_filter($results['tm_ver'])) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Teaching Material Version') ?>：</span>
                        <ul class="attr-value">
                            <?php foreach ($results['tm_ver'] as $tm_ver): ?>
                            <li><?= Html::a($tm_ver, Url::to(array_merge(['index'], array_merge($filter, ['tm_ver' => $tm_ver,'#'=>'scroll'])))) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!--过滤器-->
            <?= $this->render('/layouts/filter', ['filter' => $filter]) ?>
            <!--课程课件-->
            <div class="goods">
                <?php foreach ($results['courses'] as $index => $courses): ?>
                <div class="<?= ($index % 5 == 4 ) ? 'goods-item none' : 'goods-item'; ?>">
                    <?= Html::a('<div class="goods-img"></div>', ['view', 'id' => $courses['id']], ['title' => "【{$courses['unit']}】{$courses['cour_name']}" ]) ?>
                    <div class="goods-name course-name"><?= "【{$courses['unit']}】{$courses['cour_name']}" ?></div>
                    <div class="goods-see">
                        <i class="glyphicon glyphicon-play-circle"></i>
                        <span><?= $courses['play_count'] <= 99999 ? number_format($courses['play_count']) : substr(number_format((($courses['play_count']/10000)*10)/10, 4),0,-3).'万'; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!--分页-->
            <?= $this->render('/layouts/page', ['filter' => $filter, 'pages' => $pages]) ?>
        </div>
    </div>
</div>

<?php
$par_id = ArrayHelper::getValue($filter, 'par_id');
//$scroll = isset($filter['cat_id']) || isset($filter['sub_id']) 
//        || isset($filter['term']) || isset($filter['grade'])
//        || isset($filter['tm_ver']) || isset($filter['page']) ? 1 : 0;
$js = <<<JS
    var subjectArray = {4:"guangzhou"};        
    $(".index").addClass(subjectArray[$par_id]);
        
//    if(1) 
//        location.hash = "scroll"; 
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    StudyAsset::register($this);
?>