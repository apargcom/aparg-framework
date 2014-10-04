<?php
//use System\Core\Controller;

class IndexController extends Controller {
    
    public function indexAction() {        
        echo Config::get('min_php_version');
        
      
    }
}