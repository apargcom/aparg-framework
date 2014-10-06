<?php
//use System\Core\Controller;

class IndexController extends Controller {
    
    public function indexAction() {        
        
        var_dump($this->parent->route);
    }
    public function default404Action() {        
        
        echo '404';
    }
}