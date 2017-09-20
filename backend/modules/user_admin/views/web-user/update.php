<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WebUser */
$this->title = Yii::t(null, '{Update} {Web User}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Web User' => Yii::t('app', 'Web User'),
        ]) . $model->real_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Web Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="web-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
