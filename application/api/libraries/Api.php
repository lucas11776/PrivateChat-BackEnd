<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api

{
    
    /**
     * CodeIgniter super-object
     * 
     * @var CI_Controller
     */
    protected $CI;
    
    /**
     * Initialize class
     */
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Rest Api response
     * 
     * @param bool $status
     * @param string $message
     * @param array $data
     * @param int $code
     * @return string
     */
    public function api_response(bool $status = TRUE, string $message = '', array $data = [], int $code = 202) {
        return $this->response([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    
    /**
     * Api response
     * 
     * @param bool $data
     * @param int $code
     * @return string
     */
    public function response(array $data = [], int $code = 202) {
        http_response_code($code);
        echo $response = json_encode($data);
        return $response;
    }
    
}