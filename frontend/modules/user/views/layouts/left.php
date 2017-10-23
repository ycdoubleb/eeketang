<?php

use frontend\modules\user\assets\UserAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

?>

<div class="user-left">
    <ul class="navigation">
        <?php
        /**
         * $menuItems = [
         *   [
         *      controllerId => 控制器ID,                          
         *      actionId => 行为方法ID,                          
         *      name  => 菜单名称，
         *      url  =>  菜单url，
         *      icon => 菜单图标
         *      options  => 菜单属性，
         *      symbol =>  html字符符号：&nbsp;，
         *      conditions  => 菜单显示条件，
         *      adminOptions  => 菜单管理选项，
         *   ],
         * ]
         */
        $controllerId = Yii::$app->controller->id;          //当前控制器
        $actionId = Yii::$app->controller->action->id;      //当前行为方法
        $selectClass = 'active';                            //选择样式
        $menuItems = [
            [
                'controllerId' => 'student',
                'actionId' => 'sync',
                'name' => '同步课堂',
                'url' => ['student/sync', 'cat_id' => 1],
                'icon' => '<i class="icon icon-1"></i>',
                'options' => ['class' => null],
                'symbol' => '<hr/>',
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'controllerId' => 'student',
                'actionId' => 'subject',
                'name' => '学科培优',
                'url' => ['student/subject', 'cat_id' => 2],
                'icon' => '<i class="icon icon-2"></i>',
                'options' => ['class' => null],
                'symbol' => '<hr/>',
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'controllerId' => 'student',
                'actionId' => 'diathesis',
                'name' => '素质提升',
                'url' => ['student/diathesis', 'cat_id' => 3],
                'icon' => '<i class="icon icon-3"></i>',
                'options' => ['class' => null],
                'symbol' => '<hr/>',
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'controllerId' => 'student',
                'actionId' => 'study',
                'name' => '学习轨迹',
                'url' => ['student/study'],
                'icon' => '<i class="icon icon-4"></i>',
                'options' => ['class' => null],
                'symbol' => '<hr/>',
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'controllerId' => 'student',
                'actionId' => 'favorites',
                'name' => '我的收藏',
                'url' => ['student/favorites'],
                'icon' => '<i class="icon icon-5"></i>',
                'options' => ['class' => null],
                'symbol' => null,
                'conditions' => true,
                'adminOptions' => null,
            ],
        ];
        
        foreach ($menuItems AS $item){
            $selected = is_array($item['actionId']) ? in_array($actionId, $item['actionId']) : $actionId == $item['actionId'];
            $active = $selected ? $selectClass : null;
            $symbol = $item['symbol'] != null ?  $item['symbol'] : null;
            echo "<li class=\"{$active}\">";
            echo Html::a($item['icon'].$item['name'], $item['url']);
            echo '</li>'.$symbol;
        }
        ?>
        
    </ul>
</div>

<?php
$js = <<<JS
    window.location.hash = "#student"; 
JS;
    //$this->registerJs($js, View::POS_READY);
?>

<?php
    UserAsset::register($this);
?>