<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>CloudPages - User Registration</title>
	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="favicon.png">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="css/signup.css" rel="stylesheet">-->
	
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
		
			
		<div class="container">

      <form class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" 
                method="post" 
                name="registration_form">
        <h2>Please Sign Up</h2></br></br>
		
        <div class="form-group">
			<label for="username" class="col-sm-2 control-label">User Name</label>
			<div class="col-sm-10">
			<input type="text" class="form-control" placeholder="Enter User Name" name='username' id='username' required autofocus>
			</div>
		</div>
		
		<div class="form-group">
			<label for="email"  class="col-sm-2 control-label">E-Mail</label>
			<div class="col-sm-10">
			<input type="email" class="form-control" placeholder="Your E-Mail ID" name='email' id='email' required>
			</div>
		</div>
			
		<div class="form-group">
			<label for="password" class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10">
			<input type="password" class="form-control" placeholder="Enter Password" name='password' id='password'>
			</div>
		</div>
		<div class="form-group">
			<label for="confirmpwd" class="col-sm-2 control-label">Confirm Password</label>
			<div class="col-sm-10">
			<input type="password" class="form-control" placeholder="Confirm Password" name='confirmpwd' id='confirmpwd'>
			</div>
		</div>
		
		<label class="checkbox">
			<input type="checkbox" value="remember-me"> Remember me
		</label>
        
		<button class="btn btn-primary" type="submit" value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);">Sign Up</button>
      </form>

	  <p>Return to the <a href="index.php">login page</a>.</p>
    </body>
</html>