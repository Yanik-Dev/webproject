<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/../lib/PHPMailer/SMTP.php';
require __DIR__.'/../lib/PHPMailer/PHPMailer.php';
require __DIR__.'/../lib/PHPMailer/Exception.php';

class MailService{
    private $recipients =[];
    private $subject;
    private $body;
    private $mailClient;
    private $ccs=[];
    private $bccs=[];
    private $attachment=null;

    /**
     * accepts a config array with the following attributes
     * [
     *    "username"=>"email@example.com",
     *    "password"=>"secret",
     *    "sender"=>"sender name",
     *    "senderemail"=>"sender@example.com",
     *    "replyname"=>"no-reply",
     *    "replyemail"=>"reply@gamil.com"
     * ]
     */
    public function __construct($config){
        $this->mailClient = new PHPMailer(true);
        $this->mailClient->SMTPDebug = 2;                                 
        $this->mailClient->isSMTP();                                      
        $this->mailClient->Host = 'smtp.gmail.com';  
        $this->mailClient->SMTPAuth = true;                               
        $this->mailClient->Username = $config["username"];                 
        $this->mailClient->Password = $config["password"];                            
        $this->mailClient->SMTPSecure = 'tls';                            
        $this->mailClient->Port = 587;        
        $this->mailClient->setFrom($config["senderemail"],
                                   $config["sender"]);  
        if(isset($config["replyemail"])){
            $this->mailClient->addReplyTo($config["replyemail"], $config["replyname"]);
        }
       
    }

    public function getRecipents(){ return $this->recipient; }
    public function getCCs(){ return $this->ccs; }
    public function getBCCs(){ return $this->bccs; }
    public function getSubject(){ return $this->subject; }
    public function getBody(){ return $this->body; }
    public function getAttachment(){ return $this->attachment; }

    public function setRecipents($recipients){ $this->recipients = $recipients; }
    public function setCCs($ccs){ $this->ccs = $ccs; }
    public function setBCCs($bccs){ $this->bccs = $bccs; }
    public function setSubject($subject){ $this->subject = $subject; }
    public function setBody($body){ $this->body = $body; }
    public function setAttachment($attachment){ $this->attachment = $attachment; }


    /**
     * send an email to the recipents
     * @return bool
     */
    public function sendMail(){
        $sent = false;
        try{
            foreach($this->recipients as $recipient){
                $this->mailClient->addAddress($recipient["email"], $recipient["name"]); 
            }

            foreach($this->ccs as $cc){
                $this->mailClient->addCC($cc);
            }

            foreach($this->bccs as $bcc){
                $this->mailClient->addBCC($bbc);
            }
            

            //Attachments
            if(isset($this->attachment))
                $this->mailClient->addAttachment($this->attachment);     

            //Content
            $this->mailClient->isHTML(true);                                  
            $this->mailClient->Subject = $this->subject;
            $this->mailClient->Body    = $this->body;

            $this->mailClient->send();
            $sent = true;
        } catch (Exception $e) {
            throw new Exception($this->mailClient->ErrorInfo);
        }
        return $sent;    
    }

    private function validate(){}


}