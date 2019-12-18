<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    
    public function last_seen_update() {
        if($this->auth->account('user_id') != null) {
            $this->auth->updated_user_last_seen();
        }
        $this->api->response([]);
    }
    
}

