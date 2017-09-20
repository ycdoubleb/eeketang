<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WebUser */

$this->title = Yii::t('app', Yii::t(null, '{Create}{Web User}',[
                'Create' => Yii::t('app', 'Create'),
                'Web User' => Yii::t('app', 'Web User'),
            ]));
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Web Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
