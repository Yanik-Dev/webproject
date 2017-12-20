<?php

require_once __DIR__.'/../../core/init.php';

$uploadService = new UploadService($_CONFIG["UPLOAD"]["DIRECTORY"]);
$response = new Response();
$errors = [];

$id = SessionService::getActiveSession("user")->getUserId();

//checks if a file is posted thens uploads it
if(isset($_FILES['file'])){
    if(strcmp($_FILES['file']['name'], "") != 0){
        $result = $uploadService->uploadSingleFile($_FILES['file']);
        $status = false;
        if(strcmp('', $result["uploadedFile"]) != 0){
            $user=new User();
            $user->setUserId($id??0);
            $user->setImage($result["uploadedFile"]);

            Utility::createThumbnail($_CONFIG["UPLOAD"]["DIRECTORY"].$result["uploadedFile"],
                                        $_CONFIG["UPLOAD"]["DIRECTORY"].$result["uploadedFile"], 290);
            $updatedResult = UserService::updateImage($user);
            $status = $updatedResult["status"];
        }
        if(strcmp('', $result["uploadedFile"]) == 0 || !$status){
            $uploadService->removeFiles();
        }else if(strcmp('', $updatedResult["path"]) !=0){
            $uploadService->removeFile($updatedResult["path"]);
            $user = SessionService::getActiveSession("user");
            $user->setImage($result["uploadedFile"]);
            SessionService::setSessionObj("user", $user);
        }
   
        $response->setCode(ResponseCode::HTTP_OK);
        $response->setContent($status);
        $response->sendResponse();
        exit;

    }
}
 

//check for delete request
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $result =  UserService::delete($id);
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

if(!isset($_GET['option'])){
    $errors[] = "no option provided";
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}

$option = $_GET['option'];


//get posted values
$firstname = Utility::sanitize($_POST['firstname']);
$lastname = Utility::sanitize($_POST['lastname']);
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
 
if($option == "changepassword"){
    $password = trim(strip_tags($_POST['password']));
    $passwordConfirm = trim(strip_tags($_POST['confirmPassword']));

    if($password == "" || strlen($password) < 8){
        $error[] = "password cannot be least than 8 letters";
    }else{
        if($password != $passwordConfirm){
            $error[] = "passwords do not match.";
        }
    }

    if(count($errors) > 0){
        $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
        $response->setErrors($errors);
        $response->sendResponse();
        exit;
    }

}


if($firstname == "") {
    $errors[] = "first name is required";
}
if($lastname == "") {
    $errors[] = "last name is required";
}
   
if(count($errors) > 0){
    $response->setCode(ResponseCode::HTTP_BAD_REQUEST);
    $response->setErrors($errors);
    $response->sendResponse();
    exit;
}


$user=new User();
$user->setUserId($id);
if($option == "changeinformation"){
    $user->setFirstname($firstname);
    $user->setLastname($lastname);
    $user->setEmail($email);

    $id = UserService::update($user);
}else{
    $user->setPassword($password);
    $result = UserService::changePassword($user);
    if(!$result){
        $id = null;
    }else{
        header('Location: ../login.php?logout=yes');
        exit;
    }
}


if(isset($id)){
    SessionService::setSessionObj("user", $user);
    header('Location: ./../control-panel/account.php');
    exit;
}else{

    header('Location: ./../control-panel/account.php');
    exit;
}

?>