<?php

$user = Yii::$app->user->identity; /* @var $user \app\models\admin\Admin */

return [
    ['label' => Yii::t('admin', 'Home'), 'i' => 'fa fa-home', 'url' => ['site/index'], 'match' => 'action'],
    ['label' => Yii::t('admin', 'Users'), 'i' => 'fa fa-user', 'url' => ['user/index'], 'match' => 'controller'],
    ['label' => Yii::t('admin', 'Help'), 'i' => 'fa fa-info-circle', 'url' => '#', 'items' => [
        ['label' => Yii::t('admin', 'Debug info'), 'i' => 'fa fa-info', 'url' => ['site/about'], 'match' => 'action']
    ], 'visible' => $user->isRoot()],
];