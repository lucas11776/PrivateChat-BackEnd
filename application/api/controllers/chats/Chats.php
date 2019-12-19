<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chats extends CI_Controller
{
 
    private $friend;
    
    private const LIMIT = 30;
    
    /**
     * Get user chats from database
     * 
     * 
     */
    public function index(string $username = NULL) {
        
        if(is_string($error = $this->get_friend($username ?? ''))) {
            return $this->api->api_response(false, $error);
        }
        
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : self::LIMIT;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        
        return $this->api->response(
            $this->chats->get($this->auth->account('user_id') ?? 1, $this->friend['user_id'], $limit, $offset)
        );
    }
    
    
    private function get_friend(string $username) {
        $this->friend = $this->accounts->get(['username' => $username])[0] ?? [];
        
        if(count($this->friend) == 0) {
           return 'Friend username does not exist.'; 
        }
        
        if($this->friends->friendship_exist($this->auth->account('user_id') ?? 1, $this->friend['user_id']) == false) {
            return 'User are not friends with ' . $username;
        }
        
        return true;
    }
    
}

