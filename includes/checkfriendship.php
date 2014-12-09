<?php
// include_once 'db_connect.php';

// $_SESSION['user_name']="kaushik";
// $_GET['user']="santosh";

$fcheckquery = mysqli_query($mysqli,"SELECT DISTINCT user_name,friendsusername,accept FROM friends WHERE friendsusername='".$_SESSION['user_name']."' AND user_name='".$_GET['user']."'
  UNION
  SELECT DISTINCT user_name,friendsusername,accept FROM friends WHERE user_name='".$_SESSION['user_name']."' AND friendsusername='".$_GET['user']."'");

$fcheck=mysqli_num_rows($fcheckquery);
$friendship="none";

if($fcheck){
	while($fcrow=mysqli_fetch_array($fcheckquery)){
		if($fcrow['accept']==0){
			if($_SESSION['user_name']==$fcrow['user_name']){
				$friendship="pending";
			}
			if($_SESSION['user_name']==$fcrow['friendsusername']){
				$friendship="withheld";
			}
		}
		else $friendship="friend";
	}
}


$fofquery='SELECT friendsusername "fof"
FROM friends
WHERE user_name
IN (
SELECT friendsusername
FROM friends
WHERE user_name = "'.$_SESSION["user_name"].'"
AND accept =1
UNION SELECT user_name
FROM friends
WHERE friendsusername = "'.$_SESSION["user_name"].'"
AND accept =1
)
AND accept =1
UNION SELECT user_name "fof"
FROM friends
WHERE friendsusername
IN (
SELECT friendsusername
FROM friends
WHERE user_name = "'.$_SESSION["user_name"].'"
AND accept =1
UNION SELECT user_name
FROM friends
WHERE friendsusername = "'.$_SESSION["user_name"].'"
AND accept =1
)
AND accept =1';
$fofresult=mysqli_query($mysqli,$fofquery);
$fofexist=mysqli_num_rows($fofresult);
if($fofexist){
	while($fofrow=mysqli_fetch_array($fofresult)){
		if($fofrow['fof']==$_GET['user']){
			$friendship='fof';
		}
	}
}



?>