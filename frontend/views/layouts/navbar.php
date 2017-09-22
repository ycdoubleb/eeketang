<?php

use common\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $content string */

//$moduleId = Yii::$app->controller->module->id;   //模块ID
//$menus = Menu::getMenusNavItem(Menu::POSITION_FRONTEND);
//$link = Url::to(['index', 'parent_cat_id' => ArrayHelper::getValue(Yii::$app->request->queryParams, 'parent_cat_id')]);
?>

<?php

NavBar::begin([
    //'brandLabel' => 'My Company',
    //'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);

if (Yii::$app->user->isGuest) {
    //$menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => Yii::t('app', 'Student Login'), 'url' => ['/site/login']];
    $menuItems[] = ['label' => Yii::t('app', 'Teacher Login'), 'url' => ['/site/#']];
} else {
    foreach ($menus as $item) {
        $menuItems[] = $item;
    }
    $menuItems[] = [
        'label' => Html::img([Yii::$app->user->identity->avatar], [
            'width' => 28, 
            'height' => 28, 
            'class' => 'img-circle', 
            'style' => 'margin-right: 5px;'
        ]).Yii::$app->user->identity->username,
        //'url' => ['/user/default/index'],
        'items' => [
            ['label' => '年级：'.Yii::$app->user->identity],
            ['label' => '班级：12班'],
            [
                'label' => '<i class="fa fa-sign-out"></i>'.Yii::t('app', 'Logout'), 
                'url' => ['/site/logout'],
                'linkOptions' => [
                    'data-method' => 'post',
                    'class' => 'logout',
                    'style' => 'text-align: right;'
                ]
            ],
        ],
    ];
}

$label = ArrayHelper::getValue($items, 'label');
echo Html::beginTag('div', ['class' => 'pull-left']);
echo $label;
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'pull-right']);
echo Nav::widget([      
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $menuItems,
    'activateParents' => true,
        //'route' => $route,
]);
echo Html::endTag('div');

NavBar::end();
?>

<?php
$url = Url::to(['/user/default/index'], true);
$js = <<<JS
    $(".navbar-nav .dropdown>a,.navbar-nav .dropdown>.dropdown-menu").hover(function(){
        $(this).parent("li").addClass("open");
    }, function(){
        $(this).parent("li").removeClass("open");
    });
        
    $(".navbar-nav .dropdown>a").click(function(){
        location.href = "{$url}";
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>