<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'name' => 'MyApp',
    'id' => 'back-'.APP_ID,
    'language' => 'en',
    'timeZone' => 'Europe/Madrid',
    'basePath' => dirname(__DIR__),
    'viewPath' => '@app/views/admin',
    'controllerNamespace' => 'app\controllers\admin',
    'bootstrap' => ['log'],
    'components' => [
        'session' => [
            'name' => 'session-back-'.APP_ID,
            // 'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],
        'request' => [
            'cookieValidationKey' => COOKIE_VALIDATION_KEY.'-back-'.APP_ID,
            'enableCsrfValidation' => true,
            'csrfParam' => '_csrf-back-'.APP_ID,
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\admin\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-back-'.APP_ID,
                'httpOnly' => true,
            ],
            //'on afterLogin' => ['app\models\admin\Admin', 'afterLogin'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            'rules' => [
                '' => 'site/index',
                '<controller:[a-z0-9_-]+>/?' => '<controller>/index',
                '<controller:[a-z0-9_-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[a-z0-9_-]+>/<action:[a-z0-9_-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[a-z0-9_-]+>/<action:[a-z0-9_-]+>' => '<controller>/<action>',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 1 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/backend.log',
                    'levels' => ['error', 'warning'],
                    /*'categories' => [
                        'yii\db\*',
                        'yii\web\HttpException:*',
                    ],*/
                    'except' => YII_DEBUG ? [] : ['yii\web\HttpException:404',],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/backend-info.log',
                    'levels' => ['info'],
                    'categories' => [ 'app\*'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.DB_HOST.';dbname='.DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'utf8',
            // 'enableSchemaCache' => true,
            // 'schemaCacheDuration' => 3600,
            // 'schemaCache' => 'cache',
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
    ],
    'params' => array_merge([
        'admin.root' => 1, //root user id from admin table
        'admin.token_expire' => 3600,
        'admin.page.size' => 20,
        'admin.page.sizes' => [20, 40, 60, 80, 100, 200],
    ], $params),
];


Yii::$container->set('yii\grid\GridView', [
    'tableOptions' => [
        'class' => 'table table-bordered table-striped ',
    ],
    'layout' => "{summary}\n{items}\n<div class=\"text-center\">{pager}</div>",
    'summaryOptions' => ['tag' => 'p', 'class' => 'summary text-right'],
    //'showFooter' => true,
]);


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'customCrud' => [
                'class' => 'app\gii\crud\Generator',
                'templates' => [
                    'default' => '@app/gii/crud/xenon',
                ]
            ],
            'customModel' => [
                'class' => 'app\gii\model\Generator',
                'templates' => [
                    'default' => '@app/gii/model/default',
                ]
            ]
        ],
    ];
}

return $config;
