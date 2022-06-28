<?php   
$connect = mysqli_connect("localhost", "highalln_iot_project", "Hihomhinh99", "highalln_iot_project");  
$sql = "SELECT * FROM info1";
$sql1 = "SELECT * FROM location1"; 

$result = mysqli_query($connect, $sql);
$result1 = mysqli_query($connect, $sql1);

$json_array = array();

while($row = mysqli_fetch_array($result)){
    $id = $row['id'];
    $ten = $row['ten_tai_xe'];
    $dien_thoai = $row['dien_thoai'];
    $xuat_phat = $row['xuat_phat'];
    $dich_den = $row['dich_den'];
    $thoi_gian = $row['thoi_gian'];
    $tin_nhan = $row['tin_nhan'];
	$json_array[] = array(
	    'ID'=> $id,
		'Ten'=> $ten, 
		'Dien Thoai'=> $dien_thoai,
		'Xuat phat' => $xuat_phat,
		'Dich den' => $dich_den,
		'Thoi gian' => $thoi_gian,
		'Tin nhan' => $tin_nhan
	);
}

while($row = mysqli_fetch_array($result1)){
    $id = $row['id'];
    $kinh_do = $row['kinh_do'];
    $vi_do = $row['vi_do'];
    $thoi_gian_doc = $row['created_at'];
	$json_array[] = array(
		'ID'=> $id,
		'Kinh do'=> $kinh_do, 
		'Vi do'=> $vi_do,
		'Thoi gian doc' => $thoi_gian_doc
	);
}

echo json_encode($json_array)
?>
