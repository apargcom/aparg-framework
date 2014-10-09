<?php
use \System\Core\Controller;
class IndexController extends Controller {
    
    public function indexAction() {        
        echo 'index';
    }
    public function default404Action() {        
        
        echo '404';
        
    }
}