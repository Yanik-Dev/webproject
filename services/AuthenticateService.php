<?php
require_once __DIR__.'/../common/autoload.php';

class AuthenticateService{

    public static function authenticate($user){
        $sql = "Select * from users WHERE email = ?";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $user->getEmail());
            $statement->execute();
            if($rows = $statement->get_result()){
                $results = $rows->fetch_assoc();
            }
            if(isset($results)){
                $password = SecurityService::getHash($user->getEmail(), $results["salt"]);
                if(strcmp($password, $user->getPassword()) == 0){
                    $sessionUser = new User();
                    $sessionUser->setId($user->getId());
                    $sessionUser->setFirstname($user->getFirstname());
                    $sessionUser->setLastname($user->getLastname());
                    $sessionUser->setEmail($user->getUsername());
                    $sessionUser->setGender($user->getGender());
                    $sessionUser->setAccountVerified($user->getAccountVerified());
                    $sessionUser->setUserType($user->getRole());
                }
            }
        }
      
        return $sessionUser ?? null;
    }

}