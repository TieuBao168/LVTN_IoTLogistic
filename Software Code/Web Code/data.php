
<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
	try{
		$dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ex){
		die($ex->getMessage());
	}
	$stmt=$dbcon->prepare("SELECT * FROM data1");
	$stmt->execute();
	$json = [];
			while($row = $stmt-> fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
				
				$json[]=[(float)$id, (float)$nhiet_do];
				
			}
		echo json_encode($json);
	?>
