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
class SiteAsset extends AssetBundle {

    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@frontend/assets';
    public $css = [
        'css/login.css',
    ];
    public $js = [
//        'js/shakeobj.js',
//        'js/numberturn.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

}
