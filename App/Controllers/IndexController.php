<?php

class IndexController extends Controller {
    
    private $cache = null;
    
    public function __construct(){
        parent::__construct();
        $this->cache = $this->module('cache');
    }
    
    public function indexAction($data) {        
        //$this->redirect('http://google.com');
       
        if(!$this->cache->get('first')){ 
            $this->cache->set('first', $this->view('', ['var1'=>'Hello world!'], true));            
        }
        echo $this->cache->get('first');
    }
    public function default404Action() {        
        
        echo '404'; 
    }
}
