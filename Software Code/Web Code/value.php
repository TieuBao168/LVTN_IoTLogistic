<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		
		if(isset($_POST['value'])){
			$value = $_POST['value'];			
		$result = mysqli_query($connect,"INSERT INTO value (value) VALUES ('$value')");
		}
	
	?>