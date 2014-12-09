<?php
include_once 'psl-config.php';
include_once 'db_connect.php';
 
 //Securely start a PHP session
function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
        // var_dump($_SESSION);
    session_regenerate_id();    // regenerated the session, delete the old one.

	
	
	//Create the Login Function
	function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT user_id, user_name, password, salt 
        FROM users
       WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $user_name, $db_password, $salt);
        $stmt->fetch();

        if($e_sl = $mysqli->query("SELECT check_status FROM user_confirmation WHERE user_id = $user_id")){
            $ex_sl=$e_sl->fetch_assoc();
            if($e_sl->num_rows == 1){
                if($ex_sl['check_status'] == 0){
                    echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>'Account has to be activated. <a href="../confirmSignUp.php">Click Here </a> to enter the code sent to your Email and Activate'));
                    die();
                }
            }
        }
 
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        // echo "<br/>".$db_password;
        if ($stmt->num_rows == 1) {
             // Check if the password in the database matches
            // the password the user submitted.
            if ($db_password == $password) {
                // Password is correct!
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                $_SESSION['user_id'] = $user_id;
                // XSS protection as we might print this value
                $user_name = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                            "", 
                                                            $user_name);
                $_SESSION['user_name'] = $user_name;
                $_SESSION['login_string'] = hash('sha512', 
                          $password . $user_browser);
                // Login successful.
                return true;
            } else {
                // Password is not correct
                // We record this attempt in the database
                // $now = time();
                // $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                // VALUES ('$user_id', '$now')");
                return false;
            }

        } else {
            // No user exists.
            return false;
        }
    }
}

//Check logged in status
function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['user_name'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $user_name = $_SESSION['user_name'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM users 
                                      WHERE user_id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
                $login_string;
 
                if ($login_check == $login_string) {
                    // echo "yes";
                    // Logged In!!!! 
                    return true;
                } else {
                    // echo "check failed";
                    // Not logged in 
                    return false;
                }
            } else {
                // echo "more rows";
                // Not logged in 
                return false;
            }
        } else {
            // echo "stmt_prep_err";
            // Not logged in 
            return false;
        }
    } else {
        // echo "session not set";
        // Not logged in 
        return false;
    }
}

//Sanitize URL from PHP_SELF
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

}