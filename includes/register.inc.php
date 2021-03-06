<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'send_mail.php';

// $_POST['user_name'] = "kaushik";
// $_POST['user_email'] = "skb386@nyu.edu";
// $_POST['user_auth']='gen';
// $_POST['p']='hi';
 
$error_msg = "";

// check the verification code
if(isset($_GET['email']) && isset($_GET['code'])){
    user_confirmation($mysqli, $_GET['email']);
    die();
}

function user_confirmation($mysqli, $email){
    $code = rand(1000,9999);
    if(isset($email) && isset($code)){

        if($slct = $mysqli->query("SELECT user_id, auth FROM users WHERE email = '$email'")){
            $u_slct = $slct->fetch_assoc();

            if(count($u_slct)==0){
                echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'User doesnot exist'));
                die();
            }

            if($user_id = $u_slct['user_id']){

                if($u_slct['auth']=="g" OR $u_slct['auth']=="fb"){
                    echo json_encode(array('success'=>1,'user_id'=>$user_id));
                    die();
                }

                if($e_sl = $mysqli->query("SELECT user_id, confirmation_code, check_status FROM user_confirmation WHERE user_id = $user_id")){
                    $ex_sl=$e_sl->fetch_assoc();
                    if($e_sl->num_rows == 1){
                        if($ex_sl['check_status'] == 0){

                            // user_signup confirmation
                            if(isset($_GET['code'])){
                                if($_GET['code']==$ex_sl['confirmation_code']){
                                    if ($insert_stmt = $mysqli->query("UPDATE user_confirmation SET check_status=1 WHERE user_id = $user_id")) {
                                        if($insert_stmt1 = $mysqli->query("UPDATE users SET active=1 WHERE user_id=$user_id")){
                                            echo json_encode(array('success'=>1,'user_id'=>$user_id));
                                        }
                                        else{
                                            echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Activation failed'));
                                            die();
                                        }
                                            
                                    }
                                    else{
                                        echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Check Status update failed'));
                                        die();
                                    }
                                }
                                else{
                                    echo json_encode(array('success'=>0, 'error_id'=>5, 'error_msg'=>'Wrong verification code'));
                                    die();
                                }
                            }
                            else{
                                $error_msg1 = 'Email has already been sent with the code. Didnot receive the mail?<a href=send_mail.php?email=$email&mail_type=confirmation>Resend</a>';
                                echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$error_msg1));
                                die();
                            }
                        }
                        else{
                            echo json_encode(array('success'=>0, 'error_id'=>4, 'error_msg'=>'Your account has been confirmed already. Please SignIn'));
                            die();
                        }
                    }
                    else{
                        if ($insert_stmt = $mysqli->prepare("INSERT INTO user_confirmation (user_id, confirmation_code) VALUES (?, ?)")) {
                            $insert_stmt->bind_param('ii', $user_id, $code);
                            // Execute the prepared query.
                            if (! $insert_stmt->execute()) {
                                echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'User confirmation Insert failed'));
                            }
                            else{
                                // echo json_encode(array('success'=>1));
                                // $mysqli->close();
                                send_mail($mysqli, $email, 'confirmation');
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

if (isset($_POST['user_name'], $_POST['user_email'], $_POST['user_auth']) && !isset($_POST['p'])){
    if($_POST['user_auth']=="fb" OR $_POST['user_auth']=="g"){
        $_POST['p'] = "oauth";
    }
    else{
        echo json_encode(array('success'=>0, 'error_id'=>7, 'error_msg'=>'Required fields not set'));
        die();
    }
}

if(isset($_POST['user_pic'])){
    $user_pic = $_POST['user_pic'];
}
else{
    $user_pic = "/assets/users/".rand(1,9).".jpg";
}
 
if (isset($_POST['user_name']) && isset($_POST['user_email']) && isset($_POST['p'])) {
    // echo "*****'$_POST['p'];
    // Sanitize and validate the data passed in
    $username = $_POST['user_name'];
    $email = $_POST['user_email'];
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(isset($_POST['user_auth']))
        $auth = $_POST['user_auth'];
    else
        $auth = "gen";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 
    $password = $_POST['p'];
    // if (strlen($password) != 128) {
    //     // The hashed pwd should be 128 characters long.
    //     // If it's not, something really odd has happened
    //     $error_msg .= '<p class="error">Invalid password configuration.</p>';
    // }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 
    $prep_stmt = "SELECT user_id FROM users WHERE email = '$email' LIMIT 1";
     
    if ($stmt = $mysqli->query($prep_stmt)) {
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= 'A user with this email address already exists.';
        }
    }
    else {
        $error_msg .= 'Database error';
    }
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
 
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
 
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->query("INSERT INTO users (user_name, email, password, auth, salt) VALUES ('$username', '$email', '$password', '$auth', '$random_salt')")) {
            
            // echo json_encode(array('success'=>1,'user_id'=>$mysqli->insert_id));
            // $mysqli->close();
            user_confirmation($mysqli, $email);
        }
        else
            echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Insert failed'));
        // header('Location: ./register_success.php');
    }
    else
        echo json_encode(array('success'=>0,'error_id'=>6,'error_msg'=>$error_msg));
}
else
    echo json_encode(array('success'=>0, 'error_id'=>7, 'error_msg'=>'Required fields not set'));