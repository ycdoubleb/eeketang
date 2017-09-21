<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        
    ],
    'modules' => [
        'test' => [
            'class' => 'frontend\modules\test\Module',
        ],
        'study' => [
            'class' => 'frontend\modules\study\Module',
        ],
        'user' => [
            'class' => 'frontend\modules\user\Module',
        ],
    ],
    'as access' => [
        'class' => 'frontend\components\AccessControl',
        'allowActions' => [
            'gii/*',
            'debug/*',
            'site/unauthorized',    //未授权页面
            'user/*',   
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
