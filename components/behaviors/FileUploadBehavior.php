<?php

namespace app\components\behaviors;

use Yii;

use yii\helpers\Url;
use yii\base\Event;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\base\Exception;
use yii\db\ActiveRecord;


/**
 * Class FileUploadBehavior
 *
 * @property ActiveRecord $owner
 */
class FileUploadBehavior extends \yii\base\Behavior
{

    const EVENT_AFTER_FILE_DELETE = 'afterFileDelete';
    const EVENT_AFTER_FILE_SAVE = 'afterFileSave';

    /**
     * @var mixed string or callback. The callback is passed the owner model as an argument
     */
    public $dirName;

    protected $dir; //processed directory name

    public $createDirMode = 0775;

    public $deleteTempFile = true;

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    /**
     * @var callable callback for constructing the file name, or null if the original file name is to be used.
     * The callback will be passed the following arguments: owner model, original file name, file extension (including dot), current attribute.
     */
    public $fileNameCallback;

    /**
     * @var array attribute configuration, indexed by the fields that will hold the uploaded file names.
     * The values specify related fields or attributes. At least the 'upload' attribute should be specified. Example:
     * [
     *   'my_file' => [
     *     'upload' => 'myFileUpload', // model attribute that will hold the uploaded file
     *     'remove' => 'myFileRemove', // model attribute (boolean) that will be checked for removing the current file
     *     'type' => 'my_file_type', // model attribute / table field for storing the file type
     *     'size' => 'my_file_size', // model attribute / table field for storing the file size
     *   ],
     *   'my_other_file' => ...
     * ]
     */
    public $config = [
        'file' => ['upload' => 'fileUpload', 'remove' => 'fileRemove'],
    ];

    /*
     * @var boolean wether to automatically remove old file when a new one is being uploaded.
     */
    public $autoRemoveOld = false;

    protected $defaultFileAttribute;

