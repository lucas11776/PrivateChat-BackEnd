<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller
{
    
    private const LIMIT = 20;
    
    public function index($search = NULL) {
        $this->auth->loggedin();
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : self::LIMIT;
        $offset = is_numeric($this->input->get('offset')) ? $this->input->get('offset') : 0;
        return $this->api->response([
            'number_results' => $this->friends->count_search_results($search ?? '', $this->auth->account('user_id')),
            'results' => $this->friends->search($search ?? '', $this->auth->account('user_id'), $limit, $offset)
        ]);
    }
    
}

