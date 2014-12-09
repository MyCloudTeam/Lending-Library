<?php

include_once 'db_connect.php';
include_once 'PHPMailer/PHPMailerAutoload.php';

if(isset($_GET['mail_type']) && isset($_GET['email'])){
	send_mail($mysqli, $_GET['email'], $_GET['mail_type']);
}

function aws_ses($mysqli, $email, $user_id, $user_name, $msg){

    //SMTP Settings
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Host       = "email-smtp.us-east-1.amazonaws.com";
    $mail->Username   = "AKIAJPJEX3LBSEHPAUUQ";
    $mail->Password   = "Aks9gR9BRyiSdCVDm5lb36oxS2C+Ag3+l6w57HWwxyFU";
    //

    $mail->SetFrom('ktv205@nyu.edu', 'CloudPages'); //from (verified email address)
    $mail->Subject = "CloudPages - User Confirmation Required"; //subject

    // $body = eregi_replace("[]",'',$body);
    $mail->MsgHTML($msg);
    //

    //recipient
    $mail->AddAddress($email, $user_name);

    //Success
    if ($mail->Send()) {
        echo json_encode(array('success'=>1, 'user_id'=>$user_id, 'mail_msg'=> $msg));
        die;
    }

    //Error
    else {
        echo json_encode(array('success'=>0, 'error_id'=>4, 'error_msg'=>'Sending Mail failed'));
    }

}

function send_mail($mysqli, $email, $mail_type){
	if($mail_type == "confirmation"){
	    if($slct = $mysqli->query("SELECT user_id, user_name FROM users WHERE email = '$email'")){
	        if($u_slct=$slct->fetch_assoc()){
	            if($user_id = $u_slct['user_id']) {
	                if($ex_sl = $mysqli->query("SELECT confirmation_code, check_status FROM user_confirmation WHERE user_id = $user_id")){
	                    $e_sl = $ex_sl->fetch_assoc();
	                    $msg = "Hi ".$u_slct['user_name'].", Please use the following link and enter the code <b>".$e_sl['confirmation_code']."</b> to confirm your Email address and complete the SignUp process <br/>
                				<a href='http://54.174.122.19/eLibrary/lib/confirmSignUp.php'>Click here to complete Registration</a>";
	                    aws_ses($mysqli, $email, $user_id, $u_slct['user_name'], $msg);
	                    // echo json_encode(array('success'=>1, 'mail_msg'=> $e_sl['confirmation_code']));
	                }
	                else
	                    echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'db_error'));
	            }
	            else
	                echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'user doesnot exist'));
	        }
	        else
	            echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'db_error'));
	    }
	    else
	    	echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'Not Signed Up'));
	}
	elseif($mail_type == "password_reset"){
		if($slct = $mysqli->query("SELECT user_id FROM users WHERE email = '$email'")){
            $u_slct = $slct->fetch_assoc();
            if($user_id = $u_slct['user_id']){
                if($e_sl = $mysqli->query("SELECT user_id, reset_code, used FROM password_reset WHERE user_id = $user_id")){
                    $ex_sl=$e_sl->fetch_assoc();
                    $msg = "Hi ".$u_slct['user_name'].", Please use the following link and enter the code <b>".$e_sl['reset_code']."</b> to reset your password <br/>
                				<a href='http://54.174.122.19/eLibrary/lib/confirmSignUp.php'>Click here to reset Password</a>";
	                    aws_ses($mysqli, $email, $user_id, $u_slct['user_name'], $msg);
                }
            }
        }

	}
}

?>