<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
    'modules' => [
        'course' => [
            'class' => 'backend\modules\course\Module',
        ],
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
    ],
    'as access' => [
        'class' => 'wskeee\rbac\components\AccessControl',
        'allowActions' => [
            'site/*',
            'rbac/*',
            'gii/*',
            'debug/*',
            'user/*',
            'study/*',
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
];
