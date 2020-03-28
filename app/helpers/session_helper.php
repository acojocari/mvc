<?php

session_start();

//Flash message helper
//Example - flash('register_success','Your are now register','alert alert-danger');
// Display in view - echo flash('register_success');
function flash($name='',$message='',$class='alert alert-success'){
    if(!empty($name)){
        if(!empty($message && empty($_SESSION[$name]))){
            if(!empty($_SESSION[$name])){
                usent($_SESSION[$name]);
            }
            if(!empty($_SESSION[$name.'_class'])){
                usent($_SESSION[$_SESSION[$name.'_class']]);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        }elseif (empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name.'_class'])? $_SESSION[$name.'_class']:'';
            echo '<div class="'.$class.'" id="msg-flag">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset( $_SESSION[$name.'_class']);
        }
    }
}
