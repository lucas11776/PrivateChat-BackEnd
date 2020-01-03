<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unfriend extends CI_Controller
{
    
    /**
     * Friend account details
     * 
     * @var array
     */
    private $friend;
    
    /**
     * Unfriend a user in database
     * 
     * @Maps- http://website/api/friends/unfriend
     */
    public function index() {
        $this->auth->loggedin();
        
        $this->form_validation->set_rules('username', 'friend', 'required|callback_friend_exist|callback_friendship_exist');
        
        // error response
        if($this->form_validation->run() == false) {
            return $this->api->api_response(false, $this->form_validation->error_array()['username'] ?? '');
        }
        
        $unfriend_relationsip = $this->friends->delete(
            $this->auth->account('user_id'),
            $this->friend['user_id']
        );
        
        // error response
        if($unfriend_relationsip == false) {
            return $this->api->api_response(false, 'Something went wrong when tring to connect to database.');
        }
        
        $this->chat = $this->chats->get_all_chats(
            $this->auth->account('user_id'), $this->friend['user_id']
        );
        
        $chats_cleared = $this->chats->clear(
            $this->auth->account('user_id'),
            $this->friend['user_id']
        );
        
        if($chats_cleared == false) {
            $this->chats->clear($this->auth->account('user_id'), $this->friend['user_id']);
        } else {
            # delete all chats that are not text
        }
        
        // success response
        return $this->api->api_response(true, 'You have successfully unfriended ' . $this->friend['username'] . '.');
    }
    
    /**
     * Check if friend exist in database
     *
     * @param string
     * @return boolean
     */
    public function friend_exist(string $username = NULL) {
        $this->friend = $this->accounts->get(['username' => $username])[0] ?? [];
        if(count($this->friend) == 0) {
            $this->form_validation->set_message('friend_exist', 'Friend does does not exist.');
            return false;
        }
        return true;
    }
    
    /**
     * Check if user and friend have a relationship
     *
     * @return boolean
     */
    public function friendship_exist() {
        $exist = $this->friends->friendship_exist($this->auth->account('user_id'), $this->friend['user_id']);
        if($exist == false) {
            $this->form_validation->set_message('friendship_exist', 'Your are not friends with ' . $this->friend['username'] . '.');
            return false;
        }
        return true;
    }
}

