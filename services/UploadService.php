<?php

class UploadService{
    private $_uploadPath;
    private $_uploadedFiles = [];
    
    public function __construct($uploadDirectory) {
        $this->_uploadPath = $uploadDirectory;
    }
    
    public function uploadSingleFile($file){
        $status = "error";
        $result = "";
        $randomFilename = $this->_generateRandomFileName($file["name"]);
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
    
    public function uploadMultipleFiles($files = []){
        $notUploadedCount = 0;
        $uploadedCount = 0;
        $filesNotUploaded = [];
        foreach($files["tmp_name"] as $path){
            $count = $uploadedCount+$notUploadedCount;
            $randomFilename = $this->_generateRandomFileName($files["name"][$count]);
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
    
    
    public function removeFiles(){
        if(count($this->_uploadedFiles) > 0){
            foreach($this->_uploadedFiles as $path){
                if(@unlink($this->_uploadPath.$path)){
                    continue;
                }
            }
        }
    }

    public function removeFile($file){
        @unlink($this->_uploadPath.$file);
    }
    
    /**
     * generate a random file name
     * @param $filename 
     * @return random filename
     */
    private function _generateRandomFileName($filename){
        $temp = explode(".", $filename);
        $randomFilename = $temp[0].'_'.round(microtime(true)) . '.' . end($temp);
        return $randomFilename;
    }
}
