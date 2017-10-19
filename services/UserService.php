<?php


class UserService{

    public static function register($user){
        $sql = "INSERT INTO users SET first_name = ?, last_name = ?, gender=?, email = ?, user_type =?, password = ?, salt = ?, date_created = now()";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssssss", $user->getFirstname(),
                                               $user->getLastname(),
                                               $user->getGender(),
                                               $user->getEmail(),
                                               $user->getUserType(),
                                               $user->getPassword(),
                                               $user->getSalt());
            if($statement->execute()){
               $id = $statement->insert_id;
            }
            
        }
      
        return $id ?? null;
    }

    public static function update($user){
        $sql = "UPDATE users SET firstname = ?, lastname = ?, gender=? WHERE user_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssi", $user->getFirstname(),
                                               $user->getLastname(),
                                               $user->getGender(),
                                               $user->getUserId());
            if(!$result = $statement->execute()){
               $user = null;
            }
            
        }
      
        return $user ?? null;
    }


    public static function changePassword($user){
        $sql = "UPDATE users SET password = ?, salt = ? WHERE user_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssi", $user->getPassword(),
                                            $user->getSalt(),
                                            $user->getUserId());
            if(!$result = $statement->execute()){
               $user = null;
            }
            
        }
      
        return (isset($user))?true:false;
    }

    public static function exist($email){
        $sql = "SELECT * FROM users WHERE email = ?";
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $email);
            $statement->execute();
            if($result = $statement->get_result()){
                $rowCount = $result->num_rows;
            }
        }
        return (($rowCount > 0)? true: false);
    }

}