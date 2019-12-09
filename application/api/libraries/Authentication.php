<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Encryption
{
    
    /**
     * CodeIgniter super-object
     *
     * @var	CI_Controller
     */
    protected $CI;
    
    /**
     * User account
     * 
     * @var array
     */
    private $account;
    
    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
        $this->CI->load->model('accounts_model');
        $this->CI->load->library('api');
        $this->account = $this->get_user_account();
    }
    
    /**
     * Check if user request is valid or user token is valid
     * 
     * @param bool $guard
     * @return boolean
     */
    public function loggedin(bool $guard = TRUE) {
        if($guard && count($this->account) === 0) {
            $this->unauthorized_access();
        }
        return count($this->account) !== 0 ? true : false;
    }
    
    /**
     * Check if user request token is not valid
     * 
     * @param bool $guard
     * @return boolean
     */
    public function loggedout(bool $guard = TRUE) {
        if($guard && count($this->account) !== 0) {
            $this->unauthorized_access();
        }
        return count($this->account) === 0 ? true : false;
    }
    
    /**
     * Unauthorize access message
     * 
     * Error response message with code 401 (Unauthorized Access)
     */
    public function unauthorized_access() {
        $this->CI->api->response([
            'message' => 'Unauthorized Access...'
        ], 401);
        exit();
    }
    
    /**
     * Get user account from request token
     * 
     * Get token from headers and decrypt token
     * and check if token is valid or has not expired.
     * 
     * @return array
     */
    protected function get_user_account() {
        $user_token = $this->decrypt_user_token();
        
        if(count($user_token) === 0) {
            return [];
        }
        
        return $this->CI->accounts_model->get([
            'user_id' => $user_token['user_id']
        ])[0] ?? [];
    }
    
    /**
     * Decrypt user token from request headers
     * 
     * Get user token from headers and decrypt token and
     * check if request ip address matches user ip address
     * and token ip address has not expired.
     * 
     * @return array
     */
    private function decrypt_user_token() {
        $token_header = $this->CI->input->request_headers()['token'] ?? [];
        
        if (is_array($token_header)) {
            return $token_header;
        }
        
        $token_header = json_decode($this->decrypt($token_header), true);
        
        if(!is_array($token_header)) {
            return [];
        }
        
        if($token_header['ip_address'] != $this->CI->input->ip_address()) {
            return [];
        }
        
        if($token_header['expire'] < time()) {
            return [];
        }
        
        return $token_header;
    }
    
}

