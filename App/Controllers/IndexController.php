<?php
use \System\Core\Controller;
class IndexController extends Controller {
    
    public function indexAction() {        
        echo 'fv';
      ///  $this->view();
        
      //  $this->view->load('header');
      //  $this->view->load();
       
    }
    public function default404Action() {        
        
        echo '404';
        
    }
}