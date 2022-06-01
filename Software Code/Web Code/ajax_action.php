<?php
	
	$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
$connect = @mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
		
		if(isset($_GET['ten_tai_xe'])){
			$ten_tai_xe = $_GET['ten_tai_xe'];
			$xuat_phat = $_GET['xuat_phat'];
			$dich_den = $_GET['dich_den'];
			$thoi_gian = $_GET['thoi_gian'];
			$ket_thuc = $_GET['ket_thuc'];
			$dien_thoai = $_GET['dien_thoai'];
			$tin_nhan = $_GET['tin_nhan'];
		$result = mysqli_query($connect,"INSERT INTO info1 (ten_tai_xe,xuat_phat,dich_den,thoi_gian,dien_thoai,tin_nhan) VALUES ('$ten_tai_xe','$xuat_phat','$dich_den','$thoi_gian','$dien_thoai','$tin_nhan')");
		}
	
	?>