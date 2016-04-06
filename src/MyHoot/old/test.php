<?php

$pusherIP='172.24.18.10';
$pusherIP='192.168.0.106';
$pusherIP=getHostByName(getHostName());
//$pusherIP='52.90.77.67';
$servername = "localhost";
$username = "root";
$password = "myhoot";
$dbname = "MyHoot";
//mysqladmin -u root -p'MyHoot' password myhoot



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
$conn->query("SET collation_connection = utf8mb4_unicode_ci");
header('Content-Type: text/html; charset=UTF-8');
//SET CHARACTER SET 'utf8mb4'
//2016-03-29T10:21:06.157321Z  983 Query	SET collation_connection = 'utf8mb4_unicode_ci'

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');
	$result = $conn->query("INSERT INTO `MyHoot`.`users` (`user_id`, `game_id`, `name`, `round`, `score`, `color`) VALUES ('52', '52', '😀', NULL, NULL, '')
");
	die ("s".$conn->error . " ". $sql);
?>
