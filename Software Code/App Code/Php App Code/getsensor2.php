<?php   
$connect = mysqli_connect("localhost", "highalln_iot_project", "Hihomhinh99", "highalln_iot_project");  
$sql = "SELECT * FROM data2"; 
$result = mysqli_query($connect, $sql);
$json_array = array();

while($row = mysqli_fetch_array($result)){
    $id = $row['id'];
    $nhiet_do = $row['nhiet_do'];
    $do_am = $row['do_am'];
    $nhiet_do1 = $row['nhiet_do1'];
    $do_am1 = $row['do_am1'];
    $nhiet_do2 = $row['nhiet_do2'];
    $do_am2 = $row['do_am2'];
    $nhiet_do3 = $row['nhiet_do3'];
    $do_am3 = $row['do_am3'];
    $nhiet_do4 = $row['nhiet_do4'];
    $do_am4 = $row['do_am4'];
    $canh_bao = $row['canh_bao'];
    $thoi_gian_doc = $row['created_at'];
	$json_array[] = array(
		'ID'=> $id,
		'Nhiet do' => $nhiet_do,
		'Do am' => $do_am,
		'Nhiet do 1' => $nhiet_do1,
		'Do am 1' => $do_am1,
		'Nhiet do 2' => $nhiet_do2,
		'Do am 2' => $do_am2,
		'Nhiet do 3' => $nhiet_do3,
		'Do am 3' => $do_am3,
		'Nhiet do 4' => $nhiet_do4,
		'Do am 4' => $do_am4,
		'Canh bao' => $canh_bao,
		'Thoi gian doc' => $thoi_gian_doc
	);
}
echo json_encode($json_array)
?>
