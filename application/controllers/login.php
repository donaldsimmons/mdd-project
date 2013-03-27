<?php

    class Login extends CI_Controller {
        
        public function __construct() {
            
            #when the class is called run the parent's constructor function to inherit from parent
            #class
            parent::__construct();
            
            #load the session library for use with this class
            $this->load->library('session');
            
        }//end __Constructor Function
        
        public function submit() {
            
            #loads the helper class for urls
            $this->load->helper('url');
            
            #creates variable to hold a reference to the CodeIgniter $_POST variable
            $post = $this->input->post(NULL,TRUE);
            
            #loads the model for sending database queries
            $this->load->model('designshare_model');
            
            #uses conditional to check the value for the username input field in the $post array
            if(empty($post['username'])) {
                #if no username was submitted, the username variable is empty
                $username = '';
            }else{
                #if a username was submitted, make sure the string is all lowercased and trim the whitespace
                $username = strtolower(trim($post['username']));
            }
            
             #uses conditional to check the value for the password input field in the $post array
            if(empty($post['password'])) {
                #if no password was submitted, the password variable is empty
                $password = '';
            }else{
                #if a password was submitted, make sure the string is all lowercased and trim the whitespace
                $password = strtolower(trim($post['password']));
            }
            
             #uses conditional to check whether a user has attempted a log in by looking for a
            #login_button key in the $post array
            if($post['login_button']) {
                #if the button was clicked:
                
                #uses conditional to make sure username and password were filled in
                if(!empty($username) && !empty($password)) {
                    
                    #uses conditional to check for correct username/password format
                    if(preg_match_all('/[^a-z]/',$username) || preg_match_all('/[^a-z]/',$password)) {
                        #if username/password aren't all lowercase letters 'a-z'
                        //run the view function to show the 'login_error' message
                        $this->design_share->view('login_error');
                    }else{
                        #if username/password are in correct format
                        
                        #authenticate the user's login info
                        $user = $this->designshare_model->getUserByPassword($username,$password);
                        $profile_info = $this->designshare_model->getUserInfo($user);
                        
                        #sets the login_state indicator to true, so the site can check if a
                        #user has been signed in
                        $this->session->set_userdata('login_state',TRUE);
                        
                        #stores the user data for id, username, and password in the session cookie
                        $this->session->set_userdata($user);
                        #requests and stores the user data for visible user information in the session
                        $this->session->set_userdata($profile_info);
                        
                        #changes the view by redirecting back to the view function of the default
                        #class
                        redirect(base_url('index.php'));
                        #removes the login button from the $post array
                        unset($post['login_button']);
                    }  
                    
                }
                
            }
             
        }//end Submit Function
        
    }

?>