<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Text extends CI_Controller
{

    /**
     * Friend account details
     * 
     * @var array
     */
    private $friend;

    /**
     * Send text to friend
     * 
     * @Maps - http://website/chats/send/text
     */
    public function index() {
        $this->auth->loggedin();
        
        $this->form_validation->set_rules('username', 'friend username', 'required|callback_friend_exist|callback_friendship_exist');
        $this->form_validation->set_rules('text', 'text', 'required');

        if($this->form_validation->run() == false) {
            $message = 'Invalid send text request the was a error please try again.';
            return $this->api->api_response(false, $message, $this->form_validation->error_array());
        }

        $chat_text = [
            'from_user' => $this->auth->account('user_id'),
            'to_user' => $this->friend['user_id'],
            'type' => 'text',
            'content' => $this->input->post('text')
        ];

        if($this->chats->insert($chat_text) == false) {
            $message = 'Something went wrong when tring to connect to database';
            return $this->api->api_response(false, $message);
        }

        return $this->api->api_response(true, 'Text successfully sent.');
    }

    /**
     * Check if friend username exist
     * 
     * @param string $username
     * @return boolean
     */
    public function friend_exist(string $username = NULL) {
        $this->friend = $this->accounts->get(['username' => $username ?? ''])[0] ?? [];
        if(count($this->friend) == 0) {
            $this->form_validation->set_message('friend_exist', 'The {field} does not exist.');
            return false;
        }
        return true;
    }

    /**
     * Check if user and friend have relationship
     * 
     * @return boolean
     */
    public function friendship_exist() {
        $exist = $this->friends->friendship_exist($this->auth->account('user_id'), $this->friend['user_id']);
        if($exist == false) {
            $this->form_validation->set_message('friendship_exist', 'Your are not friends with ' + $this->friend['username'].'.');
            return false;
        }
        return true;
    }

    

}