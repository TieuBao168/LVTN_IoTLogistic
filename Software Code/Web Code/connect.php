<?php


	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';

$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

date_default_timezone_set('Asia/Ho_Chi_Minh');
$timest = date('Y-m-d H:i:s'); 

// echo "Connection Success!<br><br>";
$nhiet_do = isset($_GET['nhiet_do']) ? $_GET['nhiet_do'] : '';
$do_am = isset($_GET['do_am']) ? $_GET['do_am'] : '';
// $anhsang = "10";
$query = "INSERT INTO iot_project (nhiet_do, do_am, created_at) VALUES ('$nhiet_do', '$do_am', '$timest')";
$result = mysqli_query($connect,$query);

// echo "Insertion Success!<br>";

?>