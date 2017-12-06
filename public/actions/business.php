<?php

require_once __DIR__.'/../../core/init.php';
require_once __DIR__.'/../../lib/phpqrcode/qrlib.php';

$uploadService = new UploadService($_CONFIG["UPLOAD"]["DIRECTORY"]);
$response = new Response();
$errors = [];

if(SessionService::getActiveSession("user"))
    $ownerId = SessionService::getActiveSession("user")->getUserId();
else
    $ownerId = '';

//checks if a file is posted thens uploads it
if(isset($_FILES['file'])){
    if(strcmp($_FILES['file']['name'], "") != 0){
        $result = $uploadService->uploadSingleFile($_FILES['file']);
        $status = false;
        if(strcmp('', $result["uploadedFile"]) != 0){
            $business=new Business();
            $business->setId($_GET['id']??0);
            $business->setLogo($result["uploadedFile"]);
            $updatedResult = BusinessService::updateLogo($business);
            $status = $updatedResult["status"];
        }
        if(strcmp('', $result["uploadedFile"]) == 0 || !$status){
            $uploadService->removeFiles();
        }else if(strcmp('', $updatedResult["path"]) !=0){
            $uploadService->removeFile($updatedResult["path"]);
        }

        $response->setCode(ResponseCode::HTTP_OK);
        $response->setContent($status);
        $response->sendResponse();
        exit;

    }
}
 

//check if business name is unique
if(isset($_GET['name'])){
    $result =  BusinessService::exist($_GET['name']);
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//check for delete request
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $result =  BusinessService::delete($id);
    if($result["status"]){
        $imagesToRemove = $result["paths"];
        foreach($imagesToRemove as $imageToRemove){
            $uploadService->removeFile($imageToRemove);
        }

        $response->setCode(ResponseCode::HTTP_OK);
        $response->setContent($result);
        $response->sendResponse();
    }
    exit;
}

//get all businesses
if(isset($_GET["page"])){
    $search = $_GET["search"] ?? '';
    $result = BusinessService::findAll($ownerId, $search);
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//get posted values
$name = ucwords(trim(strip_tags($_POST['name'])));
$description = Utility::sanitize($_POST['description']);
$street = Utility::sanitize($_POST['street']);
$city = Utility::sanitize($_POST['city']);
$province = Utility::sanitize($_POST['province']);
$mobile = Utility::sanitize($_POST['mobile']);
$telephone = Utility::sanitize($_POST['telephone']);
$website = strtolower(Utility::sanitize($_POST['website']));
$email = strtolower(Utility::sanitize($_POST['email']));
$token = $_POST['token'];

//validation checks
if(!isset($token)){
    $errors[] = "missing token";
}else{
    if(strcmp(SecurityService::getToken("crsf_token"), $token)!= 0){
        $errors[] = "tokens do not match";
    }  
}

if($ownerId == "") {
    $errors[] = "owner id is missing";
}

if($name == "") {
    $errors[] = "business name is required";
}

if ($description != "") {
    if(strlen($description) > 255 ){
        $errors[] = "descripton cannot be greater than 255 characters";
    }
}else{
    $errors[] = "no description";
}


if($website != ""){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "invalid email. A valid email is required";
    }
}

if($website != ""){
    if (!filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = "invalid website url";
    }
}

if($telephone != ""){
   if(Validator::isPhoneNumber($telephone) == 0){
        $errors[] = "invalid contact number 2";
   }
}

if($mobile == ""){
    if(Validator::isPhoneNumber($mobile) == 0){
         $errors[] = "invalid contact number 1";
    }
}

if(count($errors) > 0){
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}

//if validation was successful
$business=new Business();
$owner =new User();
$address =new Address();
$contactInformation =new ContactInformation();

//set Address Object
$address->setStreet($street);
$address->setCity($city);
$address->setProvince($province);

//set ContactInformation Object
$contactInformation->setMobile($mobile);
$contactInformation->setTelephone($telephone);
$contactInformation->setWebsite($website);
$contactInformation->setEmail($email);

//set Business Object
$owner->setUserId($ownerId);
$business->setName($name);
$business->setDescription($description);
$business->setOwner($owner);
$business->setIsVerified("NO");
$business->setContactInformation($contactInformation);
$business->setAddress($address);

$qrCodeFilename = $uploadService->generateRandomFileName(str_replace(' ','',$name));
QRcode::png(''.$business->getName().'\n'.$contactInformation->getMobile(), $_CONFIG["UPLOAD_DIRECTORY"].$qrCodeFilename); 

//register a new business if id is empty otherwise updates an
//existing business
if($_POST['id'] ==""){
    $business->setContactQrCode($qrCodeFilename);
    $id = BusinessService::register($business);
    $response->setMessage('inserted successfully');
}else{
    $oldBusinessInfo = BusinessService::findOne($_POST['id']);
    $oldQrCode = $oldBusinessInfo["contactQrCode"];
    $business->setId($_POST['id']);
    $business->setContactQrCode($qrCodeFilename);
    $id = BusinessService::update($business);
    $response->setMessage('updated successfully');

    //remove old qrcode if update was successful
    if($id){
        $uploadService->removeFile($oldQrCode);
    }
}

if(isset($id)){
    //send back response with registered business and OK status
    $business->setId($id);  
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent([
        "id"=>$business->getId(),
        "name" => $business->getName(),
        "description" => $business->getDescription(),
        "street"=>$business->getAddress()->getStreet(),
        "city"=>$business->getAddress()->getCity(),
        "province"=>$business->getAddress()->getProvince(),
        "website"=>$business->getContactInformation()->getWebsite(),
        "mobile"=>$business->getContactInformation()->getMobile(), 
        "telephone"=>$business->getContactInformation()->getTelephone(),
        "email"=>$business->getContactInformation()->getEmail(),
        "contactQrCode"=>$business->getContactQrCode()
    ]);
    $response->sendResponse();
}else{
    //remove uploaded qrcode
    UploadService::removeFile($qrCodeFilename);

    //send back empty response with UNAUTHORIZED status
    $response->setCode(ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
    $response->setMessage('');
    $response->setContent([]);
    $response->sendResponse();
}

?>