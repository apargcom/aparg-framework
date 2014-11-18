<?php

class IndexController extends Controller {

    private $cache = null;

    public function __construct() {
        parent::__construct();
        $this->cache = $this->module('cache');
        $this->lang = $this->module('language');
        $this->valid = $this->module('validator');
        $this->image = $this->module('image');
        $this->user = $this->model('user');
    }

    public function indexAction($data) {

//        $this->image->open('gayane.jpg');
//        $this->image->resize(['width' => 500, 'height' => 500], true);
//        $this->image->rotate(30, '#415E9B');
//        $this->image->crop(['height' => 450, 'width' => 450]);
//        $this->image->watermark('watermark.png', ['right' => 20, 'bottom' => 20]);
//        $this->image->flip();
//        $this->image->overlay('#00ff00', 80);
//        $this->image->save('gayane1.jpg');
//        var_dump($this->image->size());
//        var_dump($this->image->meta());
//        $this->user->add('poghos', 'poghos@gmail.com');
        $this->view('', ['hello' => 'Hello World!']);
    }

    public function default404Action() {

        echo '404';
    }

}
