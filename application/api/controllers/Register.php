<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{

	/**
	 * Index response for this controller.
	 *
	 * @Maps - http://website/api/register
	 */
	public function index() {
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('confirm_password', 'confirm_password', 'required');
		
		if($this->form_validation->run() === false) {
		    echo json_encode([
		        'status' => false,
		        'message' => 'Something went wrong when tring register your account.',
		        'data' => $this->form_validation->error_array()
		    ]);
		    return;
		}
	}

	/**
	 * Check if username deos not exist in database
	 * 
	 * @param string $username
	 * @return boolean
	 */
	public function username_exist($username) {
	    return true;
	}
	
	/**
	 * Check if email address deos not exist in database
	 * 
	 * @param string $email
	 * @return boolean
	 */
	public function email_exist($email) {
	    return true;
	}
	
}
