<?php

class OfferingType{
    private $id;
    private $type;

    public function getId(){ return $this->id; }
    public function getType(){ return $this->type; }

    public function setId($id){ $this->id = $id; }
    public function setType($type){ $this->type = $type; }
}