<?php

namespace app\components\behaviors;

use Yii;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;

/**
 * Class ImageUploadBehavior
 *
 * @property ActiveRecord $owner
 */
class ImageUploadBehavior extends FileUploadBehavior
{


    /**
     * @var array attribute configuration, indexed by the fields that will hold the uploaded file names.
     * [
     *   'my_img' => [
     *     'upload' => 'myImgUpload', // model attribute that will hold the uploaded file. This is the only mandatory field.
     *     'remove' => 'myImgRemove', // model attribute (boolean) that will be checked for removing the current file
     *     'extra' => [
     *          'my_img_size' => function ($fileInfo) { return $fileInfo['size']; },
     *          'my_img_type' => function ($fileInfo) { return $fileInfo['type']; },
     *          'my_img_width' => function ($fileInfo) { return $fileInfo['width']; },
     *          'my_img_height' => function ($fileInfo) { return $fileInfo['height']; },
     *      ],
     *   ],
     *   'my_other_img' => ...
     * ]
     */
    public $config = [
        'img' => ['upload' => 'imgUpload', 'remove' => 'imgRemove'],
    ];

    public $resizeConfig = [
        'img' => [
            'thumb' => [
                'suffix' => '-t',
                'w' => 150,
                'h' => 150,
                'method' => 'crop', // or 'fit'
                'quality' => 85,
            ],
        ],
    ];

    public $defaultJpegQuality = 80;


     /**
     * @inheritdoc
     */
    public function events()
    {
        return ArrayHelper::merge(parent::events(), [
            static::EVENT_AFTER_FILE_SAVE => 'afterFileSave',
            static::EVENT_AFTER_FILE_DELETE => 'afterFileDelete',
        ]);
    }

    public function attach($owner)
    {
        parent::attach($owner);

        if (!$this->resizeConfig) $this->resizeConfig = [];
    }


    private static function addSuffix($filename, $suffix)
    {
        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return $filename.$suffix;
        }
        return substr($filename, 0, $pos).$suffix.substr($filename, $pos);
    }

    public function getImageName($size = false, $imgAttribute = null)
    {
        if (!$imgAttribute) $imgAttribute = $this->defaultFileAttribute;
        $name = $this->owner->$imgAttribute;
        if ($size) {
            $config = $this->resizeConfig;
            $suffix = ArrayHelper::getValue($config, "{$imgAttribute}.{$size}.suffix", "-{$size}");
            $name = static::addSuffix($name, $suffix);
        }
        return $name;
    }

    public function getImagePath($size = false, $imgAttribute = null)
    {
        if (!$imgAttribute) $imgAttribute = $this->defaultFileAttribute;
        $name = $this->getImageName($size, $imgAttribute);
        return $this->getBaseFilePath().'/'.$name;
    }

    public function getImageUrl($size = false, $imgAttribute = null, $scheme = false)
    {
        if (!$imgAttribute) $imgAttribute = $this->defaultFileAttribute;
        $name = $this->getImageName($size, $imgAttribute);
        $route = $this->baseUrl.'/'. $this->getDir().'/'.$name;
        return Url::to($route, $scheme);
    }

    protected function onFileSaved($fileAttribute, $fileInfo)
    {
        if (false !== ($a = getimagesize($fileInfo['path']))) {
            $fileInfo['width'] = $a[0];
            $fileInfo['height'] = $a[1];
        }
        $e = new FileUploadEvent();
        $e->attribute = $fileAttribute;
        $e->fileInfo = $fileInfo;
        $this->owner->trigger(static::EVENT_AFTER_FILE_SAVE, $e);
    }

    public function afterFileSave($event)
    {
        parent::afterFileSave($event);
        $fileAttribute = $event->attribute;
        $fileInfo = $event->fileInfo;

        $w = ArrayHelper::getValue($fileInfo, 'width');
        $h = ArrayHelper::getValue($fileInfo, 'height');
        if (!$w || !$h) {
            Yii::warning('Not an image: '.$fileInfo['path'], __METHOD__);
            return;
        }
        $this->createSizes($fileAttribute, $fileInfo['path']);
    }

    public function createSizes($fileAttribute, $fromPath = null)
    {
        if (!isset($this->resizeConfig[$fileAttribute])) return;

        if (null === $fromPath) { // not called from afterFileSave, but externally
            $fromPath = $this->getFilePath($fileAttribute);
        }

        foreach ($this->resizeConfig[$fileAttribute] as $size => $specs) {
            $this->createResizedImage($fromPath, $size, $specs);
        }
    }

    public function createResizedImage($path, $name, $specs)
    {
        $img = Image::getImagine()->open($path);
        $size = $img->getSize();

        $w = ArrayHelper::getValue($specs, 'w');
        $h = ArrayHelper::getValue($specs, 'h');

        if (!$w && !$h) return;

        if (!$w) {
            $newSize = $size->heighten($h);
        } elseif (!$h) {
            $newSize = $size->widen($w);
        } else {
            $newSize = new Box($w, $h);
        }

        $mode = 'fit' == ArrayHelper::getValue($specs, 'method', 'crop') ? ManipulatorInterface::THUMBNAIL_INSET : ManipulatorInterface::THUMBNAIL_OUTBOUND;

        $options = ['jpeg_quality' => ArrayHelper::getValue($specs, 'quality', $this->defaultJpegQuality)];

        $suffix = ArrayHelper::getValue($specs, 'suffix', "-{$size}");
        $newPath = static::addSuffix($path, $suffix);

        $img->thumbnail($newSize, $mode)->save($newPath, $options);

    }

    public function deleteSizes($fileAttribute)
    {
        if (!isset($this->resizeConfig[$fileAttribute])) return;

        foreach ($this->resizeConfig[$fileAttribute] as $size => $specs) {
           $path = $this->getImagePath($size, $fileAttribute);
           if (file_exists($path)) {
               unlink($path);
           }
        }
    }

    public function afterFileDelete($event)
    {
        $fileAttribute = $event->attribute;
        $this->deleteSizes($fileAttribute);
        parent::afterFileDelete($event);
    }

}
