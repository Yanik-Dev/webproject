<?php

/**
 * contains a collection of useful methods
 */
class Utility{

    /**
     * strip html tags, remove space and make first character uppercase
     * @param string input
     */
    public static function sanitize($input){
        $input = strip_tags($input);
        $input = ucfirst($input);
       return $input;
    }
}