<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Note */

$this->title = Html::a(Yii::t('app', Yii::t(null, '{Create}{Note}', [
                            'Create' => Yii::t('app', 'Create'),
                            'Note' => Yii::t('app', 'Note'),
        ])));
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Note}', [
        'Note' => Yii::t('app', 'Note'),
    ]), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
