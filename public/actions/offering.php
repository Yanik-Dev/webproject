<?php
require_once __DIR__.'/../../core/init.php';

$uploadService = new UploadService($_CONFIG["UPLOAD"]["DIRECTORY"]);
$response = new Response();
$errors = [];

if(SessionService::getActiveSession("user") != null)
    $ownerId = SessionService::getActiveSession("user")->getUserId();
else
    $ownerId = '';

//checks if a file is posted thens uploads it
if(isset($_FILES['file'])){
    if(count($_FILES['file']['name']) > 0){
        $result = $uploadService->uploadMultipleFiles($_FILES['file']);
        $status = false;
        if(count($result["uploaded"]) > 0){
            $images = [];
            $offering = new Offering();
            foreach($result["uploaded"] as $path)
                $images[] = $path;
           
            $offering->setId($_GET['id']??0);
            $offering->setImages($images);
            $updatedResult = OfferingService::insertImages($offering);
            $status = $updatedResult["status"];
        }
        if(count($result["uploaded"]) < 0 || $status){
            $uploadService->removeFiles();
        }
        $response->setCode(ResponseCode::HTTP_OK);
        $response->setContent($status);
        $response->sendResponse();
        exit;
    }

    
}
 


//get images
if(isset($_GET['imagesforid'])){
    $result =  OfferingService::findAll($_GET['imagesforid']);
    $response->setCode(ResponseCode::HTTP_OK);
  
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//check if offering name is unique
if(isset($_GET['name'])){
    $result =  OfferingService::exist($_GET['businessId'], $_GET['name']);
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//check if offering name is unique
if(isset($_GET['type'])){
    $result =  OfferingCategoryService::findAll($_GET['type']);
    $response->setCode(ResponseCode::HTTP_OK);
  
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//check for delete request
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $result =  OfferingService::delete($id);
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

//get all offerings
if(isset($_GET["page"])){
    $search = $_GET["search"] ?? '';
    $business =new Business();
    $category=new OfferingCategory();
    $offering=new Offering();
    $user=new User();
    $type=new OfferingType();
    
    //set User Object
    $user->setUserId($ownerId);

    //set Offering Type Object
    $type->setId($_GET['type_id']??'');

    //set Category Object
    $category->setId($_GET['category_id']??'');
    $category->setType($type);
    
    //set Business Object
    $business->setId($_GET['business_id']??'');
    $business->setOwner($user);
    
    //set Offering Object
    $offering->setName($search);
    $offering->setCost($search);
    $offering->setDescription($search);
    $offering->setBusiness($business);
    $offering->setCategory($category);

    $count = OfferingService::count($_GET['business_id']??'');
    $currentPage = $_GET["page"]??1;
    $limit = $_GET["limit"]??1;
    $total = ceil($count/$limit);
    $endOfResult = false;
    //echo json_encode([$limit, $total, $count, $currentPage]);
    //exit;
    if($currentPage > $total){
        $currentPage = $total;
        $endOfResult = true;
    }
    if($currentPage < 1){
        $currentPage = 1;
    }

    $start = ($currentPage - 1) * $limit;
    $result = OfferingService::findAll($offering, [
        'start'=>$start,
        'limit'=>$limit,
        'end'=>$endOfResult
        ]);

    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent($result);
    $response->sendResponse();
    exit;
}

//get posted values
$name = ucwords(trim(strip_tags($_POST['name'])));
$description = Utility::sanitize($_POST['description']);
$cost = Utility::sanitize($_POST['cost']);
$businessId = Utility::sanitize($_POST['businessId']);
$categoryId = Utility::sanitize($_POST['categoryId']);

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
    $errors[] = "offering name is required";
}

if ($description != "") {
    if(strlen($description) > 255 ){
        $errors[] = "descripton cannot be greater than 255 characters";
    }
}else{
    $errors[] = "no description";
}


if($cost == ""){
    $errors[] = "cost is required";
}

if(!is_numeric($businessId) || $businessId == 0){
    $errors[] = "a business id is required";
}

if(!is_numeric($categoryId) || $categoryId == 0){
    $errors[] = "a category id is required";
}


if(count($errors) > 0){
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}

//if validation was successful
$business =new Business();
$category=new OfferingCategory();
$offering=new Offering();

//set Category Object
$category->setId($categoryId);

//set Business Object
$business->setId($businessId);

//set Offering Object
$offering->setName($name);
$offering->setCost($cost);
$offering->setDescription($description);
$offering->setBusiness($business);
$offering->setCategory($category);


//register a new offering if id is empty otherwise updates an
//existing offering
if($_POST['id'] ==""){
    $id = OfferingService::insert($offering);
    $response->setMessage('inserted successfully');
}else{
    $offering->setId($_POST['id']);
    $id = OfferingService::update($offering);
    $response->setMessage('updated successfully');
}

if(isset($id)){
    //send back response with registered offering and OK status
    $offering->setId($id);  
    $response->setCode(ResponseCode::HTTP_OK);
    $response->setContent(["id"=>$id]);
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