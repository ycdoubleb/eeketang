<?php
return [
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
    ],
    'as access' => [
        'class' => 'frontend\components\AccessControl',
        'allowActions' => [
            'site/*',
            'gii/*',
            'debug/*',
            'site/unauthorized',    //未授权页面
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
];
