<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller
{
    
    private const LIMIT = 30;
    
    /**
     * Get user friends from database
     * 
     * @Maps - http://website/api/friends
     */
    public function index(string $search = NULL) {
        $this->auth->loggedin();
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : 0;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response(
            $this->friends->friends(
                $search ?? '',
                $this->auth->account('user_id'),
                $limit,
                $offset
            )
        );
    }

    /**
     * Get user friends with latest chats 
     * 
     * @Maps - http://website/friends/chat/preview
     */
    public function chat_preview(string $search = NULL) {
        $this->auth->loggedin();
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : 0;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response(
            $this->friends->friends_chat_preview(
                $search ?? '',
                $this->auth->account('user_id'),
                $limit,
                $offset
            )
        );
    }
    
    /**
     * Check if user is friend with username
     * 
     * @Map - http://website/api/friends/user/(:username)
     */
    public function friends_user(string $friend = NULL) {
        $this->auth->loggedin();
        $friend = $this->accounts->get(['username' => $friend])[0] ?? [];
       
        if(count($friend) === 0) {
            return $this->api->api_response(false, 'Friends account does not exist.');
        }
        
        $friends_user = $this->friends->friendship_exist(
            $this->auth->account('user_id'), $friend['user_id']
        );
        
        return $this->api->api_response(
            $friends_user,
            $friends_user ? 'You are friend with '.strtoupper($friend['username']):
                            'You are not friends with '.strtoupper($friend['username']));
    }
    
    /**
     * Get friend online status
     * 
     * @Maps - http://website/api/friends/last/seen/(:username)
     */
    public function online_status(string $username = NULL) {
        //$this->auth->loggedin();
        $friend = $this->accounts->get(['username' => $username])[0] ?? [];
        if(count($friend) == 0) {
            return $this->api->api_response(false, 'Friend username does not exist.');
        }
        return $this->api->response(['last_seen' => $friend['last_seen']]);
    }
    
    /**
     * Get friend account details
     * 
     * @Maps - http://website/api/friends/details/(:username)
     */
    public function friends_details(string $username = NULL) {
        $friend = $this->accounts->get(['username' => $username ?? ''])[0] ?? [];
        if (count($friend) == 0) {
            return $this->api->api_response(false, 'Friend username does not exist.');
        }
        return $this->api->response([
            'profile_picture' => $friend['profile_picture'],
            'username' => $friend['username'],
            'last_seen' => $friend['last_seen']
        ]);
    }
    
}

