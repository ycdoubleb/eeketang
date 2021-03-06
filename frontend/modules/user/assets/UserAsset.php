<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\modules\user\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/user/assets';
    public $css = [
        'css/style.css',
        'css/default.css',
        'css/info.css',
        'css/wrapper.css',
        'css/jquery.powertip.css',
    ];
    public $js = [
        //'js/jquery.jscroll.js',
        'js/jquery.powertip.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
