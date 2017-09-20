<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\course\Subject */

$this->title = Yii::t('app', Yii::t(null, '{Create}{Subject}', [
                            'Create' => Yii::t('app', 'Create'),
                            'Subject' => Yii::t('app', 'Subject'),
        ]));
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Subject}', [
        'Subject' => Yii::t('app', 'Subject'),
    ]), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
