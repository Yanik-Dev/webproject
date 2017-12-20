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
        $sql = "UPDATE users SET firstname = ?, lastname = ?, email=? WHERE user_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssi", $user->getFirstname(),
                                               $user->getLastname(),
                                               $user->getEmail(),
                                               $user->getUserId());
            if(!$result = $statement->execute()){
               $user = null;
            }
            
        }
        return $user ?? null;
    }

    public static function findByToken($token){
        $sql = "SELECT * FROM users where reset_token = ?";
        $data = null;
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("i", $token);
            $statement->execute();
            if($rows = $statement->get_result()){
                $row = $rows->fetch_assoc();
                    $data =  [
                        "id"=>$row['user_id'],
                        "email" => $row['email'],
                        "firstname" => $row['first_name'],
                        "lastname"=>$row['last_name'],
                        "image"=>$row['image']
                    ];
                    
            }
        }
        return $data;
    }

    public static function changePassword($user){
        $sql = "UPDATE users SET password = ?, salt = ?, reset_token = null WHERE user_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("ssi", $user->getPassword(),
                                            $user->getSalt(),
                                            $user->getUserId());
            if(!$result = $statement->execute()){
               $user = null;
            }
            
        }
        return (isset($user))?true:false;
    }

    public static function setResetToken($user){
        $sql = "UPDATE users SET reset_token = ? WHERE email = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("ss",  $user->getResetToken(),
                                            $user->getEmail());
            if(!$result = $statement->execute()){
               $user = null;
            }
            
        }
        return (isset($user))?true:false;
    }

    public static function updateImage($user){
        $path = "";
        if($statement = @Database::getInstance()->prepare("SELECT users.image FROM users WHERE user_id = ?")){
            @$statement->bind_param("i", $user->getUserId());
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    $path = $row["image"];
                }
            }
            $sql = "UPDATE users SET image = ? WHERE user_id = ? ";
            if( $statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("si", $user->getImage(),
                                                $user->getUserId());
                if(!$result = $statement->execute()){
                 $user = null;
                }
                
            }
        }
      
        return (isset($user))?["status"=> true, "path"=>$path]:["status"=> false];
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