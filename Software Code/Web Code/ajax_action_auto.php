
<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		
		if(isset($_GET['data'])){
			$tem1 = $_GET['data'];
		$result = mysqli_query($connect,"INSERT INTO control1 (nhiet_do) VALUES ('$tem1')");


        $jsonString = file_get_contents("app/control1/control1.json");
        $data = json_decode($jsonString, true); //take a json encode string and convert it into a PHP variable
	
        $data['Nhiet do'] = $tem1; 
                                 
    	$newJsonString = json_encode($data);
    	file_put_contents("app/control1/control1.json", $newJsonString);
		}
	
	?>