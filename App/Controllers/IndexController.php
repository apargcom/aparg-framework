<?php

class IndexController extends Controller {

    private $cache = null;

    public function __construct() {
        parent::__construct();
        $this->cache = $this->module('cache');
        $this->lang = $this->module('lang');
    }

    public function indexAction($data) {
        //$this->redirect('http://google.com');
        //var_dump($this->lang->get('hello', 'am'));
        //if(!$this->cache->get('first_'.$this->URI->lang)){ 
        //  $this->cache->set('first_'.$this->URI->lang, $this->view('', ['var1'=>$this->lang->get('hello')], true));            
        //}
        //echo $this->cache->get('first_'.$this->URI->lang);
        //$this->view('', ['var1'=>$this->lang->get('hello')]);
        //var_dump(\System\Core\DB::obj()->insert('test',['name','email'],[['asd','asd'],['bbb','ddd']]));        
        //var_dump(\System\Core\DB::obj()->fetch("SELECT * from test"));
        $this->view('', ['var1'=>$this->lang->get('hello')]);
    }

    public function default404Action() {

        echo '404';
    }

}
