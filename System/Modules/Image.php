<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

class Image extends \Module {

    private $path = '';
    private $image = null;

    public function open($path) {

        $this->path = $path;
        
        $result = false;
        if (file_exists($this->path)) {
            $ext = strtolower(pathinfo($path)['extension']);
            if ($ext == 'png') {
                $result = imagecreatefrompng($this->path);
            } else if ($ext == 'jpg' || $ext == 'jpeg') {
                $result = imagecreatefromjpeg($this->path);
            } else if ($ext == 'gif') {
                $result = imagecreatefromgif($this->path);
            }
            if($result != false){
                $this->image = $result;
            }
        }        
        return ($result == false) ? false : true;
    }

    public function save($path = '', $quality = null) {

        $path = empty($path) ? $this->path : $path;

        $result = false;
        $ext = strtolower(pathinfo($path)['extension']);
        if ($ext == 'png') {                        
            $result = is_null($quality) ? imagepng($this->image, $path) : imagepng($this->image, $path, $quality);
        } else if ($ext == 'jpg' || $ext == 'jpeg') {
            $result = is_null($quality) ? imagejpeg($this->image, $path) : imagejpeg($this->image, $path, $quality);
        } else if ($ext == 'gif') {
            $result = imagegif($this->image, $path);
        }        
        return $result;
    }

    public function watermark($path, $position = []) {

        $watermark = null;
        $result = false;
        if (file_exists($path)) {
            $ext = strtolower(pathinfo($path)['extension']);
            if ($ext == 'png') {
                $result = imagecreatefrompng($path);
            } else if ($ext == 'jpg' || $ext == 'jpeg') {
                $result = imagecreatefromjpeg($path);
            } else if ($ext == 'gif') {
                $result = imagecreatefromgif($path);
            }           
            if($result != false){
                $watermark = $result;
            }
        }
        
        if($watermark == null || $this->image == null){
            return false;
        }
        
        if(empty($position)){
            $left = imagesx($this->image)/2 - imagesx($watermark)/2;
            $top = imagesy($this->image)/2 - imagesy($watermark)/2;            
        }else{
            $left = isset($position['left']) ? $position['left'] : (isset($position['right']) ? imagesx($this->image) - imagesx($watermark) - $position['right'] : 0);
            $top = isset($position['top']) ? $position['top'] : (isset($position['bottom']) ? imagesy($this->image) - imagesy($watermark) - $position['bottom'] : 0);            
        }        

        $result = imagecopy($this->image, $watermark, $left, $top, 0, 0, imagesx($watermark), imagesy($watermark));
        if($watermark != null){
            imagedestroy($watermark);
        }
        return $result;
    }
}
