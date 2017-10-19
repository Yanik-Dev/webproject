<?php

class SessionService{
    
    /**
     * gets a value from a session variable
     * @return any;
     */
    public static function getSession($name){
        return $_SESSION[$name];
    }

    /**
     * sets a user session
     * @param $name name of session
     * @param $value session data
     * @param $flag if true, store store value as cookie
     * @param $time time cookie expires
     */
    public static function setSession($name, $value, $flag=false, $time = null){
        $_SESSION[$name] = $value;
        if($flag){
            $time = (isset($time))?$time: time()+30*24*60*60;
            setcookie($name, $value, $time);
        }
    }

    /**
     * gets a user session
     * @param $name name of session
     * @return session object
     */
    public static function getSessionObj($name){
        $result = (isset($_SESSION[$name]))?unserialize($_SESSION[$name]):null;
        return $result;
    }
    
    /**
     * sets a user session obj
     * @param $name name of session
     * @param $value session object data
     * @param $flag if true, store store value as cookie
     * @param $time time cookie expires
     */
    public static function setSessionObj($name, $value, $flag=false, $time = null){
        $_SESSION[$name] = serialize($value);
        if($flag){
            $time = (isset($time))?$time: time()+30*24*60*60;
            setcookie($name, serialize($value), $time);
        }
    }
    

    /**
     * gets cookie
     * @return any
     */
    public static function getCookieObj($name){
        $result = (isset($_COOKIE[$name]))?unserialize($_COOKIE[$name]):null;
        return $result;
    }


    /**
     * deletes a user session
     */
    public static function unsetSession($name){
        if(isset($_COOKIE[$name])){
            setcookie($name, "", time()-3600);
        }
        if(isset($_SESSION[$name])){
            unset($_SESSION[$name]);
        }
    }


    /**
     * gets a unserialized object from a specific cookie or session if it is available
     * @param string $name name cookie or session
     * @return mixed 
     */
    public static function getActiveSession($name){
        $session = null;
        $session = (isset($_SESSION[$name]))?unserialize($_SESSION[$name]):null;
        if(!isset($session))
            $session = (isset($_COOKIE[$name]))?unserialize($_COOKIE[$name]):null;
        return $session;
    }
}