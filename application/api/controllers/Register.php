<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{

	/**
	 * Register a user in database
	 *
	 * @Maps - http://website/api/register
	 */
	public function index() {
		$this->auth->loggedout();
		
		$this->form_validation->set_rules('username', 'username', 'required|min_length[3]|max_length[20]|callback_username_exist');
		$this->form_validation->set_rules('email', 'email', 'required|callback_email_exist');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');
		
		if($this->form_validation->run() === false) {
		    $message = 'Something went wrong when tring to create account.';
		    return $this->api->api_response(false, $message, $this->form_validation->error_array());
		}
		
		$account = [
		    'last_seen' => time(),
		    'profile_picture' => base_url($this->accounts::PROFILE_PICTURE),
		    'username' => strtolower($this->input->post('username')),
		    'email' => $this->input->post('email'),
		    'password' => $this->encryption->encrypt($this->input->post('password')),
		];
		
		if($this->accounts->insert($account) === false) {
		    return $this->api->api_response(false, 'Something went wrong when tring to connect to database', []);
		}
		
		return $this->api->api_response(true, 'Account `'.$account['username'].'` has been successfully created please login to your new account');
	}

	/**
	 * Check if username deos not exist in database
	 * 
	 * @param string $username
	 * @return boolean
	 */
	public function username_exist(string $username = NULL) {
	    if($this->accounts->username_exist($username ?? '')) {
			$this->form_validation->set_message('username_exist', 'The {field} already exist please try new username.');
	        return false;
	    }
	    return true;
	}
	
	/**
	 * Check if email address deos not exist in database
	 * 
	 * @param string $email
	 * @return boolean
	 */
	public function email_exist(string $email = NULL) {
	    if($this->accounts->email_exist($email ?? '')) {
			$this->form_validation->set_message('email_exist', 'The {field} already exist in database, if your forgot your password reset your password.');
	        return false;
	    }
	    return true;
	}
	
}
