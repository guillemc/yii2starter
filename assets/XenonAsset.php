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
class XenonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://fonts.googleapis.com/css?family=Arimo:400,700,400italic',
        'bundles/xenon/css/fonts/linecons/css/linecons.css',
        'bundles/xenon/css/fonts/fontawesome/css/font-awesome.min.css',
        'bundles/xenon/css/bootstrap.css',
        'bundles/xenon/css/xenon.css',
        'css/admin.css',
    ];
    public $js = [
        'bundles/xenon/js/bootstrap.min.js',
        'bundles/xenon/js/TweenMax.min.js',
        'bundles/xenon/js/resizeable.js',
        'bundles/xenon/js/joinable.js',
        'bundles/xenon/js/xenon-api.js',
        'bundles/xenon/js/xenon-toggles.js',
        'bundles/xenon/js/xenon-custom.js',
        'js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset', //yii asset depends on jquery
    ];
}
