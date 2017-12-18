<?php

class Offering{
    private $id;
    private $name;
    private $description;
    private $cost;
    private $tags;
    private $business;
    private $category;
    private $images;
    private $dateCreated;


    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getDescription(){ return $this->description; }
    public function getCost(){ return $this->cost; }
    public function getTags() { return $this->tags; }
    public function getBusiness(){ return $this->business;}
    public function getCategory(){ return $this->category; }
    public function getImages(){ return $this->images; }
    public function getDateCreated(){ return $this->dateCreated; }

    public function setId($id){ $this->id = $id; }
    public function setName($name){ $this->name = $name; }
    public function setDescription($description){ $this->description = $description; }
    public function setCost($cost){ $this->cost = $cost; }
    public function setTags($tags){ $this->tags = $tags; }
    public function setCategory($category){ $this->category = $category; }
    public function setBusiness($business){ $this->business = $business; }
    public function setImages($images){ $this->images = $images; }
    public function setDateCreated($date){ $this->dateCreated = $date; }

}