<?php
/**
 * domain class for user
 */
class User{
    private $userId;
    private $username;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $userType;
    private $gender;
    private $salt;
    private $dateCreated;
    private $dateUpdated;
    private $isAccountVerified;
    private $resetToken;

    //accessors
    public function getUserId(){ return $this->userId; }
    public function getUsername(){ return $this->username; }
    public function getEmail(){ return $this->email; }
    public function getGender(){ return $this->gender; }
    public function getPassword(){ return $this->password; }
    public function getFirstname(){ return $this->firstname; }
    public function getLastname(){ return $this->lastname; }
    public function getUserType(){ return $this->userType; }
    public function getSalt(){ return $this->salt; }
    public function getDateCreated() { return $this->dateCreated; }
    public function getDateUpdated(){ return $this->dateUpdated; }
    public function getResetToken(){ return $this->resetToken; }
    public function getIsAccountVerified(){ return $this->isAccountVerified; }

    //mutators
    public function setUserId($userId){ $this->userId = $userId; }
    public function setUsername($username){ $this->username = $username; }
    public function setEmail($email){ $this->email = $email; }
    public function setGender($gender){ $this->gender = $gender; }
    public function setPassword($password){  $this->password = $password; }
    public function setFirstname($firstname){  $this->firstname = $firstname; }
    public function setLastname($lastname){  $this->lastname = $lastname; }
    public function setUserType($userType){  $this->userType = $userType; }
    public function setSalt($salt){ $this->salt = $salt; }
    public function setDateCreated($dateCreated) { $this->dateCreated = $dateCreated; }
    public function setDateUpdated($dateUpdated){  $this->dateUpdated= $dateUpdated; }
    public function setIsAccountVerified($isAccountVerified){  $this->isAccountVerified = $isAccountVerified; }
    public function setResetToken($token){ return $this->resetToken = $token; }
}