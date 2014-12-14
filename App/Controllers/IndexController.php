<?php

class IndexController extends Controller {
    
    public function __construct() {
        
        parent::__construct();
    }

    public function indexAction($data) {

        $this->view('', ['hello' => 'Hello World!']);
    }

    public function default404Action() {

        echo '404';
    }

}
