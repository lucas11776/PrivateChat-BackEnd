<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization extends CI_Controller
{
    
    /**
     * Loggedin response for this controller.
     *
     * @Maps - http://website/api/authentication/loggedin
     */
    public function loggedin() {
        $this->auth->updated_user_last_seen();
        return $this->api->response([
            'status' => $this->auth->loggedin(false)
        ]);
    }
    
    /**
     * Loggedout response for this controller.
     *
     * @Maps - http://website/api/authentication/loggedout
     */
    public function loggedout() {
        $this->auth->updated_user_last_seen();
        return $this->api->response([
            'status' => $this->auth->loggedout(false)
        ]);
    }
    
}

