<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = Yii::t(null, '{Create}{Teachers}', [
            'Create' => Yii::t('app', 'Create'),
            'Teachers' => Yii::t('app', 'Teachers'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
