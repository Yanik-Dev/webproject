<?php
class AuthenticateService{

    private static function _authenticateAdminAndTeacher($request){
        $results = [];
        $sql = "Select * from users WHERE email = ?";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $request["email"]);
            $statement->execute();
            if($rows = $statement->get_result()){
                $results = $rows->fetch_assoc();
            }
            if(isset($results)){
                $hash = SecurityService::getHash($request["password"], $results["salt"]);
                #check password against db password
                if(strcmp($hash, $results["password"]) ==0){
                    
                    $token = self::generateToken($results);
                    return $token;
                }
            }
        }
      
        return $results;
    }

}