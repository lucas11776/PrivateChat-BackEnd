<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends CI_Model
{
    
    public const TABLE = 'accounts';
    
    /**
     * Insert/Create new account in database
     * 
     * @param array $data
     * @return boolean
     */
    public function insert(array $data) {
        return $this->db->insert(self::TABLE, $data);
    }
    
    public function get(array $where = [], int $limit = NULL, int $offset = NULL) {
        return $this->db->where($where)
                        ->order_by('created', 'DESC')
                        ->get(self::TABLE, $limit, $offset)
                        ->result_array();
    }
    
    /**
     * Check if username exist in database
     * 
     * @param string $username
     * @return boolean
     */
    public function username_exist(string $username) {
        return count($this->get(['username' => $username])) > 0 ? true : false;
    }
    
    /**
     * Check if email address exist in database
     * 
     * @param string $email
     * @return boolean
     */
    public function email_exist(string $email) {
        return count($this->get(['email' => $email])) > 0 ? true : false;
    }
    
    
    
}

