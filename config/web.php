<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => [
        'log',
        \app\components\NotificationComponent::class,
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_EwRIP6oHHGYRCi6ufQbL__9DRcxrkQF',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'activation/<token:[\w]+>'                             => 'site/activation',
                'view/<id:[\d]+>'                                      => 'site/view',
                '<action[login|logout|registration]+>'                 => 'site/<action>',
                '<module[\w-]+>'                                       => '<module>/index',
                '<module[\w-]+>/<action(login|logout)>'                => '<module>/index/<action>',
                '<module[\w-]+>/<controller[\w-]+>'                    => '<module>/<controller>/index',
                '<module[\w-]+>/<controller>/<action:(create|update)>' => '<module>/<controller>/save',
                '<controller[\w-]+>/<action[\w-]+>'                    => '<controller>/<action>',
                '<action[\w-]+>'                                       => 'site/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache' //Включаем кеширование
        ],
        'notification' => [
            'class' =>  \app\components\NotificationComponent::class
        ],
    ],
    'modules' => [
        'administration' => [
            'class' => 'app\modules\backend\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
