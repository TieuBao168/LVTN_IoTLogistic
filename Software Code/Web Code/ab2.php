<?php
	
		$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
		$dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt=$dbcon->prepare("SELECT * FROM info2");
	$stmt->execute();
	$json = [];
			while($row = $stmt-> fetch(PDO::FETCH_ASSOC))
			{
				extract($row);		
			}
			
	?>