<?php

class IndexController extends Controller {
    
    public function __construct() {
        
        parent::__construct();
    }

    public function indexAction($data) {
var_dump(MYSQLI_ASSOC);

        $this->view('', ['hello' => 'Hello World!']);
    }

    public function default404Action() {

        echo '404';
    }

}
