<?php

use common\models\WebUser;
use frontend\modules\study\assets\LayoutsAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

//$this->title = Yii::t('app', 'My Yii Application');

?>

<div id="filter" class="filter">
    <?php 
        /**
        * $menuItems = [
        *   [
        *      controllerId => 控制器ID,                          
        *      name  => 菜单名称，
        *      url  =>  菜单url，
        *      icon => 菜单图标
        *      options  => 菜单属性，
        *      symbol => html字符符号：&nbsp;，
        *      conditions  => 菜单显示条件，
        *   ],
        * ]
        */
        $controllerId = Yii::$app->controller->id;          //当前控制器
        $actionId = Yii::$app->controller->action->id;      //当前行为方法
        $requestUrl = Yii::$app->request->url;
        $menuItems = [
            [
                'controllerId' => ['choice', 'college'],
                'name' => '默认',
                'url' => array_merge([$actionId], array_merge($filter, ['sort_order' => 'sort_order','#'=> 'scroll'])),
                'icon' => '<i class="icon icon-4"></i>',
                'options' => ['class' => 'active'],
                'symbol' => '&nbsp;',
                'conditions' => true,
            ],
            [
                'controllerId' => 'college',
                'name' => '播放最多',
                'url' => array_merge([$actionId], array_merge($filter, ['sort_order' => 'play_count','#'=> 'scroll'])),
                'icon' => null,
                'options' => ['class' => 'active'],
                'symbol' => '&nbsp;',
                'conditions' => Yii::$app->user->identity->role==WebUser::ROLE_STUDENT || $controllerId == 'college',
            ],
            
        ];
        
        foreach ($menuItems as $index => $item){
            $active = $requestUrl == str_replace('#scroll', '', Url::to($item['url'])) || (!isset($filter['sort_order']) && $index == 0) ? $item['options'] : [];
            if($item['conditions']){
                echo '<div class="sort-order">'.
                    Html::a($item['icon'].$item['name'],$item['url'],$active)
                .'</div>';
            }
        }
       
    ?>
    
    <?php if(Yii::$app->user->identity->role==WebUser::ROLE_TEACHER && $controllerId == 'choice'): ?>
    <div class="filter-choice">
        <?= Html::checkbox('checkedres').Html::label('全选') ?>
        <?= Html::a(Yii::t('app', 'Submit'), 'javascript:;', ['id' => 'submitbox','class' => 'btn btn-primary btn-sm']) ?>
    </div>
    <?php endif; ?>
    
</div>
<?php
$js = <<<JS

   
        
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php 
    LayoutsAsset::register($this);
?>