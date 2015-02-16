<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'name' => 'MyApp',
    'id' => APP_ID,
    'language' => 'en',
    'timeZone' => 'Europe/Madrid',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'session' => [
            'name' => 'session-'.APP_ID,
            // 'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],
        'request' => [
            'cookieValidationKey' => COOKIE_VALIDATION_KEY.'-'.APP_ID,
            'enableCsrfValidation' => true,
            'csrfParam' => '_csrf-'.APP_ID,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '' => 'site/index',
                '<controller:[a-z0-9_-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[a-z0-9_-]+>/<action:[a-z0-9_-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[a-z0-9_-]+>/<action:[a-z0-9_-]+>' => '<controller>/<action>',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-'.APP_ID,
                'httpOnly' => true,
            ],
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
                    'logFile' => '@runtime/logs/frontend.log',
                    'levels' => ['error', 'warning'],
                    /*'categories' => [
                        'yii\db\*',
                        'yii\web\HttpException:*',
                    ],*/
                    'except' => YII_DEBUG ? [] : ['yii\web\HttpException:404',],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/frontend-info.log',
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
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
}

return $config;
