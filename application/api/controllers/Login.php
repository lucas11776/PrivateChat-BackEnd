<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    private $account;
    
    /**
     * Check if user creditails are valid and return user token
     *
     * @Maps - http://website/api/register
     */
    public function index() {
        $this->auth->loggedout();
        $this->form_validation->set_rules('username', 'username', 'required|callback_username_exist');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        if($this->form_validation->run() === false) {
            // password attemp count++
            $message = 'Invalid login credentails please enter correct credentails.';
            return $this->api->api_response(false, $message);
        }
        
        $account_password = $this->encryption->decrypt($this->account['password']);
        
        if($account_password != $this->input->post('password')) {
            $message = 'Invalid login credentails please enter correct credentails.';
            return $this->api->api_response(false, $message);
        }
        
        return $this->api->api_response(true, 'Correct login credentails.', [
            'token' => $this->encryption->encrypt(json_encode([
                'user_id' => $this->account['user_id'],
                'ip_address' => $this->input->ip_address(),
                'expire' => time() + ((60*60)*24)
            ]))
        ]);
    }
    
    /**
     * Check if username exist in database
     * 
     * @param string $username
     * @return boolean
     */
    public function username_exist(string $username = NULL) {
        if(count($this->account = $this->accounts->get_account_username($username ?? '')) === 0) {
            $this->form_validation->set_message('username_exist', 'The {field} does not exist in database.');
            return false;
        }
        return true;
    }
    
}

