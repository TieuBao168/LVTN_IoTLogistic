<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		
		if(isset($_GET['tem1'])){
			$tem1 = $_GET['tem1'];
			
		$result = mysqli_query($connect,"INSERT INTO control2 (nhiet_do) VALUES ('$tem1')");
		}
	
	?>