<?php

class SessionService{
    
    public static function getSession($name){
        return $_SESSION[$name];
    }

    public static function setSession($name, $value){
        $_SESSION[$name] = $value;
    }

    public static function getSessionObj($name){
        $result = (isset($_SESSION[$name]))?unserialize($_SESSION[$name]):null;
        return $result;
    }
    
    public static function setSessionObj($name, $value){
        $_SESSION[$name] = serialize($value);
    }
}