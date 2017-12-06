<?php

/**
 * 
 * Project Configurations
 * Contains all the configurations for external services
 * such as database, uploads folder, etc.
 *
 * USAGE: 
 *  include in your php template or service
 *  then access the associative arrays by.
 *  $_CONFIG["DATABASE"]["SERVER"] to get server name
 * @author Yanik 
 */

global $_CONFIG;

//try to get configuration files
$config_data = @file_get_contents(__DIR__.'/config.conf', false);


//write configuration files to database if config file is not found
if(!$config_data){
    $_CONFIG = [
        "DATABASE" => [
            "SERVER" =>'localhost',
            "USERNAME" => 'root',
            "PASSWORD" => 'password',
            "DATABASE" => "app_db"
        ],
        "UPLOAD"=>[
            "DIRECTORY"=>"../uploads/"
        ],
        "EMAIL"=>[
            "username" =>"yanikblake@gmail.com",
            "password" =>"N@ruto123",
            "senderemail"=>"admin@webproject.com",
            "sender"=>"admin",
            "replyname"=>"no-reply",
            "replyemail"=>"noreply@webproject.com"
        ],
        "ENVIRONMENT" => "development"
    ];
    file_put_contents(__DIR__.'/config.conf', serialize($_CONFIG));
}else{
    $_CONFIG = unserialize($config_data);
}

