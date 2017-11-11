<?php
class User{
    private $salt;
	private $email;
	private $password;
	private $userType;
	private $userID;
	private $dateCreated;
	private $dateUpdated;
	private $accountVerified;
	private $firstName;
	private $lastName;
	
	  
	  
	  
	  public function getSalt()
	  {
		  return $this->salt;
	  }
	   public function setSalt($salt)
	  {
		  $this->salt=$salt;
	  }
	  
	  
	  
	 public function getEmail()
	  {
		  return $this->email;
	  }
	   public function setEmail($email)
	  {
		   $this->email=$email;
	  }
	  
	   public function getPassword()
	  {
		  return $this->password;
	  }
	     public function setPassword($password)
	  {
		  $this->password=$password;
	  }
	  
	  
	   public function getUserType()
	  {
		  return $this->userType;
	  }
	   public function setUserType($userType)
	  {
		   $this->userType=$userType;
	  }
	  
	  
	   public function getUserID()
	  {
		  return $this->userID;
	  }
	     public function setUserID($userID)
	  {
		  $this->userID=$userID;
	  }
	  
	  
	   public function getDateCreated()
	  {
		  return $this->dateCreated;
	  }
	   public function setDateCreated($dateCreated)
	  {
		   $this->dateCreated=$dateCreated;
	  }
	  
	  
	   public function getaDateUpdated()
	  {
		  return $this->dateUpdated;
	  }
	  public function setaDateUpdated($dateUpdated)
	  {
		  $this->dateUpdated=$dateUpdated;
	  }
	
}