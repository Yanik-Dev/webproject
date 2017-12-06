<?php
/**
 * 
 * @package services
 * @author Yanik Blake
 */
class OfferingCategoryService{

   
    public static function findAll($type_id = ''){
        $sql = "SELECT * from offering_categories WHERE fk_offering_type_id LIKE CONCAT('%', ?,'%')";
        $dataSet = [];
        if($statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("s", $type_id);
            if(!$statement->execute()){
                echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                Database::getInstanace()->rollback();
                exit;
             }
            
            if($rows = $statement->get_result()){
                while($row = $rows->fetch_assoc()){
                    
                    $dataSet[] =  [
                        "id"=>$row['offering_category_id'],
                        "category" => $row['offering_category'],
                        "type_id" => $row['fk_offering_type_id']
                    ];
                    
                }
            }
        }
        return $dataSet;
    }

   
}