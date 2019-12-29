<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_file
{
    
    /**
     * CodeIgniter super-object
     *
     * @var	CI_Controller
     */
    protected $CI;

    /**
     * Profile picture upload directory
     * 
     * @var string
     */
    public const PROFILE_PICTURE_DII = 'uploads/profile-pictures/';

    /**
     * Picture upload directory
     * 
     * @var string
     */
    public const PICTURE_DIR = 'uploads/pictures/';

    /**
     * Video upload directory
     * 
     * @var string
     */
    public const VIDEO_DIR = 'uploads/videos/';

    /**
     * Audio upload directory
     * 
     * @var string
     */
    public const AUDIO = 'uploads/audios/';
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('encryption');
    }
    

    /**
     * Upload file
     * 
     * @param string $file
     * @param array $config
     * @return mixed
     */
    protected function upload_file(string $file, array $config = []) {

    }

}

