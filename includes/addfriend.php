<?php

require_once("db_connect.php");
include_once 'functions.php';

sec_session_start();

if(isset($_POST['friend'])){
	$friendquery = mysqli_query($mysqli,"INSERT INTO friends (user_name,friendsusername) VALUES ('".$_SESSION['user_name']."','".$_POST['friend']."')");
	if($friendquery) echo "friend request success";
	else printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
}

if(isset($_POST['addfriend'])){
	$addquery = mysqli_query($mysqli,"UPDATE friends SET accept='1' WHERE user_name='".$_POST['sender']."' AND friendsusername='".$_SESSION['user_name']."'");
	if($friendquery) echo "friend add success";
	else printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
}

if(isset($_POST['delfriend'])){
	$addquery = mysqli_query($mysqli,"DELETE FROM friends WHERE (user_name='".$_POST['friendnow']."' AND friendsusername='".$_SESSION['user_name']."') OR (friendsusername='".$_POST['friendnow']."' AND user_name='".$_SESSION['user_name']."')");
	if($friendquery) echo "friend delete success";
	else printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
}


header('Location: ' . $_SERVER['HTTP_REFERER']);

?>