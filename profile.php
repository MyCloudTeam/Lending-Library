<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
// include_once 'includes/nicetime.php';

sec_session_start();
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
    <link rel="stylesheet" href="Progressus/assets/css/main-int.css">
    
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body onload="initialize()">
    <?php if (login_check($mysqli) == true) : ?>

      <?php if ($_GET['user']==$_SESSION['user_id']): ?>
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
                        <li><a href="profile.php">Profile</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">More Pages <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="sidebar-left.html">Left Sidebar</a></li>
                                <li class="active"><a href="sidebar-right.html">Right Sidebar</a></li>
                            </ul>
                        </li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a class="btn" href="includes/logout.php">LOG OUT</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div> 
        <!-- /.navbar -->


        <!-- <h3> The user is trying to access his own profile </h3> -->

      <header id="head">
        <div class="col-md-3 col-md-offset-1">
          <img class="featurette-image img-responsive img-circle" src="http://jobswolf.com/img/testimonials/2/profile.jpg" width="200" height="200" alt="Generic placeholder image">
        </div>
      </header>

      <div>
        <p class="lead">
          USER NAME<br/>
          UNIVERSITY<br/>
          Works at XYZ corp.
        </p>
        <!-- <span class="text-muted">More >> </span>&nbsp;<a href="">Friends</a> , &nbsp;<a href="activities.php">Activities</a> -->
      </div>

      <div class="panel well panel-default col-md-8 col-md-offset-2" id="addpost">
        <div class="panel-body container-fluid">
          <form id="postform" enctype="multipart/form-data" action="includes/addpost.php" method="post">
            <input type="text" name="title" class="form-control" placeholder="Title">
            <textarea name="textcontent" class="form-control" rows="3" placeholder="Post Content"></textarea>
            <div class="btn-group">
              <button type="button" class="btn btn-success" onclick='$("#activitydiv").show()'>Activity</button>
              <button type="button" class="btn btn-default" onclick='$("#locationdiv").show()'>Location</button>
              <button type="button" class="btn btn-info" onclick='$("#multimediadiv").show()'>Upload</button>
              <button type="button" class="btn btn-default" onclick='$("#privacy").show()'>Privacy</button>
            </div>
              <button type="submit" class="pull-right btn btn-primary">Post</button>

            <br><br>
              <!-- <input type="text" class="form-control" placeholder="Location"> -->
              <!-- <input type="text" class="form-control" placeholder="Activity"> -->
            <div id="activitydiv" class="input-group" style="display: none;">
              <span class="input-group-addon">Activity</span>
              <input id="activityname"  name="activityname" type="text" class="form-control" placeholder="Type Activity name">
              <br>
            </div>

            <div id="locationdiv" class="input-group" style="display: none;">
              <span class="input-group-addon">Location</span>
              <!-- <input id="autocomplete" onFocus="geolocate()" type="text" class="form-control" placeholder="Type Location name"> -->
              <!-- <?php include_once "includes/gapi.php";?> -->
            </div>

            <div id="multimediadiv" style="display: none;">
              <input type="hidden" name="MAX_FILE_SIZE" value="99999999" />
              <div><input name="userfile" type="file" /></div>
            </div>

            <div id="privacy" style="display: none;">
              <input type='radio' name='privacy' value='public' checked>Public &nbsp;
              <input type='radio' name='privacy' value='friends'>Friends &nbsp;
              <input type='radio' name='privacy' value='fof'>Friend of friends
            </div>

          </form>
        </div>
      </div>      

        <?php else: ?>  
        <div class="container-fluid row navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php">Wildbook</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="profile.php?user=<?php echo htmlentities($_SESSION['user_id']); ?>"><?php echo htmlentities($_SESSION['user_id']); ?></a></li>
                <li class="pull-right"><a href="includes/logout.php" style="align:right;">LogOut</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div>
        <br><br>

        <!-- <h3> Trying to access someone else's profile </h3> -->

        <?php include "includes/otherfeature.php"; ?>

      <div class="container-fluid row page-header panel well panel-default col-md-8 col-md-offset-2" id="addpost">
        <div class="panel-body container-fluid">
          <form enctype="multipart/form-data" id="postform" action="includes/addpost.php" method="post">
            <input type="hidden" name="recipient" value="<?php echo $_GET['user'];?>">
            <input type="text" name="title" class="form-control" placeholder="Title">
            <textarea name="textcontent" class="form-control" rows="3" placeholder="Post Content"></textarea>
            <div class="btn-group">
              <button type="button" class="btn btn-success" onclick='$("#activitydiv").show()'>Activity</button>
              <button type="button" class="btn btn-default" onclick='$("#locationdiv").show()'>Location</button>
              <button type="button" class="btn btn-info" onclick='$("#multimediadiv").show()'>Upload</button>
              <button type="button" class="btn btn-default" onclick='$("#privacy").show()'>Privacy</button>
            </div>
              <button type="submit" class="pull-right btn btn-primary">Post</button>

            <br><br>
              <!-- <input type="text" class="form-control" placeholder="Location"> -->
              <!-- <input type="text" class="form-control" placeholder="Activity"> -->
            <div id="activitydiv" class="input-group" style="display: none;">
              <span class="input-group-addon">Activity</span>
              <input id="activityname"  name="activityname" type="text" class="form-control" placeholder="Type Activity name">
              <br>
            </div>

            <div id="locationdiv" class="input-group" style="display: none;">
              <span class="input-group-addon">Location</span>
              <!-- <input id="autocomplete" onFocus="geolocate()" type="text" class="form-control" placeholder="Type Location name"> -->
              <?php include_once "includes/gapi.php";?>
            </div>

            <div id="multimediadiv" style="display: none;">
              <input type="hidden" name="MAX_FILE_SIZE" value="99999999" />
              <div><input name="userfile" type="file" /></div>
            </div>

            <div id="privacy" style="display: none;">
              <input type='radio' name='privacy' value='public' checked>Public &nbsp;
              <input type='radio' name='privacy' value='friends'>Friends &nbsp;
              <input type='radio' name='privacy' value='fof'>Friend of friends
            </div>
            
          </form>
        </div>
      </div>
    
        <div id="posts" class="container col-md-8 col-md-offset-2">
            <?php
            $friendship = 'friend';
              if($friendship=='friend'){
                $result = mysqli_query($mysqli,"SELECT * FROM posts WHERE (user_name='".$_GET['user']."' OR recipient='".$_GET['user']."') AND (privacyid='friends' or privacyid='fof' or privacyid='public') ORDER BY posttime DESC LIMIT 10");
              }

              // if($friendship=='fof'){
              //   $result = mysqli_query($mysqli,"SELECT * FROM posts WHERE (user_name='".$_GET['user']."' OR recipient='".$_GET['user']."') AND (privacyid='fof' or privacyid='public') ORDER BY posttime DESC LIMIT 10");
              // }

              elseif($friendship=='none' or $friendship=='pending' or $friendship=='withheld'){
                $result = mysqli_query($mysqli,"SELECT * FROM posts WHERE (user_name='".$_GET['user']."' OR recipient='".$_GET['user']."') AND privacyid='public' ORDER BY posttime DESC LIMIT 10");
              }
      
          $postexist=mysqli_num_rows($result);
          $_GET['user'];

          if($postexist){

              while($row = mysqli_fetch_array($result)) {

                $row['user_name'];

                include "includes/posts.php";
              }
          }
          else{
            echo "<center class='lead'>No posts to show</center>";
          }
            ?>
        </div>
        <?php endif; ?>
      </div>

      <?php else : ?>
                  <script>
                      window.alert("You are not authorized to access this page.\n\n\t\t\tPlease LogIn");
                      window.location.href='index.php';
                  </script>
              <?php endif; ?>
  </body>

</html>