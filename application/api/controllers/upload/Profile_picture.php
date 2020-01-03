<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_picture extends CI_Controller
{

    /**
     * Upload new user profile picture
     * 
     * Maps - http://website/api/upload/profile/picture
     */
    public function index() {
        $this->auth->loggedin();

        $this->form_validation->set_rules('picture', 'profile picture', 'callback_upload');

        if($this->form_validation->run() == false) {
            return $this->api->api_response(false, $this->form_validation->error_array()['picture']);
        }

        // upload file path
        $file_path = $this->file_upload::PROFILE_PICTURE_CONFIG['upload_path'] . $this->file_upload->data('file_name');

        // update profile picture
        $update = $this->accounts->update(
            ['user_id' => $this->auth->account('user_id')], 
            ['profile_picture' => base_url($file_path)]
        ); 

        if($update == false) {
            // delete upload profile picture
            unlink($file_path);
            return $this->api->api_response(false, 'Something went wrong when tring to connect to database');
        }
       
        // default profile picture
        $default_profile_picture = base_url($this->accounts::PROFILE_PICTURE);
        
        // check if profile picture is not default profile picture
        if($default_profile_picture != $this->auth->account('profile_picture')) {
            unlink(str_replace(base_url(), '', $this->auth->account('profile_picture')));
        }

        return $this->api->api_response(true, 'Profile picture has been uploaded successfully.');
    }

    /**
     * Upload user profile picture
     * 
     * @return boolean
     */
    public function upload() {
        if($this->file_upload->profile_picture('picture') == false) {
            $this->form_validation->set_message('upload', $this->file_upload->error());
            return false;
        }
        return true;
    }

}
