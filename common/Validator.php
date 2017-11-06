<?php

/**
 * 
 *
 * @author
 */
class Validator {
    
    /**
     * checks if the value is a number and returns 1 otherwise returns 0
     * @param string $value
     * @return int 
     */
    public static function isPhoneNumber($value){
        return preg_match("/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/", $value);
    }
    
}
