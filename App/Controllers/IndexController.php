<?php

class IndexController extends Controller {

    private $cache = null;

    public function __construct() {
        parent::__construct();
        $this->cache = $this->module('cache');
        $this->lang = $this->module('lang');
    }

    public function indexAction($data) {
        
        $this->view('', ['hello'=>'Hello World!']);        
    }
   
    public function default404Action() {

        echo '404';
    }

}
