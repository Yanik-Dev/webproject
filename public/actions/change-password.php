<?php

require_once __DIR__.'/../../core/init.php';

$emailService = new MailService($_CONFIG['EMAIL']);
$response = new Response();
$errors = [];

$password = trim(strip_tags($_POST['password']));
$passwordConfirm = trim(strip_tags($_POST['confirmPassword']));
$id = $_GET["id"];

if($password == "" || strlen($password) < 8){
    $error[] = "password cannot be least than 8 letters";
}else{
    if($password != $passwordConfirm){
        $error[] = "passwords do not match.";
    }
}

$user = new User();
$user->setUserId($id);
$user->setSalt(SecurityService::getSalt());
$hash = SecurityService::getHash($password, $user->getSalt());
$user->setPassword($hash);
if(UserService::changePassword($user)){
    header("Location: ../login.php");
    exit;
}

