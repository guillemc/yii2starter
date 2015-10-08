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
     * [
     *   'my_file' => [
     *     'upload' => 'myFileUpload', // model attribute that will hold the uploaded file. This is the only mandatory field.
     *     'remove' => 'myFileRemove', // model attribute (boolean) that will be checked for removing the current file
     *     'extra' => [
     *          'my_file_size' => function ($fileInfo) { return $fileInfo['size']; },
     *          'my_file_type' => function ($fileInfo) { return $fileInfo['type']; },
     *      ],
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
            throw new Exception('Missing dirName attribute.');
        }

        foreach ($this->config as $fileAttribute => $config) {
            $uploadAttribute = ArrayHelper::getValue($config, 'upload');
            if (!$uploadAttribute || !property_exists($this->owner, $uploadAttribute)) {
                throw new Exception("Please specify the 'upload' attribute for '$fileAttribute'");
            }
        }

        $this->defaultFileAttribute = key($this->config);
    }

    public function getDir()
    {
        if (!isset($this->dir)) {
            if (is_callable($this->dirName)) {
                $func = $this->dirName;
                $this->dir = $func($this->owner);
            } else {
                $this->dir = $this->dirName;
            }
        }
        return $this->dir;
    }

    public function getBaseFilePath()
    {
        return Yii::getAlias($this->basePath).'/'.$this->getDir();
    }

    protected function ensureBaseFilePath()
    {
        $basePath = $this->getBaseFilePath();
        if (!is_dir($basePath) && isset($this->createDirMode)) {
            // To create a nested structure, the $recursive parameter
            // to mkdir() must be specified.
            if (!mkdir($basePath.'/', $this->createDirMode, true)) {
                throw new Exception('Unable to create directory: '.$basePath);
            }
        }
        if (!is_writable($basePath)) {
            throw new Exception("Directory '{$basePath}' is not writable.");
        }
    }

    public function getFilePath($fileAttribute = null)
    {
        if (!$fileAttribute) $fileAttribute = $this->defaultFileAttribute;
        return $this->getBaseFilePath().'/'.$this->owner->$fileAttribute;
    }

    public function getFileUrl($fileAttribute = null, $scheme = false)
    {
        if (!$fileAttribute) $fileAttribute = $this->defaultFileAttribute;
        $route = $this->baseUrl.'/'. $this->getDir().'/'.$this->owner->$fileAttribute;
        return Url::to($route, $scheme);
    }

    public function unlinkFile($fileAttribute = null)
    {
        if (!$fileAttribute) $fileAttribute = $this->defaultFileAttribute;
        if (!$this->owner->$fileAttribute) return;

        @unlink($this->getFilePath($fileAttribute));

        $this->onFileDeleted($fileAttribute);
    }

    protected function prepareUploadedFiles()
    {
        foreach ($this->config as $fileAttribute => $config) {
            $uploadAttribute = $config['upload'];
            $file = $this->owner->$uploadAttribute;
            if (!$file) {
                $this->owner->$uploadAttribute = UploadedFile::getInstance($this->owner, $uploadAttribute);
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
        $this->ensureBaseFilePath();

        foreach ($this->config as $fileAttribute => $config) {
            // delete current file: if autoRemoveOld is set and there is a file upload
            // or if a remove attribute is present and true
            $uploadAttribute = $config['upload'];
            $removeAttribute = ArrayHelper::getValue($config, 'remove');
            if ( ($this->autoRemoveOld && !empty($this->owner->$uploadAttribute))
                || ($removeAttribute && property_exists($this->owner, $removeAttribute) && $this->owner->$removeAttribute)
            ) {
                $this->unlinkFile($fileAttribute);
            }
        }

        //prepare uploaded files (in case we didn't do validation)
        $this->prepareUploadedFiles();

        return true;
    }


    public function afterSave($event)
    {
        $this->updateAttributes = [];
        foreach ($this->config as $fileAttribute => $config) {
            $uploadAttribute = ArrayHelper::getValue($config, 'upload');
            if ($uploadAttribute && property_exists($this->owner, $uploadAttribute)) {
                $this->saveFile($fileAttribute, $this->owner->$uploadAttribute);
            }
        }
        $this->owner->updateAttributes($this->updateAttributes);
    }

    protected function saveFile($fileAttribute, $file)
    {
        if (!$file) return;
        $fileInfo = [];
        if ($file instanceof UploadedFile && !$file->getHasError()) {
            $fileInfo = $this->saveUploadedFile($file, $fileAttribute);
        } elseif (is_string($file)) {
            if ($url = filter_var($file, FILTER_VALIDATE_URL)) {
                $fileInfo = $this->saveFileFromUrl($url, $fileAttribute);
            } else {
                // is it a path?
                $fileInfo = $this->saveFileFromPath($file, $fileAttribute);
            }
        }
        if ($fileInfo) {
            $this->onFileSaved($fileAttribute, $fileInfo);
        }
    }

    /**
     *
     * @param UploadedFile $file
     * @param string $fileAttr
     * @return array|null
     */
    protected function saveUploadedFile($file, $fileAttribute)
    {
        $name = $this->makeFileName($file->name, $fileAttribute);
        $destPath = $this->getBaseFilePath().'/'.$name;
        if ($file->saveAs($destPath, $this->deleteTempFile)) {
            return [
                'path' => $destPath,
                'name' => $name,
                'original_name' => basename($file->name),
                'size' => $file->size,
                'type' => $file->type,
            ];
        }
        return null;
    }

    protected function saveFileFromPath($file, $fileAttribute)
    {
        $path = Yii::getAlias($file);
        if (!file_exists($path)) return null;
        $name = $this->makeFileName($path, $fileAttribute);
        $destPath = $this->getBaseFilePath().'/'.$name;

        $success = true;
        if (realpath($path) != realpath($destPath)) {
            $success = $this->deleteTempFile ? rename($path, $destPath) : copy($path, $destPath);
        }

        if (!$success) return null;

        return [
            'path' => $destPath,
            'name' => $name,
            'original_name' => basename($path),
            'size' => filesize($destPath),
            'type' => FileHelper::getMimeType($destPath),
        ];
    }

    protected function saveFileFromUrl($url, $fileAttribute)
    {
        $name = $this->makeFileName(parse_url($url, PHP_URL_PATH), $fileAttribute);
        $destPath = $this->getBaseFilePath().'/'.$name;

        $success = @copy($url, $destPath);
        if (!$success) {
            Yii::error("Could not copy $url to $destPath", __METHOD__);
            return null;
        }

        return [
            'path' => $destPath,
            'name' => $name,
            'original_name' => basename($url),
            'size' => filesize($destPath),
            'type' => FileHelper::getMimeType($destPath),
        ];
    }

    protected function makeFileName($path, $fileAttribute)
    {
        $pi = pathinfo($path);
        /* $pi = pathinfo('/www/htdocs/inc/lib.inc.php');
        $pi['dirname'];    // => /www/htdocs/inc
        $pi['basename'];   // => lib.inc.php
        $pi['extension'];  // => php
        $pi['filename'];   // => lib.inc (since PHP 5.2.0) */

        $ext = isset($pi['extension']) ? '.'.strtolower($pi['extension']) : '';
        $fileName = Inflector::slug($pi['filename']).$ext;
        if ($this->fileNameCallback) {
            $func = $this->fileNameCallback;
            $fileName = trim($func($this->owner, $fileName, $ext, $fileAttribute), '/');
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

    protected function onFileSaved($fileAttribute, $fileInfo)
    {
        $e = new FileUploadEvent();
        $e->attribute = $fileAttribute;
        $e->fileInfo = $fileInfo;
        $this->owner->trigger(static::EVENT_AFTER_FILE_SAVE, $e);
    }

    public function afterFileSave($event)
    {
        $fileAttribute = $event->attribute;
        $fileInfo = $event->fileInfo;

        $this->updateAttributes[$fileAttribute] = $fileInfo['name'];
        $extraAttributes = ArrayHelper::getValue($this->config[$fileAttribute], 'extra', []);
        foreach ($extraAttributes as $k => $v) {
            $this->updateAttributes[$k] = is_callable($v) ? $v($fileInfo, $this->owner) : ArrayHelper::getValue($fileInfo, $v);
        }
    }

    protected function onFileDeleted($fileAttribute)
    {
        $e = new FileUploadEvent();
        $e->attribute = $fileAttribute;
        $this->owner->trigger(static::EVENT_AFTER_FILE_DELETE, $e);
    }

    public function afterFileDelete($event)
    {
        $fileAttribute = $event->attribute;
        $extraAttributes = ArrayHelper::getValue($this->config[$fileAttribute], 'extra', []);

        $this->owner->$fileAttribute = null;
        foreach ($extraAttributes as $k => $v) {
            $this->owner->$k = null;
        }
    }


    public function afterDelete($event)
    {
        foreach ($this->config as $fileAttribute => $config) {
            $this->unlinkFile($fileAttribute);
        }
    }
}


class FileUploadEvent extends Event
{
    public $attribute;
    public $fileInfo = [];
}