<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t(null, '{Update} {modelClass}: ', [
    'Update' =>  Yii::t('app', 'Update'),
    'modelClass' =>  Yii::t('app', 'Menus Frontend'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus Frontend'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
