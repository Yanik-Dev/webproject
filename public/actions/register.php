<?php

require_once __DIR__.'/../../core/init.php';

$response = new Response();
/*
$emailService = new MailService($_CONFIG['EMAIL']);

$emailService->setRecipents([
                                [
                                    "name"=>"Yanik", 
                                    "email"=>"yanikblake@yahoo.com"
                                ]
                            ]);
$emailService->setSubject('testing');
$emailService->setBody("<p>Testing</p>");
$emailService->sendMail();
exit;*/
$errors = [];

//check if email is unique
if(isset($_GET)){
    if(isset($_GET['email'])){
        $result = UserService::exist($_GET['email']);
        $response->setCode(ResponseCode::HTTP_OK);
        $response->setContent($result);
        $response->sendResponse();
        exit;
    }
}

//get posted values
$firstname = Utility::sanitize($_POST['firstname']);
$lastname = Utility::sanitize($_POST['lastname']);
$email = strtolower(Utility::sanitize($_POST['email']));
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirmPassword']);
$gender = Utility::sanitize($_POST['gender']);
$token = $_POST['token'];

//validation checks
if(!isset($token)){
    $errors[] = "missing token";
}else{
    if(strcmp(SecurityService::getToken("crsf_token"), $token)== 0){
        $errors[] = "tokens do not match";
    }  
}

if ($email == "") {
    $errors[] = "email is required";
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     $errors[] = "invalid email";
}

if ($password == "") {
    $errors[] = "password is required";
}
else if(strlen($password) < 8){
    $errors[] = "password must at least 8 characters";
}else{
    if(strcmp($password, $confirmPassword)!=0){
        $errors[] = "passwords do not match";
    }
}

if(count($errors) > 0){
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}

//if validation was successful
$user=new User();
$user->setEmail($email);
$user->setFirstname($firstname);
$user->setLastname($lastname);
$user->setUserType("BUSINESS");
$user->setGender($gender);

//hash password
$user->setSalt(SecurityService::getSalt());
$hash = SecurityService::getHash($password, $user->getSalt());
$user->setPassword($hash);
$id = UserService::register($user);

if(isset($id)){
    //send back response with registered user and OK status
    $user->setUserId($id);
    $user->setPassword(null);
    $user->setSalt(null);
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($user);
    $response->sendResponse();
}else{
    //send back empty response with UNAUTHORIZED status
    $response->setCode(ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
    $response->setContent([]);
    $response->sendResponse();
}
?>