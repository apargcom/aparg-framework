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
    private $size = [];
    private $meta = [];

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
            }
        }
        return ($result == false) ? false : true;
    }

    public function size() {

        return empty($this->size) ? false : $this->size;
    }

    public function meta() {

        return empty($this->meta) ? false : $this->meta;
    }

    public function save($path = '', $destroy = true, $quality = null) {
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
        if ($destroy) {
            imagedestroy($this->image);
            $this->image = null;
        }
        return $result;
    }

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

    public function flip($mode = IMG_FLIP_HORIZONTAL) {
        if ($this->image == null) {
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
