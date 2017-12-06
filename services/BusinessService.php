<?php
/**
 * 
 * @package services
 * @author Yanik Blake
 */
class BusinessService{

    /**
     * create a business profile
     * @param Business $business
     * @return int business id
     */
    public static function register($business){
        Database::getInstance()->autocommit(false);
        $sql = "INSERT INTO businesses SET business_name = ?, contact_qrcode = ?, business_description = ?, fk_user_id=?, publish_business = 'NO', is_verified ='NO', date_created = now()";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssi", $business->getName(),
                                            $business->getContactQrCode(),
                                            $business->getDescription(),
                                            $business->getOwner()->getUserId());
            if(!$statement->execute()){
               echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
               Database::getInstanace()->rollback();
               return null;
            }
            $business->setId($statement->insert_id);
            $sql = "INSERT INTO addresses SET street = ?, city = ?, province = ?, fk_business_id = ?";
            if($statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("sssi", $business->getAddress()->getStreet(),
                                                $business->getAddress()->getCity(),
                                                $business->getAddress()->getProvince(),
                                                $business->getId());
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    Database::getInstance()->rollback();
                    return null;
                }


                $sql = "INSERT INTO contact_information SET contact_number_1 = ?, contact_number_2 = ?, contact_email = ?, website = ?, fk_business_id = ?";
                if($statement = @Database::getInstance()->prepare($sql)){
                    @$statement->bind_param("ssssi", $business->getContactInformation()->getMobile(),
                                                    $business->getContactInformation()->getTelephone(),
                                                    $business->getContactInformation()->getEmail(),
                                                    $business->getContactInformation()->getWebsite(),
                                                    $business->getId());
                    if(!$statement->execute()){
                        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                        Database::getInstance()->rollback();
                        return null;
                    }
                    Database::getInstance()->commit();
                }else{
                    echo "3 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
                    Database::getInstance()->rollback();
                    return null;
                }


            }else{
                echo "2 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
                Database::getInstance()->rollback();
                return null;
            }
            
        }else{
            echo "1 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
            Database::getInstance()->rollback();
            return null;
        }
      
        return $business->getId() ?? null;
    }

    public static function update($business){
        $sql = "UPDATE businesses SET business_name = ?, business_description = ?, contact_qrcode = ? WHERE business_id = ? ";
        Database::getInstance()->autocommit(false);
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssi", $business->getName(),
                                            $business->getDescription(),
                                            $business->getContactQrCode(),
                                            $business->getId()
                                        );
            if(!$statement->execute()){
               echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
               Database::getInstanace()->rollback();
               return null;
            }
            $sql = "UPDATE addresses SET street = ?, city = ?, province = ? WHERE fk_business_id = ?";
            if($statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("sssi", $business->getAddress()->getStreet(),
                                                $business->getAddress()->getCity(),
                                                $business->getAddress()->getProvince(),
                                                $business->getId());
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    Database::getInstance()->rollback();
                    return null;
                }


                $sql = "UPDATE contact_information SET contact_number_1 = ?, contact_number_2 = ?, contact_email = ?, website = ? WHERE fk_business_id = ?";
                if($statement = @Database::getInstance()->prepare($sql)){
                    @$statement->bind_param("ssssi", $business->getContactInformation()->getMobile(),
                                                    $business->getContactInformation()->getTelephone(),
                                                    $business->getContactInformation()->getEmail(),
                                                    $business->getContactInformation()->getWebsite(),
                                                    $business->getId());
                    if(!$statement->execute()){
                        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                        Database::getInstance()->rollback();
                        return null;
                    }
                    Database::getInstance()->commit();
                }else{
                    echo "3 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
                    Database::getInstance()->rollback();
                    return null;
                }


            }else{
                echo "2 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
                Database::getInstance()->rollback();
                return null;
            }
            
        }else{
            echo "1 Execute failed: (" . Database::getInstance()->errno . ") " . Database::getInstance()->error;
            Database::getInstance()->rollback();
            return null;
        }
      
        return $business->getId() ?? null;
    }


    public static function changePublish($business){
        $sql = "UPDATE businesses SET publish_business = ? WHERE business_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("si", $business->getPublished(),
                                          $business->getId());
            if(!$result = $statement->execute()){
               $business = null;
            }
            
        }
      
        return (isset($business))?true:false;
    }

    public static function changeVerified($business){
        $sql = "UPDATE businesses SET is_verified = ? WHERE business_id = ? ";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("si", $business->getIsVerified(),
                                            $business->getId());
            if(!$result = $statement->execute()){
               $business = null;
            }
            
        }
      
        return (isset($business))?true:false;
    }
    
    public static function updateLogo($business){
        $path = "";
        if($statement = @Database::getInstance()->prepare("SELECT business_logo FROM businesses WHERE business_id = ?")){
            @$statement->bind_param("i", $business->getId());
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    $path = $row["business_logo"];
                }
            }
            $sql = "UPDATE businesses SET business_logo = ? WHERE business_id = ? ";
            if( $statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("si", $business->getLogo(),
                                                $business->getId());
                if(!$result = $statement->execute()){
                 $business = null;
                }
                
            }
        }
      
        return (isset($business))?["status"=> true, "path"=>$path]:["status"=> false];
    }

    public static function exist($name){
        $sql = "SELECT * FROM businesses WHERE business_name = ?";
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $name);
            $statement->execute();
            if($result = $statement->get_result()){
                $rowCount = $result->num_rows;
            }
        }
        return (($rowCount > 0)? true: false);
    }

    public static function delete($id){
        $paths = [];
        $result = true;
        if($statement = @Database::getInstance()->prepare("SELECT business_logo, contact_qrcode FROM businesses WHERE business_id = ?")){
            @$statement->bind_param("i", $id);
            if(!$statement->execute()){ }

            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    $paths = [$row["business_logo"], $row["contact_qrcode"]];
                }
            }
            $sql = "DELETE FROM businesses WHERE business_id = ?";
            if($statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("i", $id);
            
                if(!$statement->execute()){ $result = false; }
            }
        }
    
        return (isset($result))?["status"=> true, "paths"=>$paths]:["status"=> false];
    }

    public static function findAll($owner_id, string $name=''){
        $sql = "SELECT * FROM businesses LEFT JOIN addresses ON addresses.fk_business_id = businesses.business_id 
                LEFT JOIN contact_information ON contact_information.fk_business_id = businesses.business_id
                WHERE  fk_user_id LIKE CONCAT('%', ?,'%') AND business_name LIKE CONCAT('%', ?,'%')";
        $dataSet = [];
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("ss", $owner_id, $name);
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    
                    $dataSet[] =  [
                        "id"=>$row['business_id'],
                        "name" => $row['business_name'],
                        "description" => $row['business_description'],
                        "logo"=>$row['business_logo'],
                        "street"=>$row['street'],
                        "city"=>$row['city'],
                        "province"=>$row['province'],
                        "website"=>$row['website'],
                        "mobile"=>$row['contact_number_1'],
                        "telephone"=>$row['contact_number_2'],
                        "email"=>$row['contact_email'],
                        "isVerified"=>$row['is_verified'],
                        "isPublished"=>$row['publish_business'],
                        "contactQrCode"=>$row['contact_qrcode']
                    ];
                    
                }
            }
        }
        return $dataSet;
    }

    public static function findOne($id){
        $sql = "SELECT * FROM businesses LEFT JOIN addresses ON addresses.fk_business_id = businesses.business_id 
                LEFT JOIN contact_information ON contact_information.fk_business_id = businesses.business_id
                WHERE business_id = ?";
        $data = null;
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("i", $id);
            $statement->execute();
            if($rows = $statement->get_result()){
                $row = $rows->fetch_assoc();
                    $data =  [
                        "id"=>$row['business_id'],
                        "name" => $row['business_name'],
                        "description" => $row['business_description'],
                        "logo"=>$row['business_logo'],
                        "street"=>$row['street'],
                        "city"=>$row['city'],
                        "province"=>$row['province'],
                        "website"=>$row['website'],
                        "mobile"=>$row['contact_number_1'],
                        "telephone"=>$row['contact_number_2'],
                        "email"=>$row['contact_email'],
                        "isVerified"=>$row['is_verified'],
                        "isPublished"=>$row['publish_business'],
                        "contactQrCode"=>$row['contact_qrcode']
                    ];
                    
            }
        }
        return $data;
    }


}