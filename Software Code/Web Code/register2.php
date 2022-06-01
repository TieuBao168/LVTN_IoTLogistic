<?php
 $con = mysqli_connect("localhost","highalln_iot_project","Hihomhinh99","highalln_iot_project");
$a = $_POST['name'];
$b = $_POST['password'];
$sql = "select * from login2 where name= '$a' and password = '$b'"; 
$rs = mysqli_query($con,$sql);

if (mysqli_num_rows($rs) > 0){
	header("Location: information2.php");
}else{
	require_once('index.php');
	echo '<script language="javascript">';
	echo 'alert("sai tài khoản hoặc mật khẩu")';
	echo '</script>';
}
?>