<?php
class Response{
    
    private $_headers = [];
    private $_errors = [];
    private $_code;
    private $_content;
    private $_message;

    private  $_statusMessages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          
        208 => 'Already Reported',      
        226 => 'IM Used',               
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];

    public function setCode($code){
        $this->_code = $code;
    }

    public function setContent($content){
        $this->_content = $content;
    }

    public function setHeaders($headers){
        $this->_headers = $headers;
    }

    public function setErrors($errors){
        $this->_errors = $this->_errors;
    }

    public function setMessage($message){
        $this->_message = $message;
    }

    public function sendResponse(){
        $response = $this->_setUpResponse();
        echo json_encode($response);
    }

    private function _setUpResponse(){
        //set headers
        foreach($this->_headers as $header => $key){
            header("${key}: ${header}");
        }

        $statusMessage = $this->_statusMessages[$this->_code];

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            return [
               "status" => $this->_code,
               "message" => $statusMessage,
               "errors" => $this->_errors,
            ];
        } elseif ($method == 'GET') {
            return [
                "results" => $this->_content
             ];
        } elseif ($method == 'PUT') {
            return [
                "message" => $statusMessage,
                "errors" => $this->_errors,
            ];
        } elseif ($method == 'DELETE') {
            return [
                "results" => $this->_content
             ];
        } else {
            return [
                "message"=> "UNKNOWN METHOD"
            ];
        }
    }
}