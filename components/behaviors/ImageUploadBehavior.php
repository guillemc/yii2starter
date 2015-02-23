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
     * The values specify related fields or attributes. At least the 'upload' attribute should be specified. Example:
     * [
     *   'my_img' => [
     *     'upload' => 'myImgUpload', // model attribute that will hold the uploaded file
     *     'remove' => 'myImgRemove', // model attribute (boolean) that will be checked for removing the current file
     *     'type' => 'my_img_type', // model attribute / table field for storing the file type
     *     'size' => 'my_img_size', // model attribute / table field for storing the file size
     *     'w' => 'my_img_width',  // model attribute / table field for storing the image width
     *     'h' => 'my_img_height',  // model attribute / table field for storing the image height
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

    public function getImageName($size = false, $imgAttr = null)
    {
        if (!$imgAttr) $imgAttr = $this->defaultFileAttribute;
        $name = $this->owner->$imgAttr;
        if ($size) {
            $config = $this->resizeConfig;
            $suffix = ArrayHelper::getValue($config, "{$imgAttr}.{$size}.suffix", "-{$size}");
            $name = static::addSuffix($name, $suffix);
        }
        return $name;
    }

    public function getImagePath($size = false, $imgAttr = null)
    {
        if (!$imgAttr) $imgAttr = $this->defaultFileAttribute;
        $name = $this->getImageName($size, $imgAttr);
        return $this->getBaseFilePath().'/'.$name;
    }

    public function getImageUrl($size = false, $imgAttr = null, $scheme = false)
    {
        if (!$imgAttr) $imgAttr = $this->defaultFileAttribute;
        $name = $this->getImageName($size, $imgAttr);
        $route = $this->baseUrl.'/'. $this->dir.'/'.$name;
        return Url::to($route, $scheme);
    }

    public function afterFileSave($event)
    {
        $attr = $event->attribute;
        $info = $event->fileInfo;

        if (false === ($imageInfo = getimagesize($info['path']))) {
            Yii::warning('Not an image: '.$info['path']);
            return;
        }

        list($w, $h) = $imageInfo;

        if (null !== ($wAttr = ArrayHelper::getValue($this->config[$attr], 'w'))) {
            $this->updateAttributes[$wAttr] = $w;
        }
        if (null !== ($hAttr = ArrayHelper::getValue($this->config[$attr], 'h'))) {
            $this->updateAttributes[$hAttr] = $h;
        }

        $this->createSizes($attr, $info['path']);

    }

    public function createSizes($attr, $fromPath = null)
    {
        if (!isset($this->resizeConfig[$attr])) return;

        if (null === $fromPath) {
            $fromPath = $this->getFilePath($attr);
        }
        
        foreach ($this->resizeConfig[$attr] as $size => $specs) {
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

    public function deleteSizes($attr)
    {
        if (!isset($this->resizeConfig[$attr])) return;
        
        foreach ($this->resizeConfig[$attr] as $size => $specs) {
           $path = $this->getImagePath($size, $attr);
           if (file_exists($path)) @unlink($path);
        }
    }

    public function afterFileDelete($event)
    {
        $attr = $event->attribute;
        if (null !== ($wAttr = ArrayHelper::getValue($this->config[$attr], 'w'))) {
            $this->owner->$wAttr = null;
        }
        if (null !== ($hAttr = ArrayHelper::getValue($this->config[$attr], 'h'))) {
            $this->owner->$hAttr = null;
        }

        $this->deleteSizes($attr);
    }

}