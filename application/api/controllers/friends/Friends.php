<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller
{
    
    private const LIMIT = 30;
    
    /**
     * Get user friends from database
     * 
     * @Maps - http://website/api/friends
     */
    public function index(string $search = NULL) {
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : 0;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response($this->friends->friends($search ?? '', 1, $limit, $offset));
    }

    public function chat_preview() {
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : 0;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response($this->friends->friends($search ?? '', 1, $limit, $offset));
    }
    
}

