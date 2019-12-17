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
    
    /**
     * Select friends count public fields
     * 
     * @return Friends_model
     */
    private function select_chat_count() {
        $this->db->select(self::ACCOUNTS.'.username,'.self::ACCOUNTS.'.profile_picture,'.self::ACCOUNTS.'.last_seen,
        (SELECT COUNT(*) FROM '.self::CHATS.' WHERE ('.self::CHATS.'.to_user=1 AND '.self::CHATS.'.from_user='.self::TABLE.'.from_user)) AS messages');
        return $this;
    }
    
    private function select_chat_preview() {
        $this->db->select('accounts.username,accounts.profile_picture,accounts.last_seen,
            (SELECT COUNT(*) FROM chats WHERE (chats.to_user=1 AND chats.from_user=friends.from_user)) AS messages,
            text.content AS text,text.created AS text_created');
        return $this;
    }
    
    /**
     * Join chats table with friendship latest text
     * 
     * @return Friends_model
     */
    private function join_chats_table_message() {
        $table = '(SELECT * FROM chats c ORDER BY c.chat_id DESC LIMIT 1) AS text';
        $cord = '(text.from_user='.self::TABLE.'.from_user AND text.to_user='.self::TABLE.'.to_user) OR 
                 (text.to_user='.self::TABLE.'.from_user AND text.from_user='.self::TABLE.'.to_user)';
        $this->db->join($table, $cord, 'LEFT');
        return $this;
    }
    
    /**
     * Join request table with `TABLE` by user id and friend id
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
     * Join friends table with `TABLE` by user id  and friend id
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
     * Join account table with `TABLE`
     * 
     * @param int $user_id
     * @return Friends_model
     */
    private function join_account_table(int $user_id) {
        $cord = '('.self::ACCOUNTS.'.user_id='.self::TABLE.'.from_user AND '.self::TABLE.'.from_user!='.$user_id.') OR (';
        $cord .= self::ACCOUNTS.'.user_id='.self::TABLE.'.to_user AND '.self::TABLE.'.to_user!='.$user_id.')';
        $this->db->join(self::ACCOUNTS, $cord, 'LEFT');
        return $this;
    }
    
    /**
     * Insert a new friend relationship in database
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
    
    /**
     * Check if friendship exist between two users
     * 
     * @param int $user
     * @param int $friend
     * @return boolean
     */
    public function friendship_exist(int $user, int $friend) {
        $exist = $this->db
            ->group_start()
                ->where(['from_user' => $user, 'to_user' => $friend])
            ->group_end()
            ->or_group_start()
                ->where(['to_user' => $user, 'from_user' => $friend])
            ->group_end()
            ->get(self::TABLE)
            ->result_array();
        return count($exist) !== 0 ? true : false;
    }

    /**
     * Get user friends from datbase
     * 
     * @param string $search
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function friends(string $search ,int $user_id, int $limit = NULL, int $offset = NULL) {
        return $this->select_chat_count()
                    ->join_account_table($user_id)
                    ->db
                    ->order_by(self::TABLE.'.created', 'DESC')
                    ->where([self::TABLE.'.from_user' => $user_id])
                    ->or_where([self::TABLE.'.to_user' => $user_id])
                    ->get(self::TABLE)
                    ->result_array();
    }

    /**
     * Get user friends from datbase
     *
     * @param string $search
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function friends_chat_preview(string $search ,int $user_id, int $limit = NULL, int $offset = NULL) {
        return $this->select_chat_preview()
                    ->join_account_table($user_id)
                    ->join_chats_table_message()
                    ->db
                    ->order_by('text_created', 'DESC')
                    ->where([self::TABLE.'.from_user' => $user_id])
                    ->or_where([self::TABLE.'.to_user' => $user_id])
                    ->get(self::TABLE)
                    ->result_array();
    }
    
}

