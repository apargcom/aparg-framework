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
    public function add($name, $email){
        $this->DB->insert('test',['name','email'],[$name,$email]);
    }
}
