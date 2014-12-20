<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Arsen
 */
namespace App\Models;
class User extends \Model{
    public function user(){
       
        var_dump( $this->DB->select('test1',[],[['','test3','surname=work']]));
    }
}
