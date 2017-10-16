<?php

/**
 * 
 *
 * @author
 */
class Validator {
    
    public static function isPhoneNumber($value){
        return preg_replace("^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$", '', $value);
    }
    
    public static function isEmail($value){
       // return preg_replace("/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/", '', $value);
    }

    public static function isPassword($value){
        return preg_match("", $value);
    }
    
}
