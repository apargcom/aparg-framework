<?php
use \System\Core\Controller;
class IndexController extends Controller {
    
    public function indexAction() {        
        
        $this->view();
        
        
    }
    public function default404Action() {        
        
        echo '404';
        
    }
}