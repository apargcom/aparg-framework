<?php
use \System\Core\Controller;
class IndexController extends Controller {
    
    public function indexAction($vars) {        
        var_dump($vars);
    }
    public function default404Action() {        
        
        echo '404';
        
    }
}
