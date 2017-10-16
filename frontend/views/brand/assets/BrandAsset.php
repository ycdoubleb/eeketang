<?php

namespace frontend\views\brand\assets;

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
class BrandAsset extends AssetBundle {

    public $sourcePath = '@frontend/views/brand/assets';
    public $css = [
        'css/jquery.fullPage.css',
        'css/brand.css',
    ];
    public $js = [
        'js/jquery.fullPage.js',
        'js/animate.js',
        'js/distpicker.data.js',
        'js/distpicker.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

}
