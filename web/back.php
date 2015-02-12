<?php

require(__DIR__ . '/../.env.php');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/backend.php');
$localFile = __DIR__ . '/../config/backend-local.php';
if (file_exists($localFile)) {
    $config = yii\helpers\ArrayHelper::merge(
        $config,
        require($localFile)
    );
}

(new yii\web\Application($config))->run();
