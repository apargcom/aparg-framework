<?php

namespace System\Modules;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Mail class is system module for generating and sending emails
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Modules
 */
class Mail extends \Module {
    
    /**
     * @var array Array with "to" emails 
     */
    private $to = [];
    /**
     * @var array Array with "cc" emails 
     */
    private $cc = [];
    /**
     * @var array Array with "bcc" emails 
     */
    private $bcc = [];
    /**
     * @var string Subject
     */
    private $subject = '';
    /**
     * @var string Message content
     */
    private $message = '';
    /**
     * @var string From email
     */
    private $from = '';
    /**
     * @var array Headers to send with email 
     */
    private $headers = [];
    /**
     * @var string Email char set
     */
    private $charset = 'utf-8';
    /**
     * @var string Email content type
     */
    private $contentType = 'text/html';
    /**
     * @var string Email mime version
     */
    private $mimeVersion = '1.0';

    /**
     * Loads some configs
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->from = $this->config->get('mail_from');
    }

    /**
     * Set email charset
     * 
     * @param string $charset Email charset
     * @return void
     */
    public function charset($charset = '') {

        if (!empty($charset)) {
            $this->charset = $charset;
        }
    }

    /**
     * Set email content type
     * 
     * @param string $contentType Email content type
     * @return void
     */
    public function contentType($contentType = '') {

        if (!empty(trim($contentType))) {
            $this->contentType = $contentType;
        }
    }

    /**
     * Set email mime version
     * 
     * @param string $mimeVersion Email mime version
     * @return void
     */
    public function mimeVersion($mimeVersion = '') {

        if (!empty(trim($mimeVersion))) {
            $this->mimeVersion = $mimeVersion;
        }
    }

    /**
     * Set Headers to send with email
     * 
     * @param array $headers Each value is new headers
     * @param type $merge Whether to merge with already set headers or remove old
     * @return void
     */
    public function headers($headers = [], $merge = true) {

        if ($merge) {
            $headers = is_array($headers) ? $headers : [$headers];
            $this->headers = array_unique(array_merge($this->headers, $headers));
        } else {
            $this->headers = is_array($headers) ? $headers : [$headers];
        }
    }

    /**
     * Set "from" email
     * 
     * @param string $from From email
     * @return void
     */
    public function from($from = '') {

        $this->from = $from;
    }

    /**
     * Set "to" emails
     * 
     * @param array $to Each value is email
     * @param type $merge Whether to merge with already set "to" emails or remove old
     * @return void
     */
    public function to($to = [], $merge = true) {
        
        if ($merge) {
            $to = is_array($to) ? $to : [$to];
            $this->to = array_unique(array_merge($this->to, $to));
        } else {
            $this->to = is_array($to) ? $to : [$to];
        }
    }

    /**
     * Set "cc" emails
     * 
     * @param array $cc Each value is email
     * @param type $merge Whether to merge with already set "cc" emails or remove old
     * @return void
     */
    public function cc($cc = [], $merge = true) {
        
        if ($merge) {
            $cc = is_array($cc) ? $cc : [$cc];
            $this->cc = array_unique(array_merge($this->cc, $cc));
        } else {
            $this->cc = is_array($cc) ? $cc : [$cc];
        }
    }

    /**
     * Set "bcc" emails
     * 
     * @param array $bcc Each value is email
     * @param type $merge Whether to merge with already set "bcc" emails or remove old
     * @return void
     */
    public function bcc($bcc = [], $merge = true) {
        
        if ($merge) {
            $bcc = is_array($bcc) ? $bcc : [$bcc];
            $this->bcc = array_unique(array_merge($this->bcc, $bcc));
        } else {
            $this->bcc = is_array($bcc) ? $bcc : [$bcc];
        }
    }

    /**
     * Set subject for email
     * 
     * @param string $subject Subject of email
     * @return void
     */
    public function subject($subject = '') {

        $this->subject = $subject;
    }

    /**
     * Set message content
     * 
     * @param string $message Message content
     * @return void
     */
    public function message($message = '') {

        $this->message = $message;
    }

    
    /**
     * Send email with set configurations
     * 
     * @return boolean  True on success, false on fail
     */
    public function send() {

        $to = implode(',', $this->to);
        $cc = implode(',', $this->cc);
        $bcc = implode(',', $this->bcc);

        $headers = 'MIME-Version: ' . $this->mimeVersion . "\r\n";
        $headers.= 'Content-type: ' . $this->contentType . '; charset=' . $this->charset . "\r\n";
        $headers.= empty(trim($to)) ? '' : 'To: ' . $to . "\r\n";
        $headers.= empty(trim($this->from)) ? '' : 'From: ' . $this->from . "\r\n";
        $headers.= empty(trim($cc)) ? '' : 'Cc: ' . $cc . "\r\n";
        $headers .= empty(trim($bcc)) ? '' : 'Bcc: ' . $bcc . "\r\n";
        $headers.= implode("\r\n", $this->headers);
        
        return mail($to, $this->subject, $this->message, $headers);
    }

}