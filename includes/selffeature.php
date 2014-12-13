<?php
// include_once '/includes/db_connect.php';
include "age.php";

$proquery = "select * from users where user_id='".$_GET['user']."'";
$proresult = mysqli_query($mysqli,$proquery);
$proexist = mysqli_num_rows($proresult);

			if($proexist){
				$i=1;
				while($prow = mysqli_fetch_array($proresult)){
					if($i){

?>

      <div class="row featurette well">
        <div class="col-md-3 col-md-offset-1">
          <img class="featurette-image img-responsive img-circle" src="http://upload.wikimedia.org/wikipedia/commons/d/d3/User_Circle.png" width="200" height="200" alt="Generic placeholder image">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">USER NAME</h2>
          <p class="lead"><?php echo $prow['education'] ?><br>
          Works at XYZ corp.
        </p>
        <!-- <span class="text-muted">More >> </span>&nbsp;<a href="">Friends</a> , &nbsp;<a href="activities.php">Activities</a> -->
        </div>
      </div>

<?php
						$i=0;
					}

				}
			}
?>