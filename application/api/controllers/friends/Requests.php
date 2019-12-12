<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends CI_Controller
{
    
    private $friend;
    
    /**
     * Get user friends request
     * 
     * @Map - http://website/api/friends/requests
     */
    public function index(string $string = NULL) {
        $this->auth->loggedin();
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : 0;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response(
            $this->friends_requests->get($this->auth->account('user_id'), $limit, $offset)
        );
    }
    
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

