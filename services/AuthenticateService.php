<?php
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
                if(strcmp($password, $authUser->getPassword()) == 0){
                    $sessionUser = new User();
                    $sessionUser->setId($user->getId());
                    $sessionUser->setFirstName($user->getFirstName());
                    $sessionUser->setLastName($user->getLastName());
                    $sessionUser->setEmail($user->getUsername());
                    $sessionUser->setGender($user->getGender());
                    $sessionUser->setAccountVerified($user->getAccountVerified());
                    $sessionUser->setUserType($user->getRole());
                }
            }
        }
      
        return $sessionUser;
    }

}