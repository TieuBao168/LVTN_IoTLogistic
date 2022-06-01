<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		
		if(isset($_GET['data1'])){
		    if(data!=""){
		        $tem2 = $_GET['data1'];
		        $result = mysqli_query($connect,"INSERT INTO control1 (do_am) VALUES ('$tem2')");
		        
                $jsonString = file_get_contents("app/control1/control1.json");
                $data = json_decode($jsonString, true); //take a json encode string and convert it into a PHP variable
        	
                $data['Do am'] = $tem2; 
                                         
            	$newJsonString = json_encode($data);
            	file_put_contents("app/control1/control1.json", $newJsonString);
		    }
			
        
		}
	
	?>