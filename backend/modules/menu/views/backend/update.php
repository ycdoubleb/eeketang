<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu\models\MenuBackend */

$this->title = Yii::t('app', '{Update} {modelClass}: ', [
    'Update' =>  Yii::t('app', 'Update'),
    'modelClass' =>  Yii::t('app', 'Menu Backend'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Backends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-backend-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
