<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends CI_Model
{
    
    /**
     * Users accounts table name in database
     * 
     * @var string
     */
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
    
    /**
     * Get users accounts from database
     * 
     * @param array $where
     * @param int $limit
     * @param int $offset
     * @return array
     */
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
    
    /**
     * Get account by username or email
     * 
     * @param string $username
     * @return array
     */
    public function get_account_username(string $username) {
        return $this->db->where('username', $username)
                        ->or_where('email', $username)
                        ->get(self::TABLE, 1)
                        ->result_array()[0] ?? [];
    }
    
    /**
     * Updated account values/fields
     * 
     * @param array $where
     * @param array $replace
     * @return boolean
     */
    public function update(array $where, array $replace) {
        return $this->db->where($where)
                        ->update(self::TABLE, $replace);
    }
    
}

