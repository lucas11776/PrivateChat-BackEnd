<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_picture extends CI_Controller
{

    /**
     * Upload new user profile picture
     * 
     */
    public function index() {
        $this->form_validation->set_rules('picture', 'profile picture', 'callback_upload');


        print_r($_FILES);
    }

    /**
     * Upload user profile picture
     * 
     * @return boolean
     */
    public function upload() {
        return true;
    }

}