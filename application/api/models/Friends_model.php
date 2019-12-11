<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends_model extends CI_Model
{
    
    /**
     * Friends table name in database
     * 
     * @var string
     */
    private const TABLE = 'friends';
    
    /**
     * Accounts table name in database
     * 
     * @var string
     */
    private const ACCOUNTS = 'accounts';
    
    
    /**
     * friends request table name in database
     * 
     * @var string
     */
    private const REQUESTS = 'friends_requests';
    
    /**
     * Friends table name in database
     * 
     * @var string
     */
    private const FRIENDS = 'friends';
    
    
    /**
     * Select public users account data
     * 
     * @return CI_DB
     */
    private function select() {
        $this->db->select("accounts.profile_picture, accounts.username, accounts.last_seen");
        return $this;
    }
    
    /**
     * Join request table by user id and friend id
     * 
     * @param int $user_id
     * @return Friends_model
     */
    private function join_request_table(int $user_id) {
        $cord = '('.self::REQUESTS.'.from_user='.$user_id.' AND '.self::REQUESTS.'.to_user='.self::ACCOUNTS.'.user_id'.') OR (';
        $cord .= self::REQUESTS.'.to_user='.$user_id.' AND '.self::REQUESTS.'.from_user='.self::ACCOUNTS.'.user_id)';
        $this->db->join(self::REQUESTS, $cord, 'LEFT');
        return $this;
    }
    
    /**
     * Join friends table by user id  and friend id
     * 
     * @param int $user_id
     * @return Friends_model
     */
    private function join_friends_table($user_id) {
        $cord = '('.self::FRIENDS.'.from_user='.$user_id.' AND '.self::FRIENDS.'.to_user='.self::ACCOUNTS.'.user_id'.') OR (';
        $cord .= self::FRIENDS.'.to_user='.$user_id.' AND '.self::FRIENDS.'.from_user='.self::ACCOUNTS.'.user_id)';
        $this->db->join(self::FRIENDS, $cord, 'LEFT');
        return $this;
    }
    
    /**
     * Search for a friend account in database
     * 
     * @param string $username
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function search(string $username, int $user_id, int $limit = NULL, int $offset = NULL) {
        return $this->select()
                    ->join_request_table($user_id)
                    ->join_friends_table($user_id)
                    ->db
                    ->where(self::ACCOUNTS.'.user_id!=', $user_id)
                    ->where(self::REQUESTS.'.friend_request_id', NULL)
                    ->where(self::FRIENDS.'.friends_id', NULL)
                    ->like(self::ACCOUNTS.'.username', $username)
                    ->get(self::ACCOUNTS, $limit, $offset)
                    ->result_array();
    }
    
    /**
     * Count number of search results in database
     * 
     * @param string $username
     * @return number
     */
    public function count_search_results(string $username, int $user_id) {
        return $this->select()
                    ->join_request_table($user_id)
                    ->join_friends_table($user_id)
                    ->db
                    ->where(self::ACCOUNTS.'.user_id!=', $user_id)
                    ->where(self::REQUESTS.'.friend_request_id', NULL)
                    ->where(self::FRIENDS.'.friends_id', NULL)
                    ->like(self::ACCOUNTS.'.username', $username)
                    ->count_all_results(self::ACCOUNTS);
    }
    
}

