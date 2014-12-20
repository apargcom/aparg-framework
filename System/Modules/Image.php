<?php

namespace System\Modules;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Image class is system module for editing and adding effects to images
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Modules
 */
class Image extends \Module {
    
    /**
     * @var string Path to image
     */
    private $path = '';
    /**
     * @var resource Image resource
     */
    private $image = null;
    /**
     * @var array Array with image initial sizes
     */
    private $size = [];
    /**
     * @var array Array with image meta information
     */
    private $meta = [];
    /**
     * @var string Image mime type
     */
    private $mime = '';

    /**
     * Loads image file
     * 
     * @param string $path Path to image file
     * @return boolean True success, false fail
     */
    public function open($path) {

        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            return false;
        }
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
            if ($result != false) {
                $this->image = $result;
                $this->size['width'] = imagesx($this->image);
                $this->size['height'] = imagesy($this->image);
                $this->meta = exif_read_data($path);
                $this->mime = mime_content_type($path);
            }
        }
        return ($result == false) ? false : true;
    }
    
    /**
     * Returns image initial size
     * 
     * @return boolean|array Image size on success, false on fail
     */
    public function size() {

        return empty($this->size) ? false : $this->size;
    }
    
    /**
     * Returns image meta information
     * 
     * @return boolean|array Image meta information on success, false on fail
     */
    public function meta() {

        return empty($this->meta) ? false : $this->meta;
    }

    /**
     * Returns image mime type
     * 
     * @return boolean|array Image mime type on success, false on fail
     */
    public function mime() {

        return empty($this->mime) ? false : $this->mime;
    }
    
    /**
     * Saves image to file
     * 
     * @param string $path Path to save
     * @param boolean $close Whether to close current image
     * @param integer $quality Quality of image regarding to image format. For PNG uses imagepng() and for JPEG imagejpeg() quality parameter
     * @see imagepng(), imagejpeg() For $quality parameter
     * @return boolean
     */
    public function save($path = '', $close = true, $quality = null) {
        if ($this->image == null) {
            return false;
        }
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
        if ($close) {
            imagedestroy($this->image);
            $this->image = null;
        }
        return $result;
    }

    /**
     * Adds watermark to image
     * 
     * @param string $path Path to watermark
     * @param array $position If empty array watermark will be placed in the middle, else accept array with position Ex: ['top'=>10,'left'=>10]
     * @return boolean True on success, false on fail
     */
    public function watermark($path, $position = []) {
        if ($this->image == null) {
            return false;
        }
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
            if ($result != false) {
                $watermark = $result;
            }
        }

        if ($watermark == null) {
            return false;
        }

        if (empty($position)) {
            $left = $this->size['width'] / 2 - imagesx($watermark) / 2;
            $top = $this->size['height'] / 2 - imagesy($watermark) / 2;
        } else {
            $left = isset($position['left']) ? $position['left'] : (isset($position['right']) ? $this->size['width'] - imagesx($watermark) - $position['right'] : 0);
            $top = isset($position['top']) ? $position['top'] : (isset($position['bottom']) ? $this->size['height'] - imagesy($watermark) - $position['bottom'] : 0);
        }

        $result = imagecopy($this->image, $watermark, $left, $top, 0, 0, imagesx($watermark), imagesy($watermark));

        imagedestroy($watermark);

        if ($result != false) {
            $this->size['width'] = imagesx($this->image);
            $this->size['height'] = imagesy($this->image);
        }
        return $result;
    }

    /**
     * Resizes image to given sizes
     * 
     * @param array $size Array with new image sizes Ex.:['width' => 100, 'height' => 100]
     * @param array $keepRatio Whether to keep image aspect ratio
     * @return boolean True on success, false on fail
     */
    function resize($size = [], $keepRatio = true) {

        if ($this->image == null) {
            return false;
        }

        $size['width'] = isset($size['width']) ? ($size['width'] <= 0 ? $this->size['width'] : $size['width']) : (isset($size['height']) ? ($keepRatio ? PHP_INT_MAX : $this->size['width']) : $this->size['width']);
        $size['height'] = isset($size['height']) ? ($size['height'] <= 0 ? $this->size['height'] : $size['height']) : (isset($size['width']) ? ($keepRatio ? PHP_INT_MAX : $this->size['height']) : $this->size['height']);

        $oldSize['width'] = imagesx($this->image);
        $oldSize['height'] = imagesy($this->image);

        if ($keepRatio) {
            $ratio = $oldSize['width'] / $oldSize['height'];

            if ($size['width'] / $size['height'] > $ratio) {
                $newSize['width'] = $size['height'] * $ratio;
                $newSize['height'] = $size['height'];
            } else {
                $newSize['height'] = $size['width'] / $ratio;
                $newSize['width'] = $size['width'];
            }
        } else {
            $newSize['width'] = $size['width'];
            $newSize['height'] = $size['height'];
        }

        $new = imagecreatetruecolor($newSize['width'], $newSize['height']);
        $result = imagecopyresampled($new, $this->image, 0, 0, 0, 0, $newSize['width'], $newSize['height'], $this->size['width'], $this->size['height']);
        if ($result != false) {
            $this->size['width'] = imagesx($new);
            $this->size['height'] = imagesy($new);
            imagedestroy($this->image);
            $this->image = $new;
        }
        return $result;
    }
    
    /**
     * Crops image to given sizes
     * 
     * @param array $size New crop size Ex.:['height' => 100, 'width' => 100]
     * @param array $coordinates Position of cropped area Ex.:['x' => 100, 'y' => 100]
     * @return boolean True on success, false on fail
     */
    public function crop($size = [], $coordinates = []) {

        if ($this->image == null) {
            return false;
        }

        $size['width'] = isset($size['width']) ? (($size['width'] <= 0 || $size['width'] > $this->size['width']) ? $this->size['width'] : $size['width']) : $this->size['width'];
        $size['height'] = isset($size['height']) ? (($size['height'] <= 0 || $size['height'] > $this->size['height']) ? $this->size['height'] : $size['height']) : $this->size['height'];

        if (empty($coordinates)) {
            $coordinates['x'] = $this->size['width'] / 2 - $size['width'] / 2;
            $coordinates['y'] = $this->size['height'] / 2 - $size['height'] / 2;
        } else {
            $coordinates['x'] = isset($coordinates['x']) ? ($coordinates['x'] < 0 ? 0 : ($coordinates['x'] > $this->size['width'] ? $this->size['width'] - $size['width'] : $coordinates['x'])) : 0;
            $coordinates['y'] = isset($coordinates['y']) ? ($coordinates['y'] < 0 ? 0 : ($coordinates['y'] > $this->size['height'] ? $this->size['height'] - $size['height'] : $coordinates['y'])) : 0;            
            $size['width'] = ($coordinates['x'] + $size['width']) > $this->size['width'] ? ($this->size['width'] - $coordinates['x']) : $size['width'];
            $size['height'] = ($coordinates['y'] + $size['width']) > $this->size['height'] ? ($this->size['height'] - $coordinates['y']) : $size['height'];
        }

        $new = imagecreatetruecolor($size['width'], $size['height']);
        $result = imagecopyresampled($new, $this->image, 0, 0, $coordinates['x'], $coordinates['y'], $this->size['width'], $this->size['height'], $this->size['width'], $this->size['height']);
        if ($result != false) {
            $this->size['width'] = imagesx($new);
            $this->size['height'] = imagesy($new);
            imagedestroy($this->image);
            $this->image = $new;
        }
        return $result;
    }

    /**
     * Rotates image with given angle
     * 
     * @param integer $angle Angle to rotate image
     * @param string $bgColor Background color of empty area Ex.:#415E9B
     * @return boolean True on success, false on fail
     */
    public function rotate($angle, $bgColor) {
        if ($this->image == null) {
            return false;
        }
        list($r, $g, $b) = array_map('hexdec', str_split(ltrim($bgColor, '#'), 2));
        $bg = imagecolorallocate($this->image, $r, $g, $b);
        $new = imagerotate($this->image, $angle, $bg);

        if ($new != false) {
            $this->size['width'] = imagesx($new);
            $this->size['height'] = imagesy($new);
            imagedestroy($this->image);
            $this->image = $new;
        } else {
            return false;
        }
    }

    /**
     * Flips image
     * 
     * @param integer $mode Flip method(IMG_FLIP_HORIZONTAL, IMG_FLIP_VERTICAL or IMG_FLIP_BOTH)
     * @return boolean True on success, false on fail
     */
    public function flip($mode = IMG_FLIP_HORIZONTAL) {
        if ($this->image == null || !function_exists('imageflip')) {
            return false;
        }
        $result = imageflip($this->image, IMG_FLIP_HORIZONTAL);
        if ($result != false) {
            $this->size['width'] = imagesx($this->image);
            $this->size['height'] = imagesy($this->image);   
        } else {
            return false;
        }
    }

    /**
     * Adds color overlay on image
     * 
     * @param string $color Color of overlay Ex.:#ffffff
     * @param integer $transparency Transparency of overlay
     * @return boolean True on success, false on fail
     */
    public function overlay($color = '#ffffff', $transparency = 80){
        if ($this->image == null) {
            return false;
        }
        list($r, $g, $b) = array_map('hexdec', str_split(ltrim($color, '#'), 2));
        $alpha = intval(($transparency * 127) / 100);        
        $overlay = imagecreatetruecolor($this->size['width'], $this->size['height']);
        $color = imagecolorallocatealpha($overlay, $r, $g, $b, $alpha);
        imagefill($overlay,0,0,$color);
        
        $result = imagecopy($this->image, $overlay, 0, 0, 0, 0, $this->size['width'], $this->size['height']);
        
        if ($result != false) {
            $this->size['width'] = imagesx($this->image);
            $this->size['height'] = imagesy($this->image);
            imagedestroy($overlay);
        } else {
            return false;
        }
    }   
}
