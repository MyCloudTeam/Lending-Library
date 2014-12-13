<?php
    include_once 'db_connect.php';
    include_once 'functions.php';
     
    sec_session_start(); // Our custom secure way of starting a PHP session.

    function get_user_info($user_id){
        if($e_sl = $mysqli->query("SELECT user_name, email FROM users WHERE user_id = $user_id")){
            return $ex_sl=$e_sl->fetch_assoc();
        }
    }

    if (isset($_GET['user'])) {
        $email = $_POST['email'];
        $password = $_POST['p']; // The hashed password.
     
        if (login($email, $password, $mysqli) == true) {
            // Login success 
            if(isset($_POST['mobile'])){
                echo json_encode(array('success'=>1, 'user_id'=>$_SESSION['user_id'], 'user_name'=>$_SESSION['user_name'] ));
            }
            else
                header('Location: ../home.php');
        } else {
            // Login failed 
            if(isset($_POST['mobile'])){
                echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'Login failed'));
            }
            else
                header('Location: ../index.php?error=1');
        }
    } else {
        // The correct POST variables were not sent to this page. 
        echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'Invalid Request'));
    }