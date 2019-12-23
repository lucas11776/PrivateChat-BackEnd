<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    /**
     * Get user account details
     * 
     * @Maps - http://website/api/account/details
     */
    public function index() {
        $this->auth->loggedin();
        return $this->api->response([
            'profile_picture' => $this->auth->account('profile_picture'),
            'username' => $this->auth->account('username'),
            'email' => $this->auth->account('email'),
        ]);
    }

}