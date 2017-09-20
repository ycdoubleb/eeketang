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

$moduleId = Yii::$app->controller->module->id;   //模块ID
$menus = Menu::getMenusNavItem(Menu::POSITION_FRONTEND);
$link = Url::to(['index', 'parent_cat_id' => ArrayHelper::getValue(Yii::$app->request->queryParams, 'parent_cat_id')]);
?>

<?php 
    NavBar::begin([
        //'brandLabel' => 'My Company',
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);  
    $menuItems = [['label' => '首页', 'url' => ['/site/index']]];
    
    foreach ($menus as $item) {        
        if($item['url'][0] == $link)
            $item['options'] = ['class' => 'active'];
        $menuItems[] = $item;
    }
    
    $menuItems[] = [
        'label' => Html::img(['/filedata/site/image/feedback.png']), 
        'url' => '',
        'options' => ['class' => 'navbar-right'],
        'linkOptions' => ['class' => 'feedback', 'title' => '反馈信息']
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left container'],
        'encodeLabels' => false,
        'items' => $menuItems,
        'activateParents' => true,
        //'route' => $route,
    ]);
    
    NavBar::end();
?>