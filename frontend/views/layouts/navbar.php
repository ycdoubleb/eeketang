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
        'label' => '<img src = "/filedata/avatars/women19.jpg">', //'退出' 'Logout (' . Yii::$app->user->identity->username . ')',
//            'url' => ['/site/logout'],
//            'linkOptions' => ['data-method' => 'post']
        'items' => [
            ['label' => '我的属性', 'url' => '/site/reset-info', 'linkOptions' => ['class' => 'glyphicon glyphicon-user', 'style' => 'padding-left:20px;']],
            ['label' => '<i class="fa fa-sign-out"></i> 退出', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
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