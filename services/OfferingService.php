<?php
/**
 * 
 * @package services
 * @author Yanik Blake
 */
class OfferingService{

    
    /**
     * create a offering 
     * @param Business $offering
     * @return int offering id
     */
    public static function insert($offering){
        $sql = "INSERT INTO offerings SET offering_name = ?, offering_cost = ?, offering_description = ?, fk_business_id=?, fk_offering_category_id=?, date_created = now()";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssii", $offering->getName(),
                                            $offering->getCost(),
                                            $offering->getDescription(),
                                            $offering->getBusiness()->getId(),
                                            $offering->getCategory()->getId()
                                        );
            if(!$statement->execute()){
               echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
               Database::getInstanace()->rollback();
               return null;
            }
            $offering->setId($statement->insert_id);
        }
        return $offering->getId() ?? null;
    }

    /**
     * updates a single offering record
     * @param Offering $offering
     * @return int
     */
    public static function update($offering){
        $sql = "UPDATE offerings SET offering_name = ?, offering_cost = ?, offering_description = ?, fk_business_id=?, fk_offering_category_id=?, date_created = now() WHERE offering_id=?";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("sssiii", $offering->getName(),
                                            $offering->getCost(),
                                            $offering->getDescription(),
                                            $offering->getBusiness()->getId(),
                                            $offering->getCategory()->getId(),
                                            $offering->getId()
                                        );
            if(!$statement->execute()){
               echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
               Database::getInstanace()->rollback();
               return null;
            }
            $offering->setId($statement->insert_id);
        }
        return $offering->getId() ?? null;
    }
   

    /**
     * inserts images for an offering
     * @param Offering $offering
     * @return []
     */
    public static function insertImages($offering){
        $path = "";
        $images = $offering->getImages();
        foreach($images as $image){
            $sql = "INSERT INTO offering_images SET offering_image = ?, fk_offering_id = ? ";
            if( $statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("si", $image,
                                              $offering->getId());
                if(!$result = $statement->execute()){
                 $offering = null;
                }
                
            }
            
        }
        
    }

    /**
     * updates/inserts a feature image for a specific offering record
     * @param Offering $offering
     * @return []
     */
    public static function updateFeatureImage($offering){
        $path = "";
        if($statement = @Database::getInstance()->prepare("SELECT offering_images FROM offerings WHERE business_id = ?")){
            @$statement->bind_param("i", $offering->getId());
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    $path = $row["offering_image"];
                }
            }
            $sql = "UPDATE offering_image SET offering_image = ? WHERE fk_offering_image_id = ? ";
            if( $statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("si", $offering->getImages()->getImage(),
                                                $offering->getId());
                if(!$result = $statement->execute()){
                 $offering = null;
                }
                
            }
        }
      
        return (isset($offering))?["status"=> true, "path"=>$path]:["status"=> false];
    }

    /**
     * counts the number of offerings
     * @param int $businessId
     * @access public
     * @return int
     */
    public static function count($businessId=" "){
        $sql = "SELECT * FROM v_offerings WHERE fk_business_id LIKE CONCAT('%', ?,'%')";
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $businessId);
            $statement->execute();
            if($result = $statement->get_result()){
                $rowCount = $result->num_rows;
            }
        }
        return $rowCount ?? 0;
    }

    /**
     * checks if offering exist in a business by name
     * @param int $businessId
     * @param String $name
     * @return boolean 
     */
    public static function exist($businessId, $name){
        $sql = "SELECT * FROM offerings WHERE fk_business_id = ? AND offering_name = ?";
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("is", $businessId, $name);
            $statement->execute();
            if($result = $statement->get_result()){
                $rowCount = $result->num_rows;
            }
        }
        return (($rowCount > 0)? true: false);
    }


    /**
     * delete a specific offering record
     * @param int $id
     * @return []
     */
    public static function delete($id){
        $paths = [];
        $result = true;
        if($statement = @Database::getInstance()->prepare("SELECT offering_image FROM offering_images WHERE offering_images_id = ?")){
            @$statement->bind_param("i", $id);
            if(!$statement->execute()){ }

            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    $paths[] = $row["offering_image"] ;
                }
            }
            $sql = "DELETE FROM offerings WHERE offering_id = ?";
            if($statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("i", $id);
            
                if(!$statement->execute()){ $result = false; }
            }
        }
    
        return (isset($result))?["status"=> true, "paths"=>$paths]:["status"=> false];
    }

    /**
     * find all records base on the user id and/or the name
     * return all records if user id and/or name is not found
     * @param Offering $object
     * @param string $name
     * @return []
     */
    public static function findAll($object, $pagination=null){

        $i = 0;
        $dataSet = [];
        $imageDataSet = [];
        $sql = "SELECT * FROM v_offerings WHERE 
                                        fk_business_id LIKE CONCAT('%', ?,'%') 
                                        OR fk_user_id LIKE CONCAT('%', ?,'%') 
                                        OR offering_name LIKE CONCAT('%', ?,'%') 
                                        OR offering_description LIKE CONCAT('%', ?,'%') 
                                        OR offering_cost LIKE CONCAT('%', ?,'%') 
                                        OR offering_category LIKE CONCAT('%', ?,'%') 
                                        OR offering_type LIKE CONCAT('%', ?,'%') 
                                        ORDER BY offering_id DESC
                                        ".((isset($pagination))?"LIMIT ?,?":"");
    
        if($statement = @Database::getInstance()->prepare($sql)){
            if(isset($pagination)){
                @$statement->bind_param("sssssssii",   
                                            $object->getBusiness()->getId(),
                                            $object->getBusiness()->getOwner()->getUserId(),
                                            $object->getName(),
                                            $object->getDescription(),
                                            $object->getCost(),
                                            $object->getCategory()->getCategory(),
                                            $object->getCategory()->getType()->getType(),
                                            $pagination["start"],
                                            $pagination["limit"]
                                    );
            }else{
                @$statement->bind_param("sssssss",   
                                            $object->getBusiness()->getId(),
                                            $object->getBusiness()->getOwner()->getUserId(),
                                            $object->getName(),
                                            $object->getDescription(),
                                            $object->getCost(),
                                            $object->getCategory()->getCategory(),
                                            $object->getCategory()->getType()->getType()
                                    );
            }
           
                                            if(!$statement->execute()){
                                                echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                                                exit;
                                             }
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    if($statement = @Database::getInstance()->prepare("SELECT * FROM offering_images WHERE fk_offering_id = ?")){
                        @$statement->bind_param("i", $row["offering_id"]);
                        $statement->execute();
                        if($imageRows = $statement->get_result()){
                            while($imageRow = $imageRows->fetch_assoc()){
                                $imageDataSet[] = $imageRow["offering_image"];
                            }
                        }
                    }
                    $dataSet[] =  self::_setData($row, $imageDataSet);
                    $dataSet[$i]["endOfResults"] = $pagination["end"];
                    $i++;
                }
                
            }
        }
        return $dataSet;
    }

    /**
     * finds an offering record by ID
     * @param int $id
     * @return []
     */
    public static function findOne($id){
        $dataSet = [];
        $imageDataSet = [];
        if($statement = @Database::getInstance()->prepare("SELECT * FROM v_offerings WHERE offering_id = ?")){
            @$statement->bind_param("i", $id);
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    if($statement = @Database::getInstance()->prepare("SELECT * FROM offering_images WHERE fk_offering_id = ?")){
                        @$statement->bind_param("i", $row["offering_id"]);
                        $statement->execute();
                        if($imageRows = $statement->get_result()){
                            while($imageRow = $imageRows->fetch_assoc()){
                                $imageDataSet[] = $imageRow["offering_image"];
                            }
                        }
                    }
                    $dataSet[] =  _setData($row, $imageDataSet);
                }
                
            }
        }
        return $dataSet;
    }

    /**
     * 
     */
    private static function _setData($item, $images){
        return [
            "id"=>$item['offering_id'],
            "name" => $item['offering_name'],
            "cost" => $item['offering_cost'],
            "description"=>$item['offering_description'],
            "categoryId"=>$item['fk_offering_category_id'],
            "category" => $item["offering_category"],
            "dateCreated"=>$item['date_created'],
            "typeId"=>$item['fk_offering_type_id'],
            "type"=>$item['offering_type'],
            "businessId"=>$item['fk_business_id'],
            "businessName"=>$item['business_name'],
            "imageId"=>$item["offering_images_id"],
            "image"=>$item['offering_image'],
            "images"=>$images,
            "endOfResults"=>false
        ];
    }

}