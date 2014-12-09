<?php

	require_once 'db_connect.php';
	include_once 'functions.php';

	sec_session_start();

	function check_friendship($mysqli, $cur_user_id, $other_user_id){
		$fcheckquery = mysqli_query($mysqli,"SELECT DISTINCT from_user_id,to_user_id,accept FROM friends WHERE to_user_id='".$cur_user_id."' AND from_user_id='".$other_user_id."'
											  UNION
											  SELECT DISTINCT from_user_id,to_user_id,accept FROM friends WHERE from_user_id='".$cur_user_id."' AND to_user_id='".$other_user_id."'");

		$fcheck=mysqli_num_rows($fcheckquery);
		$friendship="none";

		if($fcheck){
			while($fcrow=mysqli_fetch_array($fcheckquery)){
				if($fcrow['accept']==0){
					if($_SESSION['user_id']==$fcrow['from_user_id']){
						return $friendship="pending";
					}
					if($_SESSION['user_id']==$fcrow['to_user_id']){
						return $friendship="withheld";
					}
				}
				else
					return $friendship="friend";
			}
		}
		else
			return FALSE;
	}

	function send_friend_request($mysqli, $other_user_id){
		$friendquery = mysqli_query($mysqli,"INSERT INTO friends (from_user_id,to_user_id) VALUES ('".$_SESSION['user_id']."','".$other_user_id."')");
		if($friendquery){
			// echo "friend request success";
			echo json_encode(array('success'=>1));
		}
		else{
			// printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
			echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$mysqli->error));
		}
	}

	function accept_friend_request($mysqli, $other_user_id){
		$addquery = mysqli_query($mysqli,"UPDATE friends SET accept='1' WHERE from_user_id='".$other_user_id."' AND to_user_id='".$_SESSION['user_id']."'");
		if($friendquery){
			// echo "friend add success";
			echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$mysqli->error));
		}
		else{
			// printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
			echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$mysqli->error));
		}
	}

	function delete_friend($mysqli, $other_user_id){
		$addquery = mysqli_query($mysqli,"DELETE FROM friends WHERE (from_user_id='".$other_user_id."' AND to_user_id='".$_SESSION['user_id']."') OR (to_user_id='".$_POST['friendnow']."' AND user_id='".$_SESSION['user_id']."')");
		if($friendquery){
			// echo "friend delete success";
			echo json_encode(array('success'=>1));
		}
		else{
			// printf("Mazaak: %s\n", $mysqli->error); //MySQL Error
			echo json_encode(array('success'=>0, 'error_id'=>3, 'error_msg'=>$mysqli->error));
		}
	}

?>