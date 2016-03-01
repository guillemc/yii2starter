<?php

/**
 * @copyright Copyright (c) 2014 Newerton Vargas de Araújo
 * @link http://newerton.com.br
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package yii2-fancybox
 * @version 1.0.0
 */

namespace app\components\widgets;

use yii\base\Widget;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

/**
 * fancyBox is a tool that offers a nice and elegant way to add zooming
 * functionality for images, html content and multi-media on your webpages
 *
 * @author Newerton Vargas de Araújo <contato@newerton.com.br>
 * @since 1.0
 */
class FancyBox extends Widget {

    /**
     * @var type string target of the widget
     */
    public $target = 'a[rel=fancybox]';

    // @ array of config settings for fancybox
    /**
     *
     * @var type array of config settings for fancybox
     */
    public $config = [];

    /**
     * @var array to use for $.fancybox.open( [group], [options] ) scenario
     */
    public $group = [];

    /**
     * @inheritdoc
     */
    public function init() {
        if (is_null($this->target)) {
            throw new InvalidConfigException('"FancyBox.target has not been defined.');
        }
        // publish the required assets
        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    public function run() {
        $view = $this->getView();

        $config = Json::encode($this->config);

        if(!empty($this->group)){
            $config = Json::encode($this->group).','.$config;
        }
        $view->registerJs("jQuery('{$this->target}').fancybox({$config});");
    }

    /**
     * Registers required script for the plugin to work as DatePicker
     */
    public function registerClientScript() {
        $view = $this->getView();

        \app\assets\FancyBoxAsset::register($view);
    }

}
