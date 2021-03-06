<?php

use common\models\course\Course;
use frontend\modules\study\assets\StudyAsset;
use wskeee\utils\ArrayUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */

$this->title = Yii::t('app', 'My Yii Application');

?>

<div class="study-choice-index study-index has-title choice">
    
    <div class="container"><h4 class="choice-title">观课中心</h4></div>
    <div class="search" id="scroll">
        <div class="container">
            <div class="search-inner">
                <div class="search-input">
                    <?php $form = ActiveForm::begin([
                        'id' => 'search-form',
                        'action' => array_merge(['search'], $filter),
                        'method' => 'get'
                    ]); ?>
                    <?= Html::textInput('keyword', ArrayHelper::getValue($filter, 'keyword'), ['class' => 'form-control', 'placeholder' => '请输入你想搜索的关键词']) ?>
                    <?php ActiveForm::end() ?>
                </div>
                <div id="submit-search" class="search-button"><i class="icon icon-1"></i></div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <!--面包屑-->
            <div class="crumb">
                <ul class="crumb-nav">
                    <li><span>筛选条件：</span><b><?= $parModel->name ?></b></li>
                    <?php foreach ($filterItem as $filter_key => $item): ?>
                    <li>
                        <i class="arrow">&gt;</i>
                        <?= Html::a("<b>{$filter_key}:</b><em>{$item['filter_value']}</em><i>×</i>", [$item['url'],'#'=>'scroll']) ?>
                    </li>
                    <?php endforeach; ?>
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
                            <li>
                                <?= Html::a($category['name'],
                                        Url::to(array_merge(['index'], 
                                            array_merge($filter, ['cat_id' => $category['id'],'page'=>1,'#'=>'scroll'])))) 
                                ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['sub_id']) && count($results['subject']) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Subject') ?>：</span>
                        <ul class="attr-value">
                            <?php foreach ($results['subject'] as $subject): ?>
                            <li>
                                <?= Html::a($subject['name'],
                                        Url::to(array_merge(['index'], 
                                           array_merge($filter, ['sub_id' => $subject['id'],'page'=>1,'#'=>'scroll'])))) 
                                ?>
                            </li>
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
                            <li>
                                <?= Html::a(Course::$term_keys[$term],
                                        Url::to(array_merge(['index'],
                                           array_merge($filter, ['term' => $term,'page'=>1,'#'=>'scroll'])))) 
                                ?>
                            </li>
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
                            <li>
                                <?= Html::a(Course::$grade_keys[$grade],
                                    Url::to(array_merge(['index'], 
                                        array_merge($filter, ['grade' => $grade,'page'=>1,'#'=>'scroll'])))) 
                                ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(!isset($filter['tm_ver']) && count(array_filter($results['tm_ver'])) > 1): ?>
                    <li>
                        <span class="attr-key"><?= Yii::t('app', 'Teaching Material Version') ?>：</span>
                        <ul class="attr-value">
                            <?php foreach ($results['tm_ver'] as $tm_ver): ?>
                            <li>
                                <?= Html::a($tm_ver, 
                                        Url::to(array_merge(['index'], 
                                            array_merge($filter, ['tm_ver' => $tm_ver,'page'=>1,'#'=>'scroll'])))) 
                                ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php foreach ($results['attrs'] as $attr_name => $attr_arr): ?>
                    <?php if(count($attr_arr['value']) > 1): ?>
                    <li>
                        <span class="attr-key"><?= $attr_name ?>：</span>
                        <ul class="attr-value">
                            <?php $attr_arr['value'] = ArrayUtil::sortCN($attr_arr['value']);?>
                            <?php foreach ($attr_arr['value'] as $attr_label): ?>
                            <li>
                                <?php  
                                    //合并之前已选择的属性过滤条件
                                    $attrs = array_merge(isset($filter['attrs']) ? $filter['attrs'] : [] , [['attr_id' => $attr_arr['attr_id'], 'attr_value' => $attr_label]]);
                                    //过滤之前已选择过滤条件
                                    $params = array_merge($filter,['attrs' => $attrs, 'page'=>1,'#'=>'scroll']);
                                    echo Html::a($attr_label, Url::to(array_merge(['index'], $params)))
                                
                                ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!--过滤器-->
            <?= $this->render('/layouts/filter', ['filter' => $filter]) ?>
            <!--课程课件-->
            <div class="goods">
                <?php if(count($results['courses']) <= 0): ?>
                <div class="error-404"></div>
                <?php endif; ?>
                <?php foreach ($results['courses'] as $index => $courses): ?>
                <div class="<?= ($index % 5 == 4 ) ? 'goods-list none' : 'goods-list'; ?>">
                    <div class="goods-pic" style="background-color:<?= Course::$backgroundColor[$courses['id']%count(Course::$backgroundColor)] ?>">
                        <a href="<?= Url::to(['college/view', 'id' => $courses['id']]) ?>" title="<?= $courses['cour_name'] ?>">    
                            <?= Html::img([$courses['sub_img']]) ?>
                            <?= Html::img([$courses['tea_img']], ['class' => 'course-teacher']) ?>
                            <?= Html::img(["/filedata/course/tm_logo/{$tm_logo[$courses['tm_ver']]}.png"], ['class' => 'tm-ver-logo']) ?>
                            <div class="course-title">
                                <?= Course::$grade_keys[$courses['grade']].$courses['attr_values'].Course::$term_keys[$courses['term']].$courses['unit'] ?>
                            </div>
                            <div class="course-line-clamp course-lable"><?= $courses['cour_name'] ?></div>
                            <?php if($courses['is_choice']): ?>
                            <i class="icon icon-3"></i>
                            <?php else: ?>
                            <?= Html::checkbox('course_id',false,['class'=>'selected','value'=>$courses['id']]) ?>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="goods-name course-name"><?= $courses['cour_name'] ?></div>
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
//$par_id = ArrayHelper::getValue($filter, 'par_id');

$url = Yii::$app->request->url;
$js = <<<JS
    /*var subjectArray = {4:"guangzhou",5:"yangguan",6:"renjiao",7:"yingyu",8:"shuxue",
        9:"zuowen",10:"weiqi",11:"xiangqi",12:"huihua",13:"jiyou",14:"wenti",15:"shougong",
        16:"kexue",17:"tiyu"};        
    $(".study-index").addClass(subjectArray[]);*/
    //单击提交表单
    $('#submit-search').click(function(){
        $('#search-form').submit();
    });
    //checkbox全选、全不选
    $("input[type='checkbox'][name='checkedres']").click(function(){
        if($("input[type='checkbox'][name='checkedres']").prop("checked"))
            $("input[type='checkbox'][name='course_id']").prop("checked",true);
        else
            $("input[type='checkbox'][name='course_id']").prop("checked",false); 
    });
    //单击提交checkbox数据
    $("#submitbox").click(function(){
        var couserIds = $("input[type='checkbox'][name='course_id']").serialize();
        $.post("/study/choice/save",{"course_id":couserIds},function(data){
            if(data['code'] == 200){
                $("body").load("$url");
            }
        });
    });
    /** 判断当前页的课程是否是全选 */
    if($(".goods-pic").find("input").length<=0){
        $("input[type='checkbox'][name='checkedres']").attr("disabled", "disabled");
        $("#submitbox").addClass("disabled");
    }

JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    StudyAsset::register($this);
?>
