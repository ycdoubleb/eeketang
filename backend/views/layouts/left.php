<?php

use backend\modules\menu\assets\MenuBackendAsset;
use backend\modules\menu\models\MenuBackend;
use common\models\User;
use common\widgets\Menu;

/* @var $user User */

$menus = MenuBackend::getBackendMenu();
?>
<?php if($user!=null): ?>
<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= WEB_ROOT.$user->avatar?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->nickname ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?php
        $menuItems = [
            ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
            ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
        ];
        foreach ($menus as $items) {
            if(isset($items['items']) && count($items['items']) > 0)//{
                $menuItems[] = $items;  
        }
        echo Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $menuItems,
            ]
        ) ?>

    </section>

</aside>
<?php else : ?>
<div style="color: #fff;display: inline-block">
    <h3>用户注册！</h3>
</div>

<?php endif; ?>
<?php
    MenuBackendAsset::register($this);
?>