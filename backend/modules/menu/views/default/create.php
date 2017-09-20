<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t(null, '{Create}{Menus}', [
    'Create' => Yii::t('app', 'Create'),
    'Menus' => Yii::t('app', 'Menus'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus Frontend'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents,
    ]) ?>

</div>
