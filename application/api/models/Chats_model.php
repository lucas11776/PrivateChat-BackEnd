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
                    ->where([self::TABLE.'.from_user' => $user, self::TABLE.'.to_user' => $friend])
                ->group_end()
                ->or_group_start()
                    ->where([self::TABLE.'.to_user' => $user, self::TABLE.'.from_user' => $friend])
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
     * Join user and friend accounts table
     *
     * @param int
     */
    public function get(int $user, int $friend, int $limit = NULL, int $offset = NULL) {
        $result = $this->select_chats_fields($user)
                    ->join_accounts_table($user, $friend)
                    ->where_chats($user, $friend)
                    ->db
                    ->get(self::TABLE)
                    ->result_array();
        return $result;
    }
    
    /**
     * Join user and friend accounts table
     *
     * @param int
     */
    public function latest_chats(int $user, int $friend, string $last_text, int $limit = NULL) {
        $result = $this->select_chats_fields($user)
        ->join_accounts_table($user, $friend)
        ->where_chats($user, $friend)
        ->db
        ->where(self::TABLE.'.created >', $last_text)
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
    private function count(int $user, int $friend) {
        return $this->join_accounts_table($user, $friend)
                    //->where_chats($user, $friend)
                    ->count_all_results();
    }
    
    /**
     * Delete chats in database
     * 
     * @param array $where
     * @return string|boolean
     */
    private function delete(array $where) {
        
    }
    
}

