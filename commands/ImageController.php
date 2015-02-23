<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\imagine\Image;
use Imagine\Image\Point\Center;
use Imagine\Image\Box;

class ImageController extends Controller
{

    public function actionCrop($in, $width, $height, $out = null)
    {
        $imagine = Image::getImagine();
        $path = Yii::getAlias($in);
        $img = $imagine->open($path);
        
        if ($out) {
            $img->copy();
        }
        $center = new Center($img->getSize());
        $img->crop($center, new Box($width, $height));
        if ($out) {
            if (false !== strpos('/', $out)){
                $img->save(Yii::getAlias($out));
            } else {
                $img->save(dirname($path).'/'.$out);
            }
        }
    }
    
    public function actionResize($in, $width, $height, $out = null)
    {
        $imagine = Image::getImagine();
        $path = Yii::getAlias($in);
        $img = $imagine->open($path);
        
        if ($out) {
            $img->copy();
        }       
        $img->resize(new Box($width, $height));
        if ($out) {
            if (false !== strpos('/', $out)){
                $img->save(Yii::getAlias($out));
            } else {
                $img->save(dirname($path).'/'.$out);
            }
        }
    }
    
    public function actionThumbIn($in, $width, $height, $out = null)
    {
        $this->thumb(\Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET, $in, $width, $height, $out);
    } 
    
    public function actionThumbOut($in, $width, $height, $out = null)
    {
        $this->thumb(\Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND, $in, $width, $height, $out);
    }
    
    protected function thumb($mode, $in, $width, $height, $out = null)
    {
    
        $imagine = Image::getImagine();
        $path = Yii::getAlias($in);
        $img = $imagine->open($path);
        
        $thumb = $img->thumbnail(new Box($width, $height), $mode);
       
        if ($out) {
            if (false !== strpos('/', $out)){
                $thumb->save(Yii::getAlias($out));
            } else {
                $thumb->save(dirname($path).'/'.$out);
            }
        } else {
            $thumb->save($path);
        }
    }
}
