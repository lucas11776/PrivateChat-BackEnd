<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	/**
	 * Index response for this controller.
	 *
	 * @Maps - http://website/api/register
	 */
	public function index() {
		
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
