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
     * Friends request table name in database
     * 
     * @var string
     */
    private const REQUESTS = 'friends_requests';
    
    /**
     * Chats table name in database
     * 
     * @var string
     */
    private const CHATS = 'chats';
    
    private function select_chat_count() {
        $this->db->select(
            self::ACCOUNTS.'.profile_pictre,'.self::ACCOUNTS.'.username'.self::ACCOUNTS.'.last_seen'
            
        );
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
    private function join_friends_table(int $user_id) {
        $cord = '('.self::TABLE.'.from_user='.$user_id.' AND '.self::TABLE.'.to_user='.self::ACCOUNTS.'.user_id'.') OR (';
        $cord .= self::TABLE.'.to_user='.$user_id.' AND '.self::TABLE.'.from_user='.self::ACCOUNTS.'.user_id)';
        $this->db->join(self::TABLE, $cord, 'LEFT');
        return $this;
    }
    
    /**
     * Join chats table by user id and friends id and user seen
     * 
     * @param int $user_id
     * @return Friends_model
     */
    private function join_chats_table(int $user_id) {
        $cord = '('.self::CHATS.'.to_user='.$user_id.' AND '.self::CHATS.'.from_user='.self::TABLE.'.from_user AND '.self::CHATS.'.seen=0) OR ';
        $cord .= '('.self::CHATS.'.to_user='.$user_id.' AND '.self::CHATS.'.from_user='.self::TABLE.'.to_user AND '.self::CHATS.'.seen=0)';
        $this->db->join(self::CHATS, $cord, 'LEFT');
        return $this;
    }
    
    
    private function join_account_table(int $user_id) {
        $cord = '('.self::ACCOUNTS.'.user_id='.self::TABLE.'.from_user AND '.self::TABLE.'.from_user!='.$user_id.') OR (';
        $cord .= self::ACCOUNTS.'.user_id='.self::TABLE.'.to_user AND '.self::TABLE.'.to_user!='.$user_id.')';
        $this->db->join(self::ACCOUNTS, $cord, 'LEFT');
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
        return $this->join_request_table($user_id)
                    ->join_friends_table($user_id)
                    ->db
                    ->select(self::ACCOUNTS.'.profile_picture,'.self::ACCOUNTS.'.username,'.self::ACCOUNTS.'.last_seen')
                    ->where(self::ACCOUNTS.'.user_id!=', $user_id)
                    ->where(self::REQUESTS.'.friend_request_id', NULL)
                    ->where(self::TABLE.'.friends_id', NULL)
                    ->order_by(self::ACCOUNTS.'.user_id', 'RANDOM')
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
        return $this->join_request_table($user_id)
                    ->join_friends_table($user_id)
                    ->db
                    ->where(self::ACCOUNTS.'.user_id!=', $user_id)
                    ->where(self::REQUESTS.'.friend_request_id', NULL)
                    ->where(self::TABLE.'.friends_id', NULL)
                    ->like(self::ACCOUNTS.'.username', $username)
                    ->count_all_results(self::ACCOUNTS);
    }

    
    public function friends(string $search ,int $user_id, int $limit = NULL, int $offset = NULL) {
        return $this->join_chats_table($user_id)
                    ->join_account_table($user_id)
                    ->db
                    ->select(
                        self::ACCOUNTS.'.profile_picture,'.self::ACCOUNTS.'.username,'.self::ACCOUNTS.'.last_seen,'.
                        'count('.self::CHATS.'.chat_id) as messages')
                    ->order_by(self::CHATS.'.created', 'DESC')
                    ->get(self::TABLE)
                    ->result_array();
    }
    
}

