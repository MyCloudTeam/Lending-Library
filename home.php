<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
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
        <?php if (login_check($mysqli) == true) : ?>
            <!-- Fixed navbar -->
            <div class="navbar navbar-inverse navbar-fixed-top headroom" >
                <div class="container">
                    <div class="navbar-header">
                        <!-- Button for smallest screens -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                        <a class="navbar-brand" href="index.php">Cloud Pages</a>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav pull-right">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="about.html">Profile</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">More Pages <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="sidebar-left.html">Left Sidebar</a></li>
                                    <li class="active"><a href="sidebar-right.html">Right Sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Contact</a></li>
                            <li><a class="btn" href="signin.html">LOG OUT</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div> 
            <!-- /.navbar -->

                <!-- Main jumbotron for a primary marketing message or call to action -->
                <header id="head">
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
    
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>