    protected $updateAttributes;


    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }


    public function attach($owner)
    {
        parent::attach($owner);

        if (!isset($this->dirName)) {
            throw new CException('Missing dirName attribute.');
        }

        if (is_callable($this->dirName)) {
            $func = $this->dirName;
            $this->dir = $func($this->owner);
        } else {
            $this->dir = $this->dirName;
        }

        $basePath = $this->getBaseFilePath();
        if (!is_dir($basePath) && isset($this->createDirMode)) {
            // To create a nested structure, the $recursive parameter
            // to mkdir() must be specified.
            if (!mkdir($basePath.'/', $this->createDirMode, true)) {
                throw new Exception('Unable to create directory: '.$basePath);
            }
        }

        foreach ($this->config as $fileAttr => $config) {
            $uploadAttr = ArrayHelper::getValue($config, 'upload');
            if (!$uploadAttr || !property_exists($this->owner, $uploadAttr)) {
                throw new Exception("Please specify the 'upload' attribute for '$fileAttr'");
            }
        }

        if (!is_writable($basePath)) {
            throw new Exception("Directory '{$basePath}' is not writable.");
        }

        $this->defaultFileAttribute = key($this->config);
    }

    public function getBaseFilePath()
    {
        return Yii::getAlias($this->basePath).'/'.$this->dir;
    }

    public function getFilePath($fileAttr = null)
    {
        if (!$fileAttr) $fileAttr = $this->defaultFileAttribute;
        return $this->getBaseFilePath().'/'.$this->owner->$fileAttr;
    }

    public function getFileUrl($fileAttr = null, $scheme = false)
    {
        if (!$fileAttr) $fileAttr = $this->defaultFileAttribute;
        $route = $this->baseUrl.'/'. $this->dir.'/'.$this->owner->$fileAttr;
        return Url::to($route, $scheme);
    }

    public function unlinkFile($fileAttr = null)
    {
        if (!$fileAttr) $fileAttr = $this->defaultFileAttribute;
        if (!$this->owner->$fileAttr) return;

        @unlink($this->getFilePath($fileAttr));

        $this->onFileDeleted($fileAttr);
    }

    protected function prepareUploadedFiles()
    {
        foreach ($this->config as $fileAttr => $config) {
            $uploadAttr = $config['upload'];
            $file = $this->owner->$uploadAttr;
            if (!$file) {
                $this->owner->$uploadAttr = UploadedFile::getInstance($this->owner, $uploadAttr);
            }
        }
    }

    /**
     * Before validate event.
     */
    public function beforeValidate($event)
    {

        $this->prepareUploadedFiles();

        return true;
    }


    public function beforeSave($event)
    {
        foreach ($this->config as $fileAttr => $config) {
            // delete current file: if autoRemoveOld is set and there is a file upload
            // or if a remove attribute is present and true
            $uploadAttr = $config['upload'];
            $removeAttr = ArrayHelper::getValue($config, 'remove');
            if ( ($this->autoRemoveOld && !empty($this->owner->$uploadAttr))
                || (property_exists($this->owner, $removeAttr) && $this->owner->$removeAttr)
            ) {
                $this->unlinkFile($fileAttr);
            }
        }

        //prepare uploaded files (in case we didn't do validation)
        $this->prepareUploadedFiles();

        return true;
    }


    public function afterSave($event)
    {
        $this->updateAttributes = [];
        foreach ($this->config as $fileAttr => $config) {
            $uploadAttr = ArrayHelper::getValue($config, 'upload');
            if ($uploadAttr && property_exists($this->owner, $uploadAttr)) {
                $this->saveFile($fileAttr, $this->owner->$uploadAttr);
            }
        }
        $this->owner->updateAttributes($this->updateAttributes);
    }

    protected function saveFile($fileAttr, $file)
    {
        if (!$file) return;

        if ($file instanceof UploadedFile && !$file->getHasError()) {
            $data = $this->saveUploadedFile($file, $fileAttr);
        } elseif (is_string($file)) { // is it a path?
            $data = $this->saveFileFromPath($file, $fileAttr);
        }

        if ($data) {
            $this->onFileSaved($fileAttr, $data);
        }
    }

    protected function saveUploadedFile($file, $fileAttr)
    {
        $name = $this->makeFileName($file->name, $fileAttr);
        $destPath = $this->getBaseFilePath().'/'.$name;
        if ($file->saveAs($destPath, $this->deleteTempFile)) {
            return [
                'path' => $destPath,
                'name' => $name,
                'size' => $file->size,
                'type' => $file->type,
            ];
        }
        return null;
    }

    protected function saveFileFromPath($file, $fileAttr)
    {
        $path = Yii::getAlias($file);
        if (!file_exists($path)) return null;
        $name = $this->makeFileName($path, $fileAttr);
        $destPath = $this->getBaseFilePath().'/'.$name;

        $success = true;
        if (realpath($path) != realpath($destPath)) {
            $success = $this->deleteTempFile ? rename($path, $destPath) : copy($path, $destPath);
        }

        if (!$success) return null;

        return [
            'path' => $destPath,
            'name' => $name,
            'size' => filesize($destPath),
            'type' => FileHelper::getMimeType($destPath),
        ];
    }

    protected function makeFileName($path, $fileAttr)
    {
        $pi = pathinfo($path);
        /* $pi = pathinfo('/www/htdocs/inc/lib.inc.php');
        $pi['dirname'];    // => /www/htdocs/inc
        $pi['basename'];   // => lib.inc.php
        $pi['extension'];  // => php
        $pi['filename'];   // => lib.inc (since PHP 5.2.0) */

        $ext = strtolower($pi['extension']);
        $fileName = Inflector::slug($pi['filename']).'.'.$ext;
        if ($this->fileNameCallback) {
            $func = $this->fileNameCallback;
            $fileName = trim($func($this->owner, $fileName, $ext, $fileAttr), '/');
        }

        if (false !== strpos($fileName, '/')) {
            // is a path: try to create it
            $this->makeFilePath($fileName);
        }

        return $fileName;
    }

    protected function makeFilePath($fileName)
    {
        $filePath = $this->getBaseFilePath().'/'.dirname($fileName);
        if (!file_exists($filePath) && !mkdir($filePath, $this->createDirMode, true)) {
            throw new Exception('Unable to create directory: '.$filePath);
        }
    }

    protected function onFileSaved($fileAttr, $data)
    {
        $this->updateAttributes[$fileAttr] = $data['name'];
        if (null !== ($sizeAttr = ArrayHelper::getValue($this->config[$fileAttr], 'size'))) {
            $this->updateAttributes[$sizeAttr] = $data['size'];
        }
        if (null !== ($typeAttr = ArrayHelper::getValue($this->config[$fileAttr], 'type'))) {
            $this->updateAttributes[$typeAttr] = $data['type'];
        }
        $e = new FileUploadEvent();
        $e->attribute = $fileAttr;
        $e->fileInfo = $data;
        $this->owner->trigger(static::EVENT_AFTER_FILE_SAVE, $e);
    }

    protected function onFileDeleted($fileAttr)
    {
        $e = new FileUploadEvent();
        $e->attribute = $fileAttr;
        $this->owner->trigger(static::EVENT_AFTER_FILE_DELETE, $e);
        
        $this->owner->$fileAttr = null;
        if (null !== ($sizeAttr = ArrayHelper::getValue($this->config[$fileAttr], 'size'))) {
            $this->owner->$sizeAttr = null;
        }
        if (null !== ($typeAttr = ArrayHelper::getValue($this->config[$fileAttr], 'type'))) {
            $this->owner->$typeAttr = null;
        }
    }


    public function afterDelete($event)
    {
        foreach ($this->config as $fileAttr => $config) {
            $this->unlinkFile($fileAttr);
        }
    }
}


class FileUploadEvent extends Event
{
    public $attribute;
    public $fileInfo;
}
