<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

class ImageController extends Controller
{
    public $echo = 0;
    public $debug = 0;
    public $remove = 0;

    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID)
            , ['echo', 'debug'] // global for all actions
            , ($actionID == 'recreate_thumbs') ? ['remove'] : []
        );
    }

    protected function log($message)
    {
        if ($this->echo) {
            echo $message."\n";
        } else {
            Yii::info($message, __METHOD__);
        }
    }

    public function actionRemove_thumbs($modelName, $imageField)
    {
        $finder = $this->getFinder($modelName);
        $this->log("Removing thumbs for $modelName");
        foreach ($finder->all() as $model) {
            if ($this->debug) $this->log($model->id.' - '.$model->getLabel());
            $model->removeImageSizes($imageField);
        }
    }

    public function actionRecreate_thumbs($modelName, $imageField)
    {
        $finder = $this->getFinder($modelName);
        $this->log("Recreating thumbs for $modelName");
        foreach ($finder->all() as $model) {
            if ($this->debug) $this->log($model->id.' - '.$model->getLabel());
            if ($this->remove) $model->removeImageSizes($imageField);
            $model->createImageSizes($imageField);
        }
    }

    private function getFinder($modelName)
    {
        $modelName = ucfirst($modelName);
        $modelName = "\\app\models\\".$modelName;
        if (!class_exists($modelName)) {
            throw new \yii\base\Exception("model not found: $modelName");
        }

        $model = new $modelName();
        if (!$model->hasMethod('createImageSizes')) {
            throw new \yii\base\Exception("model must have attached behavior \app\components\behaviors\ImageUploadBehavior");
        }

        return $modelName::find();
    }
}
