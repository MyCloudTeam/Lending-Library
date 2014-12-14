<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    include_once '../includes/friendship.php';
     
    if(session_status() != PHP_SESSION_ACTIVE){
        sec_session_start(); // Our custom secure way of starting a PHP session.
    }

    if(login_check($mysqli) != TRUE){
        echo json_encode(array('success'=>0, 'error_id'=>401, 'error_msg'=>'Not authorized to view this page'));
        die();
    }

    function get_user_info($mysqli, $user_id){
        if($e_sl = $mysqli->query("SELECT user_name, email FROM users WHERE user_id = $user_id")){
            return $ex_sl=$e_sl->fetch_assoc();
        }
        else
            return NULL;
    }

    function get_lib_uploaded($mysqli, $user_id){
        if($e_sl = $mysqli->query("SELECT * FROM books WHERE user_id = $user_id AND privacy!='private'")){
            $ex_sl = $e_sl->fetch_assoc();
            if(count($ex_sl)>0){
                foreach ($ex_sl as $i => $book) {
                    $ex_sl[$i]['uploaded_by'] = get_user_info($ex_sl['user_id']);
                }
            }
            return $ex_sl;
        }
        else
            return NULL;
    }

    function get_private_lib($mysqli, $user_id){
        if($e_sl = $mysqli->query("SELECT * FROM books WHERE user_id = $user_id AND privacy='private'")){
            $ex_sl = $e_sl->fetch_assoc();
            if(count($ex_sl)>0){
                foreach ($ex_sl as $i => $book) {
                    $ex_sl[$i]['uploaded_by'] = get_user_info($ex_sl['user_id']);
                }
            }
            return $ex_sl;
        }
        else
            return NULL;
    }

    function get_shared_with_me($mysqli, $user_id){
        if($e_sl = $mysqli->query("SELECT * FROM book_user JOIN books WHERE book_user.user_id = $user_id")){
            $ex_sl = $e_sl->fetch_assoc();
            if(count($ex_sl)>0){
                foreach ($ex_sl as $i => $book) {
                    $ex_sl[$i]['uploaded_by'] = get_user_info($ex_sl['user_id']);
                }
            }
            return $ex_sl;
        }
        else
            return NULL;
    }

    if (isset($_GET['user'])) {
        if($user = get_user_info($mysqli, $_GET['user'])){
            if($_GET['user']==$_SESSION['user_id']){
                $user['friendship'] = "self";
            }
            else{
                $user['friendship'] = check_friendship($mysqli, $_SESSION['user_id'], $_GET['user']);
            }

            $user_uploads = get_lib_uploaded($mysqli, $_GET['user']);
            $shared_with_me = get_shared_with_me($mysqli, $_GET['user']);

            $user_lib = array('uploads'=>$user_uploads, 'shared_with_me'=>$shared_with_me);

            if($user['friendship'] == "self")
                $user_lib['private_lib'] = get_private_lib($mysqli, $_GET['user']);

            echo json_encode(array('success'=>1, 'user'=>$user, 'user_lib'=>$user_lib));
        }
        else
            echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'User doesnot exist'));
    }
    else {
        // The correct POST variables were not sent to this page. 
        echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'User Id is not set'));
    }