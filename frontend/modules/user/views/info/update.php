<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WebUser */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Web User',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Web Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="web-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
