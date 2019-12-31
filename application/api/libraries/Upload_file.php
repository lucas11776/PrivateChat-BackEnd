<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_file
{

    /**
     * Profile picture upload configurations
     * 
     * @var array
     */
    public const PROFILE_PICTURE_CONFIG = [
        'upload_path' => 'uploads/profile/',
        'allowed_types' => ['png','jpg','jpge'],
        'max_size' => 1000
    ];

    /**
     * Picture configuration upload configurations
     * 
     * @var array
     */
    public const PICTURE_CONFIG = [
        'upload_path' => 'uploads/picture/',
        'allowed_types' => ['png','jpg','jpge'],
        'max_size' => 1000
    ];
    
    /**
     * CodeIgniter super-object
     *
     * @var	CI_Controller
     */
    protected $CI;

    /**
     * Upload profile picture name prefix
     * 
     * @var string
     */
    private const PROFILE_PICTURE_PREFIX = 'PROFILE-';

    /**
     * Upload picture name extension
     * 
     * @var string
     */
    private const PICTURE_PREFIX = 'PICTURE-';
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('upload');
    }

    /**
     * Upload file to profile pictures
     * 
     * @param string $file
     * @return boolean
     */
    public function profile_picture(string $file) {
        $filename = ['file_name' => uniqid(self::PROFILE_PICTURE_PREFIX)];
        return $this->upload($file, array_merge(self::PROFILE_PICTURE_CONFIG, $filename));
    }

    /**
     * Upload data
     * 
     * @return array
     */
    public function data(string $field = NULL) {
        return $this->CI->upload->data($field);
    }
    
    /**
     * Upload file error
     * 
     * @return string
     */
    public function error() {
        return $this->CI->upload->display_errors('','');
    }

    /**
     * Upload file
     * 
     * @param string $file
     * @param array $config
     * @return mixed
     */
    protected function upload(string $file, array $config = []) {
        $this->CI->upload->initialize($config);
        return $this->CI->upload->do_upload($file);
    }

}

