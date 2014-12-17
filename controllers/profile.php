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

        if($b_sl = $mysqli->query("SELECT * FROM books WHERE user_id = $user_id AND privacy!='private'")){
            $res = array();
            while($bk = $b_sl->fetch_assoc()){

                // setting the access flag for each book
                if($bk['user_id']==$_SESSION['user_id']){
                    $bk['access'] = 1;
                }
                elseif($bk['privacy']=="public"){
                    $bk['access']=1;
                }
                elseif($f = check_friendship($mysqli, $_SESSION['user_id'], $bk['user_id'])){
                    if($f=="friend" AND $bk['privacy']=="friends"){
                        $bk['access'] = 1;
                    }
                    else{
                        $bk['access'] = 0;
                    }
                }
                else{
                    $bk['access'] = 0;
                }

                $bk['uploaded_by'] = get_user_info($mysqli, $bk['user_id']);
                array_push($res,$bk);
            }
            return $res;
        }
        else
            return NULL;
    }

    function get_private_lib($mysqli, $user_id){
        if($b_sl = $mysqli->query("SELECT * FROM books WHERE user_id = $user_id AND privacy='private'")){
            $res = array();
            while($bk = $b_sl->fetch_assoc()){
                $bk['uploaded_by'] = get_user_info($mysqli, $bk['user_id']);
                array_push($res,$bk);
            }
            return $res;
        }
        else
            return NULL;
    }

    function get_fav($mysqli, $user_id){
        if($b_sl = $mysqli->query("SELECT * FROM book_user JOIN books WHERE book_user.user_id = $user_id")){
            $res = array();
            while($bk = $b_sl->fetch_assoc()){

                // setting the access flag for each book
                if($bk['user_id']==$_SESSION['user_id']){
                    $bk['access'] = 1;
                }
                elseif($bk['privacy']=="public"){
                    $bk['access']=1;
                }
                elseif($f = check_friendship($mysqli, $_SESSION['user_id'], $bk['user_id'])){
                    if($f=="friend" AND $bk['privacy']=="friends"){
                        $bk['access'] = 1;
                    }
                    else{
                        $bk['access'] = 0;
                    }
                }
                else{
                    $bk['access'] = 0;
                }

                $bk['uploaded_by'] = get_user_info($mysqli, $bk['user_id']);
                array_push($res,$bk);
            }
            return $res;
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
            $fav = get_fav($mysqli, $_GET['user']);

            $user_lib = array('uploads'=>$user_uploads, 'fav'=>$fav);

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