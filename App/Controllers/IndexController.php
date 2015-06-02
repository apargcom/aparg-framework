<?php

class IndexController extends Controller {

    public function indexAction($data) {
        $this->module('cache');
        $this->module('image');
        $this->module('image');
        $this->module('language');
        $this->module('mail');
        $this->module('validator');
        $this->core('config');
        $this->core('session');
        $this->core('uri');
        //$this->core('db');

        $this->view('', ['hello' => 'Hello World!']);
    }

    public function default404Action() {

        echo '404';
    }

}
