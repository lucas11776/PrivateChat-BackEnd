<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends_requests_model extends CI_Model
{
    
    public const TABLE = 'friends_requests';
    
    /**
     * Select friend request direct and indirect
     * 
     * @param int $user
     * @param int $friend
     * @return CI_DB
     */
    private function select_user(int $user, int $friend) {
        return $this->db->where([self::TABLE.'.from_user' => $user, self::TABLE.'.to_user' => $friend])
                        ->or_where([self::TABLE.'.from_user' => $friend, self::TABLE.'.to_user' => $user]);
    }
    
    /**
     * Insert a new user friend request
     * 
     * @param int $from
     * @param int $to
     * @return boolean
     */
    public function insert(int $from, int $to) {
        $data = ['from_user' => $from, 'to_user' => $to];
        return $this->db->insert(self::TABLE, $data);
    }
    
    /**
     * Check if request request already exist between to users
     * 
     * @param int $user
     * @param int $friend
     * @return boolean
     */
    public function friend_request_exist(int $user, int $friend) {
        $request = $this->select_user($user, $friend)
            ->get()
            ->result_array();
        return count($request) !== 0 ? true : false;
    }
    
}
