<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require_once '../../models/autoload.php';
require_once '../../services/autoload.php';


$response = new Response();


if(isset($_POST['token'])){
    if(strcmp(SecurityService::getCRSFToken(), $_POST['token'])== 0){
        
    }  
}


if (!isset($_POST['email'])) {
    
} 

if (!isset($_POST['password'])) {
   
} 

$user=new User();
$user->setEmail($_POST['email']);

$authUser = AuthenticateService::authenticate($user);

if(isset($authUser)){
    //creates user session then send back response with OK status
    $response->setContent($authUser);
    $response->setCode(ResponseCode::HTTP_OK);
    SessionService::setSessionObj("user", $authUser);
    echo json_encode($response);
}else{
    //creates user session then send back response with UNAUTHORIZED status
    $response->setContent($authUser);
    $response->setCode(ResponseCode::HTTP_UNAUTHORIZED);
    echo json_encode($response);
}
?>