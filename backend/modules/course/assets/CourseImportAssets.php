<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\course\assets;

use yii\web\AssetBundle;

/**
 * Description of TreegridAssets
 *
 * @author Administrator
 */
class CourseImportAssets extends AssetBundle{
    public $sourcePath = '@backend/modules/course/assets';
    public $css = [
       
    ];
    public $js = [
        'js/import.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
