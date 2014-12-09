<?php

include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'send_mail.php';

if(isset($_GET['email']) && isset($_GET['code'])){
    password_reset($mysqli, $_GET['email']);
}

function password_reset($mysqli, $email){
    $code = rand(1000,9999);
    if(isset($email) && isset($code)){
        if($slct = $mysqli->query("SELECT user_id FROM users WHERE email = '$email'")){
            $u_slct = $slct->fetch_assoc();
            if($user_id = $u_slct['user_id']){
                if($e_sl = $mysqli->query("SELECT user_id, reset_code, used FROM password_reset WHERE user_id = $user_id")){
                    $ex_sl=$e_sl->fetch_assoc();
                    if($e_sl->num_rows == 1){
                        if($ex_sl['used'] == 0){

                            // password_reset
                            if(isset($_GET['code'])){
                                if($_GET['code']==$ex_sl['reset_code']){
                                    if ($insert_stmt = $mysqli->query("UPDATE password_reset SET used=1 WHERE user_id = $user_id")) {
                                            echo json_encode(array('success'=>1,'user_id'=>$user_id));
                                    }
                                    else
                                        echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Used - update failed'));
                                }
                                else
                                    echo json_encode(array('success'=>0, 'error_id'=>5, 'error_msg'=>'Wrong reset code'));
                            }
                            else{
                                $error_msg1 = 'Email has already been sent with the reset code. Didnot receive the mail?<a href=send_mail.php?email=$email&mail_type=password_reset>Resend</a>';
                                echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$error_msg1));
                            }
                        }
                        else{
                            echo json_encode(array('success'=>0, 'error_id'=>4, 'error_msg'=>'Your account has been confirmed already. Please SignIn'));
                        }
                    }
                    else{
                        if ($insert_stmt = $mysqli->prepare("INSERT INTO password_reset (user_id, reset_code) VALUES (?, ?)")) {
                            $insert_stmt->bind_param('ii', $user_id, $code);
                            // Execute the prepared query.
                            if (! $insert_stmt->execute()) {
                                echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'User password reset Insert failed'));
                            }
                            else{
                                // echo json_encode(array('success'=>1));
                                // $mysqli->close();
                                send_mail($mysqli, $email, 'password_reset');
                            }
                        }
                    }
                }
            }
            else
                echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'User doesnot exist'));
        }
        else
            echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'fetch user_id failed'));
    }
}


?>