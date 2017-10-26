<?php

namespace frontend\assets;

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
class CollegeAsset extends AssetBundle {

    public $sourcePath = '@frontend/assets';
    public $css = [
//        'css/banner.css',
        'css/index.css',
    ];
    public $js = [
//        'js/myCarousel.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

}
