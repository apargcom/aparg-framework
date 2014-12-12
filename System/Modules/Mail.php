<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

class Mail extends \Module {

    private $to = [];
    private $cc = [];
    private $bcc = [];
    private $subject = '';
    private $message = '';
    private $from = '';
    private $headers = [];
    private $charset = 'utf-8';
    private $contentType = 'text/html';
    private $mimeVersion = '1.0';

    public function __construct() {
        parent::__construct();

        $this->from = $this->config->get('mail_from');
    }

    public function charset($charset = '') {

        if (!empty($charset)) {
            $this->charset = $charset;
        }
    }

    public function contentType($contentType = '') {

        if (!empty(trim($contentType))) {
            $this->contentType = $contentType;
        }
    }

    public function mimeVersion($mimeVersion = '') {

        if (!empty(trim($mimeVersion))) {
            $this->mimeVersion = $mimeVersion;
        }
    }

    public function headers($headers = [], $merge = true) {

        if ($merge) {
            $headers = is_array($headers) ? $headers : [$headers];
            $this->headers = array_unique(array_merge($this->headers, $headers));
        } else {
            $this->headers = is_array($headers) ? $headers : [$headers];
        }
    }

    public function from($from = '') {

        $this->from = $from;
    }

    public function to($to = [], $merge = true) {
        
        if ($merge) {
            $to = is_array($to) ? $to : [$to];
            $this->to = array_unique(array_merge($this->to, $to));
        } else {
            $this->to = is_array($to) ? $to : [$to];
        }
    }

    public function cc($cc = [], $merge = true) {
        
        if ($merge) {
            $cc = is_array($cc) ? $cc : [$cc];
            $this->cc = array_unique(array_merge($this->cc, $cc));
        } else {
            $this->cc = is_array($cc) ? $cc : [$cc];
        }
    }

    public function bcc($bcc = [], $merge = true) {
        
        if ($merge) {
            $bcc = is_array($bcc) ? $bcc : [$bcc];
            $this->bcc = array_unique(array_merge($this->bcc, $bcc));
        } else {
            $this->bcc = is_array($bcc) ? $bcc : [$bcc];
        }
    }

    public function subject($subject = '') {

        $this->subject = $subject;
    }

    public function message($message = '') {

        $this->message = $message;
    }

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
