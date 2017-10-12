<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\modules\study\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StudyAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/study/assets';
    public $css = [
        'css/style.css',
        'css/index.css',
        'css/view.css',
        'css/timer.css',
        'css/error.css',
    ];
    public $js = [
        'js/view.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
