<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

class Validator extends \Module {

    private $rules = [];
    public $errors = [];
    private $rulesMethods = [
        'required' => 'required',
        'matches' => 'matches',
        'regex_match' => 'regexMatch',
        'is_unique' => 'isUnique',
        'min_length' => 'minLength',
        'max_length' => 'maxLength',
        'exact_length' => 'exactLength',
        'greater_than' => 'greaterThan',
        'less_than' => 'lessThan',
        'alpha' => 'alpha',
        'alpha_numeric' => 'alphaNumeric',
        'alpha_dash' => 'alphaDash',
        'numeric' => 'numeric',
        'integer' => 'integer',
        'decimal' => 'decimal',
        'is_natural' => 'isNatural',
        'is_natural_no_zero' => 'isNaturalNoZero',
        'valid_email' => 'validEmail',
        'valid_emails' => 'validEmails',
        'valid_ip' => 'validIP',
        'valid_base64' => 'validBase64'
    ];

    public function __construct() {

        parent::__construct();
    }

    public function rules($rules = []) {

        $this->rules = $rules;
    }

    public function validate() {

        $valid = true;
        foreach ($this->rules as $input => $data) {
            $inputName = $input;
            $inputValue = $data[0];
            $inputRules = $data[1];

            foreach ($inputRules as $rule) {
                if (is_array($rule)) {
                    $ruleName = $rule[0];
                    $ruleParam = $rule[1];
                    $method = array_key_exists($ruleName, $this->rulesMethods) ? $this->rulesMethods[$ruleName] : '';
                    $inputValid = !empty($method) ? $this->$method($inputValue, $ruleParam) : false;
                } else {
                    $ruleName = $rule;
                    $method = array_key_exists($ruleName, $this->rulesMethods) ? $this->rulesMethods[$ruleName] : '';
                    $inputValid = !empty($method) ? $this->$method($inputValue) : false;
                }

                if (!$inputValid) {
                    $valid = false;
                    $this->errors[$inputName][] = is_array($rule) ? $rule[0] : $rule;
                }
            }
        }
        return $valid;
    }

    public function validateOne($inputValue, $inputRules) {

        $valid = true;
        $errors = [];
        foreach ($inputRules as $rule) {
            if (is_array($rule)) {
                $ruleName = $rule[0];
                $ruleParam = $rule[1];
                $method = array_key_exists($ruleName, $this->rulesMethods) ? $this->rulesMethods[$ruleName] : '';
                $inputValid = !empty($method) ? $this->$method($inputValue, $ruleParam) : false;
            } else {
                $ruleName = $rule;
                $method = array_key_exists($ruleName, $this->rulesMethods) ? $this->rulesMethods[$ruleName] : '';
                $inputValid = !empty($method) ? $this->$method($inputValue) : false;
            }

            if (!$inputValid) {
                $valid = false;
                $errors[] = is_array($rule) ? $rule[0] : $rule;
            }
        }
        return $valid ? true : $errors;
    }

    private function required($value) {

        return (trim($value) == '') ? false : true;
    }

    private function regexMatch($value, $param) {

        return (preg_match($param, $value) == false) ? false : true;
    }

    private function matches($value, $param) {

        return ($value == $this->rules[$param][0]) ? true : false;
    }

    private function isUnique($value) {

        $counter = 0;
        foreach ($this->rules as $input) {
            if ($input[0] == $value) {
                $counter++;
            }
        }
        return ($counter > 1) ? false : true;
    }

    private function minLength($value, $param) {

        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) < $param) ? false : true;
        }

        return (strlen($value) < $param) ? false : true;
    }

    private function maxLength($value, $param) {
        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) > $param) ? false : true;
        }

        return (strlen($value) > $param) ? false : true;
    }

    private function exactLength($value, $param) {
        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) != $param) ? false : true;
        }

        return (strlen($value) != $param) ? false : true;
    }

    private function greaterThan($value, $param) {

        if (!is_numeric($value)) {
            return false;
        }
        return $value > $param;
    }

    private function lessThan($value, $param) {
        if (!is_numeric($value)) {
            return false;
        }
        return $value < $param;
    }

    private function alpha($value) {

        return (preg_match("/^([a-z])+$/i", $value) == false) ? false : true;
    }

    private function alphaNumeric($value) {

        return (preg_match("/^([a-z0-9])+$/i", $value) == false) ? false : true;
    }

    private function alphaDash($value) {

        return (preg_match("/^([-a-z0-9_-])+$/i", $value) == false) ? false : true;
    }

    private function numeric($value) {

        return (preg_match("/^[\-+]?[0-9]*\.?[0-9]+$/", $value) == false) ? false : true;
    }

    private function integer($value) {

        return (preg_match("/^[\-+]?[0-9]+$/", $value) == false) ? false : true;
    }

    private function decimal($value) {

        return (preg_match("/^[\-+]?[0-9]+\.[0-9]+$/", $value) == false) ? false : true;
    }

    private function isNatural($value) {

        return (preg_match("/^[0-9]+$/", $value) == false) ? false : true;
    }

    private function isNaturalNoZero($value) {

        if (preg_match('/^[0-9]+$/', $value) == false) {
            return false;
        }
        if ($str == 0) {
            return false;
        }
        return true;
    }

    private function validEmail($value) {

        return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value) == false) ? false : true;
    }

    private function validEmails($value) {

        if (strpos($value, ',') === false) {
            return $this->email(trim($value));
        }

        foreach (explode(',', $value) as $email) {
            if (trim($email) != '' && $this->email(trim($email)) === false) {
                return false;
            }
        }

        return true;
    }

    private function validIP($value, $param) {
        if ($param == 'ipv4') {
            return (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) == false) ? false : true;
        } elseif ($param == 'ipv6') {
            return (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) == false) ? false : true;
        } else {
            return false;
        }
    }

    private function validBase64($value) {
        var_dump($value);
        return (preg_match("/[^a-zA-Z0-9\/\+=]/", $value) == false) ? false : true;
    }

}
