<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Input
{
    
    /**
     * Get input stream data and assign data to POST
     * 
     * assign stream data to post munally because PHP
     * deos not assing ajax data to post
     */
    public function __construct() {
        // get raw post request data and assign it to POST
        $_POST = json_encode($this->input_stream(), true);
        parent::__construct();
    }
    
}