<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require_once __DIR__.'/../../common/autoload.php';
require_once '../../models/autoload.php';
require_once '../../services/autoload.php';


$response = new Response();
$errors = [];

//validation checks
if(!isset($_POST['token'])){
    $errors[] = "missing token";
}else{
    if(strcmp(SecurityService::getCRSFToken(), $_POST['token'])== 0){
        $errors[] = "tokens do not match";
    }  
}

if (trim($_POST['email']) == "") {
    $errors[] = "email is required";
}
//TODO: fix email validator
//else if(!Validator::isEmail($_POST['email'])){
//    $errors[] = "invalid email";
//}

if (trim($_POST['password']) == "") {
    $errors[] = "password is required";
}

if(count($errors) > 0){
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}

//if validation was successful
$user=new User();
$user->setEmail($_POST['email']);

$authUser = AuthenticateService::authenticate($user);

if(isset($authUser)){
    //creates user session then send back response with OK status
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($authUser);
    if($_POST['rememberMe']=="yes"){
        SessionService::setSessionObj("user", $authUser, true);
    }else{
        SessionService::setSessionObj("user", $authUser);
    }
    $response->sendResponse();
}else{
    //creates user session then send back response with UNAUTHORIZED status
    $response->setCode(ResponseCode::HTTP_UNAUTHORIZED);
    $response->setContent($authUser);
    $response->sendResponse();
}
?>