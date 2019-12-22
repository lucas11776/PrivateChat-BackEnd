<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete extends CI_Controller
{

    /**
     * Delete chat or chats
     * 
     * @Maps - http://website/api/chats/delete
     */
    public function index() {
        $this->form_validation->set_rules('chat', 'chat', 'required');

        die($this->input->post('chat'));
    }

    private function delete_chat() {

    }

    private function delete_video() {

    }

    private function delete_picture() {

    }

}