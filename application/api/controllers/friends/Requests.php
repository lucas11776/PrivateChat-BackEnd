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
        $this->form_validation->set_rules('username', 'friend username', 'required|callback_friend_exist|callback_friend_request_exist|callback_not_friends');
        
        if($this->form_validation->run() === false) {
            return $this->api->api_response(false, $this->form_validation->error_array()['username']);
        }
        
        if($this->friends->insert($this->friend['user_id'], $this->auth->account('user_id')) === false) {
            return $this->api->response(false, 'Something went wrong when tring to connect to database.');
        }
        
        if($this->friends_requests->delete($this->auth->account('user_id'), $this->friend['user_id']) === false) {
            $this->friends_requests->delete($this->auth->account('user_id'), $this->friend['user_id']);
        }
        
        return $this->api->api_response(true, 'You are now friends with '.$this->friend['username']);
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
        $this->get_friend_username($username);
        if(count($this->friend) === 0) {
            $this->form_validation->set_message('friend_exist', 'Friend username does not exist');
            return false;
        }
        return true;
    }
    
    /**
     * Check if user is not friends with username
     * 
     * @param string @username
     * @return boolean
     */
    public function already_friends(string $username = NULL) {
        $this->get_friend_username($username ?? '');
        return true;
    }
    
    /**
     * Check if user and username are not friends
     * 
     * @param string $username
     * @return boolean
     */
    public function not_friends(string $username = NULL) {
        $this->get_friend_username($username);
        $friends = $this->friends->friendship_exist($this->auth->account('user_id'), $this->friend['user_id']);
        if($friends === true) {
            $this->form_validation->set_message('not_friends', 'You are already friends with '.$this->friend['username'].'.');
            return false;
        }
        return true;
    }
    
    /**
     * Check if friend request exist between user and username
     * 
     * @param string $username
     * @return boolean
     */
    public function friend_request_exist(string $username = NULL) {
        $this->get_friend_username($username ?? '');
        $exist = $this->friends_requests->friend_request_exist($this->auth->account('user_id'), $this->friend['user_id']);
        if ($exist === false) {
            $this->form_validation->set_message('friend_request_exist', 'You did not recieve a friend request from that user.');
            return false;
        }
        return true;
    }
    
    /**
     * Get username account details
     * 
     * @param string $username
     */
    private function get_friend_username(string $username) {
        if($this->friend == null) {
            $this->friend = $this->accounts->get(['username' => $username])[0] ?? NULL;
        }
    }
    
}

