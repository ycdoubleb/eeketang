<?php

namespace frontend\views;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class GatewayAsset extends AssetBundle {

    public $sourcePath = '@frontend/views/assets';
    public $css = [
        'css/banner.css',
        'css/index.css',
    ];
    public $js = [
        'js/banner.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

}
