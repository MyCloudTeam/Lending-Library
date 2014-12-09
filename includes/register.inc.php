<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'send_mail.php';
 
$error_msg = "";

// check the verification code
if(isset($_GET['email']) && isset($_GET['code'])){
    user_confirmation($mysqli, $_GET['email']);
}

function user_confirmation($mysqli, $email){
    $code = rand(1000,9999);
    if(isset($email) && isset($code)){

        if($slct = $mysqli->query("SELECT user_id FROM users WHERE email = '$email'")){
            $u_slct = $slct->fetch_assoc();
            if($user_id = $u_slct['user_id']){
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
                                        else
                                            echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Activation failed'));
                                    }
                                    else
                                        echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Check Status update failed'));
                                }
                                else
                                    echo json_encode(array('success'=>0, 'error_id'=>5, 'error_msg'=>'Wrong verification code'));
                            }
                            else{
                                $error_msg1 = 'Email has already been sent with the code. Didnot receive the mail?<a href=send_mail.php?email=$email&mail_type=confirmation>Resend</a>';
                                echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$error_msg1));
                            }
                        }
                        else{
                            echo json_encode(array('success'=>0, 'error_id'=>4, 'error_msg'=>'Your account has been confirmed already. Please SignIn'));
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
 
if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // echo "*****'$_POST['p'];
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(isset($_POST['auth']))
        $auth = $_POST['auth'];
    else
        $auth = "gen";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
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