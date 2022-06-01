   <?php
    $connect = mysqli_connect("localhost", "highalln_iot_project", "Hihomhinh99", "highalln_iot_project");  
    if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
    } else{
        echo "Connect successful";
    }
    
    // $mode = test_input($_POST['Mode']);
    // $insertdata = "INSERT INTO control1(nhiet_do) VALUES ('$mode')";
    // mysqli_query($connect, $insertdata);

  
    $jsonString = file_get_contents("control1/control1.json");
    $data = json_decode($jsonString, true);

    $user='abcd_ef';
    
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	$type = test_input($_POST["Type"]);
        switch($type){
            case "ND":
                $data['Nhiet do'] = test_input($_POST["Mode_Temp"]);
                break;
            case "DA":
                $data['Do am'] = test_input($_POST["Mode_Humi"]);
                break;
        }
	}
	
    $a = $data['Nhiet do'];
    $b = $data['Do am'];
    $insertdata = "INSERT INTO control1(nhiet_do, do_am) VALUES ('$a','$b')";
	mysqli_query($connect, $insertdata);
	
	$newJsonString = json_encode($data);
	file_put_contents("control1/control1.json", $newJsonString);
	
 function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
   ?>  