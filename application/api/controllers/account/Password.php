 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller
{
    
    /**
     * Change account password
     * 
     * @Map - http://website/api/account/change/password
     */
    public function index() {
        $this->auth->loggedin();
        
        $this->form_validation->set_rules('old_password', 'old password', 'required|callback_matches_password');
        $this->form_validation->set_rules('new_password', 'new password', 'required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[new_password]');
        
        if($this->form_validation->run() == false) {
            $message = 'Invalid data submission please enter correct account password or try again later.';
            return $this->api->api_response(false, $message, $this->form_validation->error_array());
        }
        
        $where = ['user_id' => $this->auth->account('user_id')];
        $update = ['password' => $this->encryption->encrypt($this->input->post('new_password'))];
        
        if($this->accounts->update($where, $update) == false) {
            return $this->api->api_response(false, 'Something went wrong when tring to connect to datbase.');
        }
        
        return $this->api->api_response(true, 'Account password has been changed successfully.');
    }
    
    /**
     * Check if password matches account password
     * 
     * @param string $password
     * @return boolean
     */
    public function matches_password(string $password = NULL) {
        $account_password = $this->encryption->decrypt($this->auth->account('password'));
        if($password != $account_password) {
            $this->form_validation->set_message('matches_password', 'The {field} does not match account password.');
            return false;
        }
        return true;
    }
}

