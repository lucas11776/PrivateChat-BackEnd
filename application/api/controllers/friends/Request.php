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
    
    public function friend_exist(string $username = NULL) {
        $this->friend = $this->accounts->get(['username' => $username])[0] ?? [];
        if(count($this->friend) === 0) {
            $this->form_validation->set_message('friend_exist', 'Friend username does not exist');
            return false;
        }
        return true;
    }
    
    public function already_friends() {
        return true;
    }
    
    public function not_friends() {
        return true;
    }
    
}

