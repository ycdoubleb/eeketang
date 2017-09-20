<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Note */
$this->title = Yii::t(null, '{Update} {Note}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Note' => Yii::t('app', 'Note'),
        ]) . $model->content;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="note-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
