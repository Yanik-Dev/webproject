<?php
session_start();

if (!isset($_POST['email'])) {
    
} 

if (!isset($_POST['password'])) {
   
} 

$user=new User();
$user->setEmail($_POST['email']);

$authUser = AuthenticateService::authenticate($user);

if($authUser->getUsername() != null){
    $password = Security::getHash($_POST['password'], $authUser->getSalt());
    if(strcmp($password, $authUser->getPassword()) == 0){
        $sessionUser = new User();
        $sessionUser->setId($user->getId());
        $sessionUser->setFirstName($user->getFirstName());
        $sessionUser->setLastName($user->getLastName());
        $sessionUser->setEmail($user->getUsername());
        $sessionUser->setGender($user->getGender());
        $sessionUser->setAccountVerified($user->getAccountVerified());
        $sessionUser->setUserType($user->getRole());
        SessionService::setSessionObj("user", $sessionUser);
    }
}
?>