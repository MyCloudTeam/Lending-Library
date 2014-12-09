<?php

	// include_once 'psl-config.php';   // As functions.php is not included


$dbhost = 'first.c1hi3phu09kt.us-east-1.rds.amazonaws.com';
$dbport = '3306';

$dsn = "mysql:host={$dbhost};port={$dbport}";
$username = 'krishna';
$password = 'frontline';
// $conn = new PDO($dsn, $username, $password);
// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo "database created successfully";



	$mysqli = new mysqli($dbhost, $username, $password, "cloudlib", $dbport);