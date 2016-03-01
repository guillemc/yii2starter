<?php
/**
 * @copyright Copyright (c) 2014 Newerton Vargas de Araújo
 * @link http://newerton.com.br
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace app\assets;

use yii\web\AssetBundle;

class FancyBoxAsset extends AssetBundle
{


    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'bundles/fancybox/jquery.fancybox.css',
    ];

    public $js = [
        'bundles/fancybox/jquery.fancybox.js',
        'bundles/fancybox/jquery.mousewheel.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
