<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends_requests_model extends CI_Model
{
    
    /**
     * Friends request table name in database
     * 
     * @var string
     */
    private const TABLE = 'friends_requests';
    
    /**
     * Accounts table name in database
     * 
     * @var string
     */
    private const ACCOUNTS = 'accounts';
    
    /**
     * Select friend request direct and indirect
     * 
     * @param int $user
     * @param int $friend
     * @return CI_DB
     */
    private function select_user(int $user, int $friend) {
        $this->db->where([self::TABLE.'.from_user' => $user, self::TABLE.'.to_user' => $friend])
             ->or_where([self::TABLE.'.from_user' => $friend, self::TABLE.'.to_user' => $user]);
        return $this;
    }
    
    /**
     * Join friends requests with sent user account
     * 
     * @return Friends_requests_model
     */
    private function join_accounts_table() {
        $cord = self::TABLE.'.from_user='.self::ACCOUNTS.'.user_id';
        // $cord .= self::TABLE.'.to_user='.self::ACCOUNTS.'.user_id';
        $this->db->join(self::ACCOUNTS, $cord, 'LEFT');
        return $this;
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
     * Get user friends requests
     * 
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get(int $user_id, int $limit = NULL, int $offset = NULL) {
        return $this->join_accounts_table()
                    ->db
                    ->select(
                        self::ACCOUNTS.'.profile_picture,'.self::ACCOUNTS.'.username,'.self::ACCOUNTS.'.last_seen,'.
                        self::TABLE.'.created'
                    )
                    ->where(self::TABLE.'.to_user', $user_id)
                    ->order_by(self::TABLE.'.created', 'DESC')
                    ->get(self::TABLE, $limit, $offset)
                    ->result_array();
    }
    
    
    /**
     * Count all get results
     * 
     * @param int $user_id
     * @return integer
     */
    public function get_count(int $user_id) {
        return $this->join_accounts_table()
                    ->db
                    ->where(self::TABLE.'.to_user', $user_id)
                    ->count_all_results();
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
                        ->db->where(['from_user' => $friend, 'to_user' => $friend])
                        ->get(self::TABLE)
                        ->result_array();
        return count($request) !== 0 ? true : false;
    }
    
    /**
     * Delete friend request from databases
     * 
     * @param int $user
     * @param int $friend
     * @return boolean
     */
    public function delete($user, $friend) {
        return $this->select_user($user, $friend)
                    ->db
                    ->delete(self::TABLE);
    }
    
}
