<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminLteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        //'http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css',
        'bundles/bootstrap/css/bootstrap.min.css',
        'bundles/adminlte/css/AdminLTE.min.css',
        'bundles/adminlte/css/skins/skin-blue.min.css',
        'css/admin.css',
    ];

    public $js = [
        'bundles/bootstrap/js/bootstrap.min.js',
        'bundles/adminlte/js/app.min.js',
        'js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset', //yii asset depends on jquery
    ];
}
