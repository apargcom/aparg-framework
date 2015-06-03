<?php

namespace System\Core\Components;

use System\Core\Singleton;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Session class is for working with session
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core\Components
 */
class Session extends Singleton {

    /**
     * Initialize Session class
     * 
     * @return void
     */
    public function __construct() {

        session_start();
    }

    /**
     * Terminate Session class
     *    
     * @return void
     */
    public function __destruct() {

        session_write_close();
    }

    /**
     * Get data from opened session for specific key
     * 
     * @param string $key Key of data to get
     * @return boolean|array False on fail, array with data on success
     */
    public function get($key) {

        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    /**
     * Store data to opened session for specific key
     * 
     * @param string $key Key of data to set
     * @param mixed $value Data to Store
     * @return void
     */
    public function set($key, $value) {

        $_SESSION[$key] = $value;
    }

    /**
     * Delete opened session data for specific key
     * 
     * @param string $key Key of data to delete
     * @return void
     */
    public function delete($key) {

        unset($_SESSION[$key]);
    }

    /**
     * Get opened session id
     *      
     * @return string Opened session id
     */
    public function sessionId() {

        return session_id();
    }

}
