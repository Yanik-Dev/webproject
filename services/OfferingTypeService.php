<?php
/**
 * 
 * @package services
 * @author Yanik Blake
 */
class OfferingTypeService{

   
    public static function findAll(){
        $sql = "SELECT * from offering_types";
        $dataSet = [];
        if($statement = @Database::getInstance()->prepare($sql)){
            $statement->execute();
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    
                    $dataSet[] =  [
                        "id"=>$row['offering_type_id'],
                        "type" => $row['offering_type'],
                    ];
                    
                }
            }
        }
        return $dataSet;
    }

   
}