<?php
class ContactInformation{
    private $email;
    private $website;
    private $telephone;
    private $mobile;

    public function getEmail(){ return $this->email; }
    public function getWebsite() { return $this->website; }
    public function getTelephone() { return $this->telephone; }
    public function getMobile() { return $this->mobile; }

    public function setEmail($email) { $this->email = $email; }
    public function setWebsite($website){$this->website = $website; }
    public function setTelephone($telephone){ $this->telephone = $telephone; }
    public function setMobile($mobile){ $this->mobile = $mobile; }

}