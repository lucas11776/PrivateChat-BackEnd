<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chats_model extends CI_Model
{
    
    /**
     * Chats table name in database
     * 
     * @var string
     */
    private const TABLE = 'chats';
    
    /**
     * Account table name in datbase
     * 
     * @var string
     */
    private const ACCOUNTS = 'accounts';
    
    /**
     * Join user and friend accounts table 
     * 
     * @return Chats_model
     */
    private function join_accounts_table(int $user, int $friend) {
        $from_cord = 'from.user_id='.self::TABLE.'.from_user';
        $to_cord = 'to.user_id='.self::TABLE.'.to_user';
        $this->db->join(self::ACCOUNTS.' AS from', $from_cord, 'LEFT');
        $this->db->join(self::ACCOUNTS.' AS to', $to_cord, 'LEFT');
        return $this;
    }
    
    /**
     * Where clue to select user or friend id in from_user and to_user
     * 
     * @param int $user
     * @param int $friend
     * @return Chats_model
     */
    private function where_chats(int $user, int $friend) {
        $this->db->group_start()
                    ->group_start()
                        ->where([self::TABLE.'.from_user' => $user, self::TABLE.'.to_user' => $friend])
                    ->group_end()
                    ->or_group_start()
                        ->where([self::TABLE.'.to_user' => $user, self::TABLE.'.from_user' => $friend])
                    ->group_end()
                ->group_end();
        return $this;
    }
    
    /**
     * Select chats fields from database
     * 
     * @param int $user
     * @return Chats_model
     */
    private function select_chats_fields(int $user) {
        $this->db->select(
            self::TABLE.'.chat_id,'.self::TABLE.'.created,'.self::TABLE.'.type,'.self::TABLE.'.content,'.
            'from.profile_picture,from.username AS from, to.username AS to'
        );
        return $this;
    }
    
    /**
     * Insert chat in database
     * 
     * @param array
     * @return boolean
     */
    public function insert(array $data) {
        return $this->db->insert(self::TABLE, $data);
    }

    /**
     * Get chat from database
     */
    public function get_chat(int $chat_id) { 
        return $this->db->where('chat_id', $chat_id)
                        ->get(self::TABLE)
                        ->result_array()[0] ?? [];
    }
    
    public function get_all_chats(int $user, int $friend) {
        return $this->where_chats($user, $friend)
                    ->db
                    ->get(self::TABLE)
                    ->result_array();
    }

    /**
     * Join user and friend accounts table
     *
     * @param int $user
     * @param int $friend
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get(int $user, int $friend, int $limit = NULL, int $offset = NULL) {
        $result = $this->select_chats_fields($user)
                    ->join_accounts_table($user, $friend)
                    ->where_chats($user, $friend)
                    ->db
                    ->order_by(self::TABLE.'.chat_id', 'ASC')
                    ->get(self::TABLE)
                    ->result_array();
        return $result;
    }

    /**
     * Register messages as seen
     * 
     * @param int $user_id
     * @return boolean
     */
    public function chats_seen(int $user_id, int $friend_id) {
        return $this->db->where(['from_user' => $friend_id, 'to_user' => $user_id, 'seen' => 0])
                        ->update(self::TABLE, ['seen' => 1]);
    }
    
    /**
     * Join user and friend accounts table
     *
     * @param int
     */
    public function latest_chats(int $user, int $friend, int $chat_id, int $limit = NULL) {
        $result = $this->select_chats_fields($user)
            ->join_accounts_table($user, $friend)
            ->where_chats($user, $friend)
            ->db
            ->order_by(self::TABLE.'.chat_id', 'ASC')
            ->group_start()
                ->where(self::TABLE.'.chat_id >', $chat_id)
            ->group_end()
            ->get(self::TABLE)
            ->result_array();
        return $result;
    }
    
    /**
     * Count number chat between users and friend
     *
     * @param int User ID
     * @param int Friend ID
     * @return int
     */
    public function count(int $user, int $friend) {
        return $this->join_accounts_table($user, $friend)
                    ->where_chats($user, $friend)
                    ->count_all_results();
    }
    
    /**
     * Delete chats in database
     * 
     * @param array $where
     * @return string|boolean
     */
    public function delete(array $where) {
        return $this->db->where($where)
                        ->delete(self::TABLE);
    }
    
    /**
     * Clear all user chats from database
     * 
     * @param integer $user
     * @param integer $friend
     * @param integer $last_chat_id
     * @return boolean
     */
    public function clear(int $user, int $friend, int $last_chat_id = NULL) {
        $db = $this->where_chats($user, $friend)->db;
        if($last_chat_id != null) {
            $db->group_start()
                ->where(self::TABLE.'.chat_id <=', $last_chat_id)
               ->group_end();
        }
        return $db->delete(self::TABLE);
    }
    
}

