<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.163.111;dbname=k12_tt',
            'username' => 'wskeee',
            'password' => '1234',
            'charset' => 'utf8',
            'enableSchemaCache'=>true,
            'tablePrefix' => 'k12_'   //加入前缀名称fc_
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'authManager'=>[
            'class'=>'wskeee\rbac\RbacManager',
            'cache' => [
                'class' => 'yii\caching\FileCache',
                'cachePath' => dirname(dirname(__DIR__)) . '/frontend/runtime/cache'
            ]
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'wskeee\rbac\Module',
        ],
    ],
    
    'aliases' => [
        '@filedata' => dirname(dirname(__DIR__)) . '/frontend/web/filedata',
    ],
];
