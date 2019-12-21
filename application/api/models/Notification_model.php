<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model
{
    
    private const FRIENDS = 'friends';
    
    private const CHATS = 'chats';
    
    private const REQUEST = 'friends_requests';
    
    /**
     * Count number of unseen chats, friend requests, users friends
     * 
     * @return array
     */
    public function count_db(int $user_id) {
        $sql = '
        SELECT
            (SELECT COUNT('.self::CHATS.'.chat_id) FROM '.self::CHATS.' WHERE '.self::CHATS.'.to_user='.$user_id.' AND '.self::CHATS.'.seen=0) AS chats,
            (SELECT COUNT('.self::FRIENDS.'.friends_id) FROM friends WHERE '.self::FRIENDS.'.to_user='.$user_id.' OR '.self::FRIENDS.'.from_user='.$user_id.') AS friends,
            (SELECT COUNT('.self::REQUEST.'.friend_request_id) FROM '.self::REQUEST.' WHERE '.self::REQUEST.'.to_user='.$user_id.') AS requests
        FROM DUAL';
        return $this->db->query($sql)
                        ->result_array()[0];
    }
    
}

