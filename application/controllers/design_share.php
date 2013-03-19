<?php

    class Design_Share extends CI_Controller{
        
        public function view($page="home") {
            
            #uses conditional to check if request page exists
            if(!file_exists('application/views/pages/'.$page.'.php')) {
                
                #if page doesn't exist, show the 404 message
                show_404();
                
            }
            
            #loads the header and footer include files for the page, as well as the currentPage
            $this->load->view('html_templates/header.inc');
            $this->load->view('pages/'.$page.'.php');
            $this->load->view('html_templates/footer.inc');
            
        }//end View Function
        
        public function login() {
            
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
                        $this->view('login_error');
                    }else{
                        #if username/password are in correct format
                        
                        #authenticate the user's login info
                        $user = $this->designshare_model->getUserByPassword($username,$password);
                        $this->view('list');
                        unset($post['login_button']);
                    }  
                    
                }
                
            }
            
        }//end Login Function
        
        public function signup() {
            
            #creates variable to hold a reference to the CodeIgniter $_POST variable
            $post = $this->input->post(NULL,TRUE);
            
            #loads the model for sending database queries
            $this->load->model('designshare_model');
            
            #registers the new user in the database, saves returned user info for login
            $new_user = $this->designshare_model->registerUser($post);
            
            #log the new user in using the getUserByPassword function and the $new_user_info array values
            $user = $this->designshare_model->getUserByPassword($new_user['user'],$new_user['password']);
            unset($post['signup_button']);
            $this->view('list');
            
        }//end SignUp Function
        
        public function updateUser() {
            
            #loads the URL helper class so the base_url() method can be used
            $this->load->helper('url');
            
            #creates variable to hold a reference to the CodeIgniter $_POST variable
            $post = $this->input->post(NULL,TRUE);
            
            #loads the model for sending database queries
            $this->load->model('designshare_model');
            
            $this->designshare_model->updateUserInfo($post);
            $this->view('list');
            
        }//end UpdateUser Function
        
        public function deleteUser() {
            #loads the URL helper class so the base_url() method can be used
            $this->load->helper('url');
            
            #creates variable to hold a reference to the CodeIgniter $_POST variable
            $post = $this->input->post(NULL,TRUE);
            
            #loads the model for sending database queries
            $this->load->model('designshare_model');
            
            #deletes user
            $this->designshare_model->deleteUser();
            
            #redirects page back to home page
            header('Location: '.base_url('index.php'));
            #exits the current function
            exit;
            
        }//end DeleteUser Function
    }

?>