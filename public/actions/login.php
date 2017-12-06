<?php

require_once __DIR__.'/../../core/init.php';

$response = new Response();
$errors = [];

//check if User requested to logout
if(isset($_GET['logout'])){

    if(isset($_GET['token'])){
        if(strcmp(SecurityService::getToken("token"), $_GET['token']) == 0){
            SessionService::unsetSession("user");
            header('Location: ../login.php');
            exit;
        }  
    }
    header('Location: ../'.strtolower($_GET['page']).'.php');
    exit;
}


//validation checks
if(!isset($_POST['token'])){
    $errors[] = "missing token";
}else{
    if(strcmp(SecurityService::getToken("crsf_token"), $_POST['token']) != 0){
        $errors[] = "tokens do not match";
    }  
}

if (trim($_POST['email']) == "") {
    $errors[] = "email is required";
}
else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errors[] = "invalid email";
}

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
$user->setPassword($_POST['password']);

$authUser = AuthenticateService::authenticate($user);

if(isset($authUser)){
    //creates user session then send back response with OK status
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($authUser);
    if(isset($_POST['rememberMe'])){
        SessionService::setSessionObj("user", $authUser, true);
    }else{
        SessionService::setSessionObj("user", $authUser);
    }
    $response->sendResponse();
}else{
    //send back response with UNAUTHORIZED status
    $response->setCode(ResponseCode::HTTP_UNAUTHORIZED);
    $response->setContent($authUser);
    $response->sendResponse();
}
?>