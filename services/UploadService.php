<?php

class UploadService{
    private $_uploadPath;
    private $_uploadedFiles = [];
    private $_validations = [];
    
    public function __construct($uploadDirectory, $validations=[]) {
        $this->_uploadPath = $uploadDirectory;
        $this->_validations= $validations;
    }
    
    
    /**
     * uploads a single file
     * @param $_FILES $file
     * @return [
     *      "uploadedFile"=>string
     *      "status"=> string
     * ]
     */
    public function uploadSingleFile($file){
        $status = "error";
        $result = "";
        $randomFilename = $this->generateRandomFileName($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $this->_uploadPath.$randomFilename)) {
            $status = "ok";
            $result = $randomFilename;
            $this->_uploadedFiles[] = $randomFilename;
        } 
        
        return [
            "uploadedFile" => $randomFilename,
            "status" => $status
        ];
    }
    
    /**
     * upload multiple files
     * @param array $files
     * @return [
     *      "uploaded"=>[]
     *      "failed"=>[]
     * ]
     */
    public function uploadMultipleFiles($files = []){
        $notUploadedCount = 0;
        $uploadedCount = 0;
        $filesNotUploaded = [];
        foreach($files["tmp_name"] as $path){
            $count = $uploadedCount+$notUploadedCount;
            $randomFilename = $this->generateRandomFileName($files["name"][$count]);
            if (!$j = move_uploaded_file($path, $this->_uploadPath.$randomFilename)) {
                $filesNotUploaded[]= $randomFilename;
                $notUploadedCount++;
            }else{ 
                $this->_uploadedFiles[]=$randomFilename;
                $uploadedCount++;
            }
        }
        return [
            "uploaded" => $this->_uploadedFiles,
            "failed" => $filesNotUploaded
        ];
    }
    

    /**
     * remove all the currently uploaded files
     */
    public function removeFiles(){
        if(count($this->_uploadedFiles) > 0){
            foreach($this->_uploadedFiles as $path){
                if(@unlink($this->_uploadPath.$path)){
                    continue;
                }
            }
        }
    }

    /**
     * removes a specific file
     * @param string $file
     */
    public function removeFile($file){
        @unlink($this->_uploadPath.$file);
    }
    

    /**
     * generate a random file name
     * @param $filename 
     * @return string filename
     */
    public function generateRandomFileName($filename){
        $temp = explode(".", $filename);
        $randomFilename = $temp[0].'_'.round(microtime(true));
        return $randomFilename;
    }


    /**
     * enforce validations
     * @param array $validation
     * @param string $filename
     * @throws Exception
     */
    public function validate($filename){
        if(isset($this->_validation["fileTypes"])){
            $types = implode("|", $this->_validation["fileTypes"]);
            if(!preg_match('/\\.('.$types.')$/i', $filename)){
                throw Exception('Invalid file type. Only');
            }
        }
        if(isset($this->_validation["fileSize"])){

        }
        
    }

    
}


