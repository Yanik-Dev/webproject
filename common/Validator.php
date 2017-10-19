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
        //^: anchored to beginning of string
        //\S*: any set of characters
        //(?=\S{8,}): of at least length 8
        //(?=\S*[a-z]): containing at least one lowercase letter
        //(?=\S*[A-Z]): and at least one uppercase letter
        //(?=\S*[\d]): and at least one number
        //$: anchored to the end of the string
        $regex = "";
        return preg_replace("^\s*(?=\s{8,})(?=\s*[a-z])(?=\s*[A-Z])(?=\s*[\d])\s*$",'', $value);
    }
    
}
