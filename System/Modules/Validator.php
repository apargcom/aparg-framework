<?php

namespace System\Modules;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Validator class is system module for validating multiple values for against multiple rules
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Modules
 */
class Validator extends \Module {

    /**
     * @var array Array with rules where key is input name, value is array which 0 element is input value 1 element is array with rules
     */
    private $rules = [];
    /**
     * @var array Array with error after validation 
     */
    public $errors = [];
    /**
     * @var array Array with rules where key is rule name value is function name
     */
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
        'valid_phone' => 'validPhone',
        'valid_email' => 'validEmail',
        'valid_emails' => 'validEmails',
        'valid_ip' => 'validIP',
        'valid_base64' => 'validBase64'
    ];

    /**
     * Sets rules array
     * 
     * @param array $rules Array where 0 element is input name and 1 element is array where 0 element is value 1 element is array with rules.
     *                     Ex.:['input_name'=>['input_value',['alpha_numeric',['min_length',4]]]]
     * @return void
     */
    public function rules($rules = []) {

        $this->rules = $rules;
    }

    /**
     * Validates all inputs by all given rules
     * 
     * @return boolean True success, false fail
     */
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

    /**
     * Validates one input
     * 
     * @param string $inputValue Input value
     * @param array $inputRules Input rules. For rules with parameter array must be given. Ex.:['alpha_numeric',['min_length',4]]
     * @return boolean|array True on success, array with errors on fail
     */
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

    /**
     * Validate value for required rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function required($value) {

        return (trim($value) == '') ? false : true;
    }

    /**
     * Validate value for regexMatch rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function regexMatch($value, $param) {

        return (preg_match($param, $value) == false) ? false : true;
    }

    /**
     * Validate value for matches rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function matches($value, $param) {

        return ($value == $this->rules[$param][0]) ? true : false;
    }

    /**
     * Validate value for isUnique rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function isUnique($value) {

        $counter = 0;
        foreach ($this->rules as $input) {
            if ($input[0] == $value) {
                $counter++;
            }
        }
        return ($counter > 1) ? false : true;
    }

    /**
     * Validate value for minLength rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function minLength($value, $param) {

        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) < $param) ? false : true;
        }

        return (strlen($value) < $param) ? false : true;
    }

    /**
     * Validate value for maxLength rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function maxLength($value, $param) {
        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) > $param) ? false : true;
        }

        return (strlen($value) > $param) ? false : true;
    }

    /**
     * Validate value for exactLength rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function exactLength($value, $param) {
        if (is_numeric($value)) {
            return false;
        }
        if (function_exists('mb_strlen')) {
            return (mb_strlen($value) != $param) ? false : true;
        }

        return (strlen($value) != $param) ? false : true;
    }

    /**
     * Validate value for greaterThan rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function greaterThan($value, $param) {

        if (!is_numeric($value)) {
            return false;
        }
        return $value > $param;
    }

    /**
     * Validate value for lessThan rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function lessThan($value, $param) {
        if (!is_numeric($value)) {
            return false;
        }
        return $value < $param;
    }

    /**
     * Validate value for alpha rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function alpha($value) {

        return (preg_match("/^([a-z])+$/i", $value) == false) ? false : true;
    }

    /**
     * Validate value for alphaNumeric rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function alphaNumeric($value) {

        return (preg_match("/^([a-z0-9])+$/i", $value) == false) ? false : true;
    }

    /**
     * Validate value for alphaDash rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function alphaDash($value) {

        return (preg_match("/^([-a-z0-9_-])+$/i", $value) == false) ? false : true;
    }

    /**
     * Validate value for numeric rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function numeric($value) {

        return (preg_match("/^[\-+]?[0-9]*\.?[0-9]+$/", $value) == false) ? false : true;
    }

    /**
     * Validate value for integer rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function integer($value) {

        return (preg_match("/^[\-+]?[0-9]+$/", $value) == false) ? false : true;
    }

    /**
     * Validate value for decimal rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function decimal($value) {

        return (preg_match("/^[\-+]?[0-9]+\.[0-9]+$/", $value) == false) ? false : true;
    }

    /**
     * Validate value for isNatural rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function isNatural($value) {

        return (preg_match("/^[0-9]+$/", $value) == false) ? false : true;
    }

    /**
     * Validate value for isNaturalNoZero rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function isNaturalNoZero($value) {

        if (preg_match('/^[0-9]+$/', $value) == false) {
            return false;
        }
        if ($value == 0) {
            return false;
        }
        return true;
    }

    /**
     * Validate value for validPhone rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function validPhone($value) {

        return (preg_match("/^[0-9\.\#\+\-\(\)\s]*$/", $value) == false) ? false : true;
    }

    /**
     * Validate value for validEmail rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function validEmail($value) {

        return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value) == false) ? false : true;
    }

    /**
     * Validate value for validEmails rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
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

    /**
     * Validate value for validIP rule
     * 
     * @param string $value Value
     * @param string $param Rule option
     * @return boolean True on success, false on fail
     */
    private function validIP($value, $param) {
        if ($param == 'ipv4') {
            return (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) == false) ? false : true;
        } elseif ($param == 'ipv6') {
            return (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) == false) ? false : true;
        } else {
            return false;
        }
    }

    /**
     * Validate value for validBase64 rule
     * 
     * @param string $value Value
     * @return boolean True on success, false on fail
     */
    private function validBase64($value) {
        var_dump($value);
        return (preg_match("/[^a-zA-Z0-9\/\+=]/", $value) == false) ? false : true;
    }

}