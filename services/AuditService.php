<?php
/**
 * 
 * @package services
 * @author Yanik Blake
 */
class AuditService{

    /**
     * log user actions 
     * @param Audit $audit
     * @return int business id
     */
    public static function log($audit){
        Database::getInstance()->autocommit(false);
        $sql = "INSERT INTO audit_owner SET user_id = ?, action = ?, platform=?";
        if( $statement = @Database::getInstance()->prepare($sql)){
            @$statement->bind_param("iss", $audit->getOwner()->getId(), $audit->getAction(), $_SERVER['HTTP_USER_AGENT']);
            if(!$statement->execute()){
               echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
               Database::getInstanace()->rollback();
               return null;
            }
            $auditTableId = $statement->insert_id;
            $sql = "INSERT INTO audit_actions SET fk_owner_id =?, old_value =?, new_value=?";
            if($statement = @Database::getInstance()->prepare($sql)){
                @$statement->bind_param("iss", $auditTableId);
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    Database::getInstance()->rollback();
                    return null;
                }
                Database::getInstance()->commit();
                

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

    public function findAll(){

    }
}