<?php

require_once __DIR__.'/../../core/init.php';

$emailService = new MailService($_CONFIG['EMAIL']);
$response = new Response();
$errors = [];

if(!isset($_GET['email'])){
    $errors[]="missing email";
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}
$passwordToken = md5(uniqid(rand(), true));

$user=new User();
$user->setEmail($_GET['email']);
$user->setResetToken($passwordToken);
if(UserService::setResetToken($user)){
    $emailService->setRecipents([
        [
            "name"=>'',
            "email"=>$user->getEmail()
        ]
    ]);
    $emailService->setSubject('Reset Password!');
    $emailService->setBody("To reset your password click here -> <a href='http://".$_SERVER['SERVER_NAME']."/reset-password.php?token=".$passwordToken."'>Reset Link</a>");
    $emailService->sendMail();
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent(true);
    $response->sendResponse();
    exit;
}
$response->setCode(ResponseCode::HTTP_BAD_REQUEST);
$response->setErrors($errors);
$response->sendResponse();
exit;





?>