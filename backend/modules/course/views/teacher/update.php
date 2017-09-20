<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = Yii::t('app', '{Update} {Teacher}：', [
    'Update' => Yii::t('app','Update'),
    'Teacher' => Yii::t('app','Teacher'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="teacher-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
