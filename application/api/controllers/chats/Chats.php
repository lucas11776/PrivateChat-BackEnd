<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chats extends CI_Controller
{
 
    /**
     * User friend account details
     * 
     * @var array
     */
    private $friend;
    
    /**
     * Defualt chats results limit
     * 
     * @var string
     */
    private const LIMIT = 30;
    
    /**
     * Get user chats from database
     * 
     * @param string $username
     */
    public function index(string $username = NULL) {
        $this->auth->loggedin();

        if(is_string($error = $this->get_friend($username ?? ''))) {
            return $this->api->api_response(false, $error);
        }
        
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : self::LIMIT;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        
        // mark all user chats seen
        $this->chats->chats_seen($this->auth->account('user_id'), $this->friend['user_id']);

        return $this->api->response([
            'friend' => $this->friend['username'],
            'user' => $this->auth->account('username'),
            'total' => 10,
            'chats' => $this->chats->get($this->auth->account('user_id'), $this->friend['user_id'], $limit, $offset)
        ]);
    }

    /**
     * Get user chats from database
     * 
     * @param string $username
     */
    public function latest_chats(string $username = NULL) {
        $this->auth->loggedin();

        if(is_string($error = $this->get_friend($username ?? ''))) {
            return $this->api->api_response(false, $error);
        }
        
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : self::LIMIT;
        $chat_id = is_numeric($this->input->get('chat_id')) ? $this->input->get('chat_id') : 0;

        // mark all user chats seen
        $this->chats->chats_seen($this->auth->account('user_id'), $this->friend['user_id']);
        
        return $this->api->response([
            'friend' => $this->friend['username'],
            'user' => $this->auth->account('username'),
            'total' => 10,
            'chats' => $this->chats->latest_chats($this->auth->account('user_id'), $this->friend['user_id'], $chat_id, $limit)
        ]);
    }
    
    /**
     * Get users friend account details
     * 
     * @var string $username
     * @return boolean
     */
    private function get_friend(string $username) {
        $this->friend = $this->accounts->get(['username' => $username])[0] ?? [];
        
        if(count($this->friend) == 0) {
           return 'Friend username does not exist.'; 
        }
        
        if($this->friends->friendship_exist($this->auth->account('user_id'), $this->friend['user_id']) == false) {
            return 'User are not friends with ' . $username;
        }
        
        return true;
    }

    /**
     * Check if relationship exist between user and friend
     * 
     * @return boolean
     */
    private function friendship_exist() {
        return true;
    }
    
}

