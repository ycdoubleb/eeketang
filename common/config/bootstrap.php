<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('wskeee', dirname(__DIR__) . '/wskeee');
defined('FRONTEND_DIR') or define('FRONTEND_DIR',dirname(dirname(__DIR__)) . '/frontend');
defined('WEB_ROOT') or define('WEB_ROOT',defined('YII_ENV_TT') ? 'http://tt.eeketang.gzedu.com' :'http://gy.eeketang.gzedu.com');