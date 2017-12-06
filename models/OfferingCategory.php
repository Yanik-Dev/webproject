<?php

class OfferingCategory{
    private $id;
    private $category;
    private $type;

    public function getId(){ return $this->id; }
    public function getCategory(){ return $this->category; }
    public function getType(){ return $this->type; }

    public function setId($id){ $this->id = $id; }
    public function setCategory($category){ $this->category = $category; }
    public function setType($type){ $this->type = $type; }
}