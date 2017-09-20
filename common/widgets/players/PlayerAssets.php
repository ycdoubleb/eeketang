<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\players;

use yii\web\AssetBundle;

/**
 * Description of TreegridAssets
 *
 * @author Administrator
 */
class PlayerAssets extends AssetBundle{
    public $sourcePath = '@common/widgets/players';
    public $css = [
       
    ];
    public $js = [
        'js/coursedata.js',
        'js/mainInterface.js',
        'js/swfobject.js',
        'js/toolsInterface.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
