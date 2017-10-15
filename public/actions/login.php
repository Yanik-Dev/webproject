<?php
session_start();
require_once '../models/autoload.php';
require_once '../services/autoload.php';


$response = new Response();

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
    //creates user session then send back response with OK status
    $response->setContent($authUser);
    $response->_code(ResponseCode::HTTP_OK);
    SessionService::setSessionObj("user", $authUser);
    return json_encode($response);
}else{
    //creates user session then send back response with UNAUTHORIZED status
    $response->setContent($authUser);
    $response->_code(ResponseCode::HTTP_UNAUTHORIZED);
    return json_encode($response);
}
?>