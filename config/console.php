<?php

Yii::setAlias('@webroot', dirname(__DIR__) . '/web');
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');

return [
    'id' => 'console-app',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Europe/Madrid',
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'logFile' => '@runtime/logs/console.log',
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/console-info.log',
                    'levels' => ['info'],
                    'categories' => [ 'app\*'],
                    'logVars' => [],
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
    ],
    'params' => $params,
];
