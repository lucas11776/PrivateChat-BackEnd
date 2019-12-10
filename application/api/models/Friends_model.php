<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends_model extends CI_Model
{
    
    /**
     * Select public users account data
     * 
     * @return CI_DB
     */
    private function select() {
        return $this->db->select("
            accounts.profile_picture, accounts.username, accounts.last_seen
        ");
    }
    
    /**
     * Search for a friend account in database
     * 
     * @param string $username
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function search(string $username, int $limit = NULL, int $offset = NULL) {
        return $this->select()
                    ->like('accounts.username', $username)
                    ->order_by('accounts.create', 'RANDOM')
                    ->get($this->accounts::TABLE, $limit, $offset)
                    ->result_array();
    }
    
    /**
     * Count number of search results in database
     * 
     * @param string $username
     * @return number
     */
    public function search_result_count(string $username) {
        return $this->select()
                    ->like('accounts.username', $username)
                    ->count_all_results($this->accounts::TABLE);
    }
    
}

