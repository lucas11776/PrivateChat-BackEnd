<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller
{
    
    private const LIMIT = 20;
    
    public function index($search = NULL) {
        $this->auth->loggedin();
        $limit = is_numeric($this->input->get('limit')) ? $this->input->get('limit') : self::LIMIT;
        return $this->api->response([
            'number_results' => $this->friends->search_result_count($search ?? ''),
            'results' => $this->friends->search($search ?? '', $limit)
        ]);
    }
    
}

