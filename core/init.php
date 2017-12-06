<?php
/**
 * initialize all global packages and settings
 * 
 */
session_start();

//include require classes and files
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../common/autoload.php';
require_once __DIR__.'/../models/autoload.php';
require_once __DIR__.'/../services/autoload.php';


//checks the environment of the app
if($_CONFIG["ENVIRONMENT"] === "production"){


}else{
    //enable errors
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
 
