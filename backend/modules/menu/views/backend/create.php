<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\menu\models\MenuBackend */

$this->title = Yii::t('app', 'Create Menu Backend');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Backends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-backend-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
