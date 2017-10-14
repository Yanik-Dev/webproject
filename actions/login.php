<?php
session_start();
require_once '../models/autoload.php';
require_once '../services/autoload.php';

if(!isset($_POST['submit'])){

}

if(strcmp(SecurityService::getCRSFToken(), $_POST['token'])== 0){

}

if (!isset($_POST['email'])) {
    
} 

if (!isset($_POST['password'])) {
   
} 

$user=new User();
$user->setEmail($_POST['email']);

$authUser = AuthenticateService::authenticate($user);

if(isset($authUser)){
    SessionService::setSessionObj("user", $authUser);
    return json_encode($authUser);
}
?>