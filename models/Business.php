<?php
/**
 * domain class 
 */
class Business{
    private $Id;
    private $name;
    private $description;
    private $logo;
    private $owner;
    private $dateCreated;
    private $isPublished;
    private $isVerified;
    private $address;
    private $contactInformation;
    private $contactQrCode;

    //accessors
    public function getId(){ return $this->Id; }
    public function getName(){ return $this->name; }
    public function getDescription(){ return $this->description; }
    public function getContactQrCode(){ return $this->contactQrCode; }
    public function getLogo(){ return $this->logo; }
    public function getOwner(){ return $this->owner; }
    public function getDateCreated() { return $this->dateCreated; }
    public function getPublished(){ return $this->publish; }
    public function getIsVerified(){ return $this->isVerified; }
    public function getAddress(){ return $this->address; }
    public function getContactInformation(){ return $this->contactInformation; }

    //mutators
    public function setId($id){ $this->Id = $id; }
    public function setName($name){ $this->name = $name; }
    public function setDescription($description){ $this->description = $description; }
    public function setContactQrCode($contactQrCode){ $this->contactQrCode = $contactQrCode; }
    public function setLogo($logo){  $this->logo = $logo; }
    public function setOwner($owner){  $this->owner = $owner; }
    public function setPublished($isPublished){  $this->isPublished = $isPublished; }
    public function setIsVerified($isVerified){  $this->isVerified = $isVerified; }
    public function setDateCreated($dateCreated) { $this->dateCreated = $dateCreated; }
    public function setAddress($address){ $this->address = $address; }
    public function setContactInformation($contactInformation){ $this->contactInformation = $contactInformation; }
}