<?php

class Users extends Controller {
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register(){
     //check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            //Init data
            $data=[
                'name'=>trim($_POST['name']),
                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),
                'confirm_password'=>trim($_POST['confirm_password']),
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>''
            ];

            //Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }else{
                //Check email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }

            //Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            //Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }elseif (strlen($data['password'] < 6)){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            //Validate Confirm password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            }else{
                if($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'Password do not match';
                }
            }

            //Make sure that those errors are empty
            if(empty($data['email_err'])
                && empty($data['name_err'])
                && empty($data['password_err'])
                && empty($data['confirm_password_err'])){

                //Hash Password
                $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);

                //Register User
                if($this->userModel->register($data)){
                    //redirect the user if the authentication is done without errors
                    flash('register_success','You are registred and can log in');
                    redirect('users/login');
                }else{
                    die('Something went wrong!');
                };

            }else{
                //Load view with errors
                $this->view('users/register',$data);
            }

     }else
        {
            //load de form
            //Init data
            $data=[
                'name'=>'',
                'email'=>'',
                'password'=>'',
                'confirm_password'=>'',
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>''
            ];

            //Load view
            $this->view('users/register',$data);
        }
    }

    public function login(){
        //check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            //Init data
            $data=[
                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),
                'email_err'=>'',
                'password_err'=>'',
            ];

            //Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            //Validate Password
            if(empty($data['email'])){
                $data['password_err'] = 'Please enter password';
            }

            //Check for user/email
            if($this->userModel->findUserByEmail($data['email'])){
                //User found
            }else{
                $data['email_err']= 'No user found';
            }

            //Make sure that those errors are empty
            if(empty($data['email_err'])
                && empty($data['password_err'])){
                //Data validated
                //Check and set logged in user
                $loggedInUser= $this->userModel->login($data['email'], $data['password']);
                if($loggedInUser){
                    //Create Session
                    die('success');
                }else{
                    $data['password_err']='Password incorrect';
                    $this->view('users/login',$data);
                }
            }else{
                //Load view with errors
                $this->view('users/login',$data);
            }

        }else
        {
            //load de form
            //Init data
            $data=[
                'email'=>'',
                'password'=>'',
                'email_err'=>'',
                'password_err'=>''
            ];

            //Load view
            $this->view('users/login',$data);
        }
    }
}