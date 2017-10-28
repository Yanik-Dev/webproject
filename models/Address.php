<?php

class Address{
    private $street;
    private $city;
    private $province;
    private $country;

    public function getStreet(){ return $this->street; }
    public function getCity(){ return $this->city; }
    public function getProvince(){ return $this->province; }
    public function getCountry(){ return $this->country; }

    public function setStreet($street) { $this->street = $street; }
    public function setCity($city) { $this->city = $city; }
    public function setProvince($province){ $this->province = $province; }
    public function setCountry($country){ $this->country = $country; }
}