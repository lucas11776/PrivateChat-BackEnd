<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller
{
    
    private $friend;
    
    /**
     * Send friend requet response for this controller.
     *
     * @Maps - http://website/api/friends/request/decline
     */
    public function send() {
        $this->auth->loggedin();
    }
    
    /**
     * Accept friend requet response for this controller.
     *
     * @Maps - http://website/api/friends/request/decline
     */
    public function accept() {
        $this->auth->loggedin();
    }
    
    /**
     * Decline friend requet response for this controller.
     *
     * @Maps - http://website/api/friends/request/decline
     */
    public function decline() {
        $this->auth->loggedin();
    }
    
    
    
}

