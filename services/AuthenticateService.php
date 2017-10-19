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
                $password = SecurityService::getHash($user->getPassword(), $results["salt"]);
                if(strcmp($password, $results["password"]) == 0){
                    $sessionUser = new User();
                    $sessionUser->setUserId($results["user_id"]);
                    $sessionUser->setFirstname($results["first_name"]);
                    $sessionUser->setLastname($results["last_name"]);
                    $sessionUser->setEmail($results["email"]);
                    $sessionUser->setGender($results["gender"]);
                    $sessionUser->setIsAccountVerified($results["account_verified"]);
                    $sessionUser->setUserType($results["user_type"]);
                }
            }
        }
      
        return $sessionUser ?? null;
    }

}