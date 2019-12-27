<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete extends CI_Controller
{
    /**
     * Chat to delete from database
     * 
     * @var array
     */
    private $chat;

    /**
     * Friend account details
     * 
     * @var array
     */
    private $friend;

    /**
     * Delete chat or chats
     * 
     * @Maps - http://website/api/chats/delete
     */
    public function index() {
        $this->form_validation->set_rules('chat_id', 'chat', 'required|integer|callback_chat_exist|callback_user_chat');

        if($this->form_validation->run() == false) {
            return $this->api->api_response(false, $this->form_validation->error_array()['chat_id'] ?? '');
        }

        $delete = ['chat_id' => $this->input->post('chat_id')];

        if($this->chats->delete($delete) == false) {
            return $this->api->api_response(false, 'Something went wrong when tring to connect to database please try again later.');
        }

        // delete file if type is not text
        if(is_array($errors = $this->delete_chat())) {
            // save errors to delete file error
        }
        
        return $this->api->api_response(true, 'Chat delete successfully.');
    }

    /**
     * Delete all user chats in database
     * 
     * @Map - http://website/api/chats/clear/all
     */
    public function clear_all() {
        $this->auth->loggedin();
        
        $this->form_validation->set_rules('username', 'friend', 'required|callback_friend_exist|callback_friendship_exist');

        if($this->form_validation->run() == false) {
            return $this->api->api_response(false, $this->form_validation->error_array()['friend'] ?? '');
        }
        
        $this->chat = $this->chats->get_all_chats(
            $this->auth->account('user_id'), $this->friend['user_id']
        );
        
        $last_chat_id = count($this->chat) !== 0 ? $this->chat[count($this->chat)-1]['chat_id'] : 0;
        $chats_cleared = $this->chats->clear(
            $this->auth->account('user_id'),
            $this->friend['user_id'],
            $last_chat_id
        );
        
        if($chats_cleared == false) {
            return $this->api->api_response(false, 'Something went wrong when tring to connect to database.');
        }

        // delete file if type is not text
        if(is_array($errors = $this->delete_chat())) {
            // save errors to delete file error
        }
        
        return $this->api->api_response(true, "All chats when deleted successfully.");
    }

    /**
     * Check if chat exist in database
     * 
     * @param integer
     * @return boolean
     */
    public function chat_exist($chat = NULL) {
        $this->chat = $this->chats->get_chat($chat);
        if(count($this->chat) == 0) {
            $message = 'The {field} does not exist that mean your message has been deleted please refresh your browser.';
            $this->form_validation->set_message('chat_exist', $message);
            return false;
        }
        return true;
    }

    /**
     * Check if text belong to user
     * 
     * @return boolean
     */
    public function user_chat() {
        $user_id = $this->auth->account('user_id');
        if(($user_id != $this->chat['from_user']) AND ($user_id != $this->chat['to_user'])) {
            $message = 'The {field} does not belong to you so you can not delele this text.';
            $this->form_validation->set_message('chat_exist', $message);
            return false;
        }
        return true;
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
            $this->form_validation->set_message('friendship_exist', 'Your are not friends with ' + $this->friend['username'].'.');
            return false;
        }
        return true;
    }

    /**
     * Delete chats in database
     * 
     * @return 
     */
    private function delete_chat() {
        $error = [];
        for($i = 0; $i < count($this->chat); $i++) {
//             if($this->chat[$i]['type'] == 'picture') {
//                 if($this->delete_picture($this->chat[$i]['content']) == false) {
//                     $error[] = $this->chat[$i]['content'];
//                 }
//             } elseif($this->chat[$i]['type'] == 'video') {
//                 if($this->delete_video($this->chat[$i]['content']) == false) {
//                     $error[] = $this->chat[$i]['content'];
//                 }
//             }
        }
        return count($error) == 0 ? true :  $error;
    }

    /**
     * Delete video file
     * 
     * @param
     * @return
     */
    private function delete_video(string $path) {
        return true;
    }

    /**
     * Delete picture file
     * 
     * @param
     * @return 
     */
    private function delete_picture(string $path) {
        return true;
    }

}