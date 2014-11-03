<?php

class IndexController extends Controller {

    private $cache = null;

    public function __construct() {
        parent::__construct();
        $this->cache = $this->module('cache');
        $this->lang = $this->module('language');
        $this->valid = $this->module('validator');
    }

    public function indexAction($data) {
//        $this->valid->setRules([
//            'name'=>['pd3cd@dfg.com', ['alpha','valid_email']],
//            'surname'=>['22', ['alpha', ['min_length', 3]]],
//            'email'=>[ 'asd@asd.com2', [['max_length', 4]]],
//        ]);
//        $this->valid->validateAll();
//        var_dump($this->valid->errors);
        
        //var_dump($this->valid->validate('edcd#de@d.dc',['valid_email']));
        //TODO: Test validator!
        $this->view('', ['hello' => 'Hello World!']);
    }

    public function default404Action() {

        echo '404';
    }

   

}
