<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller
{
    
    public function index() {
        $this->auth->loggedin();
        return $this->api->response(
            $this->notification->count_db($this->auth->account('user_id'))
        );
    }
    
}

