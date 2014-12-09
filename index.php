<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Cloud Pages</title>
	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="favicon.png">
    <!-- Bootstrap -->
    <!-- <link href="css/bootstrap.css" rel="stylesheet"> -->
    <link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="Progressus/assets/css/font-awesome.min.css"> -->

    <link rel="stylesheet" href="Progressus/assets/css/bootstrap-theme.css" media="screen" >
    <link rel="stylesheet" href="Progressus/assets/css/main.css">
	
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
    
	<?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
        ?> 
	
	<div class="navbar navbar-inverse navbar-fixed-top headroom" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><b>Cloud Pages</b></a>
      </div>
      <div class="navbar-collapse collapse">
        <form class="navbar-form navbar-right" role="form" action="includes/process_login.php" method="post" name="login_form">
          <div class="form-group">
            <input type="text" placeholder="Email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <input type="password" placeholder="Password" name="password" class="form-control">
          </div>
          <button type="submit" class="btn btn-success" onclick="formhash(this.form, this.form.password);">Sign in</button>
        </form>
      </div><!--/.navbar-collapse -->
    </div>
  </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <header id="head" style="background-image: url(img/books.jpg)">
    <div class="container">
      <div class="row col-md-offset-3">
    <!-- <div class="jumbotron" style="background-image: url(img/books.jpg)"> -->
      <!-- <div class="container"> -->
        <h1  style="color:black"><center>Cloud Pages</h1>
        <h4> <center class="text-muted">Library on the Cloud that let's you access all your books from anywhere</center></h4><br/><br/>
        <!-- Read and share your books. Anywhere | Anytime -->
        <center class="text-muted"><small>Not a Member yet? </small><a class="btn btn-primary btn-sm" role="button" href="register.php">Sign Up</a></center>
      </div>
    </div>
  </header>

    

      <div class="jumbotron">
    <div class="container">
      
      <!-- <h3 class="text-center thin">Reasons to use this template</h3> -->
      
      <div class="row">
        <div class="col-md-3 col-sm-6 highlight">
          <div class="h-caption"><h4><i class="fa fa-plus fa-5" style="color:Green"></i>Create Book Repo</h4></div>
          <div class="h-body text-center">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque aliquid adipisci aspernatur. Soluta quisquam dignissimos earum quasi voluptate. Amet, dignissimos, tenetur vitae dolor quam iusto assumenda hic reprehenderit?</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 highlight">
          <div class="h-caption"><h4><i class="fa fa-share-alt fa-5" style="color:DodgerBlue"></i>Share it with friends</h4></div>
          <div class="h-body text-center">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, commodi, sequi quis ad fugit omnis cumque a libero error nesciunt molestiae repellat quos perferendis numquam quibusdam rerum repellendus laboriosam reprehenderit! </p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 highlight">
          <div class="h-caption"><h4><i class="fa fa-group fa-5" style="color:tomato"></i>Groups</h4></div>
          <div class="h-body text-center">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, vitae, perferendis, perspiciatis nobis voluptate quod illum soluta minima ipsam ratione quia numquam eveniet eum reprehenderit dolorem dicta nesciunt corporis?</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 highlight">
          <div class="h-caption"><h4><i class="fa fa-lock fa-5"></i>Privacy aware</h4></div>
          <div class="h-body text-center">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, excepturi, maiores, dolorem quasi reprehenderit illo accusamus nulla minima repudiandae quas ducimus reiciendis odio sequi atque temporibus facere corporis eos expedita? </p>
          </div>
        </div>
      </div> <!-- /row  -->
    
    </div>
  </div>

        <hr>

      <footer>
        <p>&copy; Cloud 2014</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>  
    <script src="Progressus/assets/js/headroom.min.js"></script>
    <script src="Progressus/assets/js/jQuery.headroom.min.js"></script>
    <script src="Progressus/assets/js/template.js"></script>
  </body>
</html>
