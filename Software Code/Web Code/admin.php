<html>
<head>
	<title>ADMIN</title>
	 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" 
  type="text/css" href="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
  
  <script type="text/javascript" src="./jquery/index.js"></script>
  <script
  		src="https://code.jquery.com/jquery-3.6.0.min.js"
  		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  		crossorigin="anonymous">		
  		</script>
		</head>
	<body>
		
  
  <br>
  <br>
		
			<div class="col-md-12"></div>
				<h3>QUẢN LÝ DỮ LIỆU XE SỐ 1</h3>
				<form method="GET" id="data1">
					<label>TÊN TÀI SẾ</label>
					<input type="text" class="form-control" placeholder="nhập tên tài sễ" id="ten_tai_xe">
					<br>
					<label>XUẤT PHÁT</label>
					<input type="text" class="form-control" placeholder="vị trí xuất phát" id="xuat_phat">
					<br>
					<label>ĐÍCH ĐẾN</label>
					<input type="text" class="form-control" placeholder="đích đến" id='dich_den'>
					<br>
					<label>THỜI GIAN KHỞI HÀNH </label>
					<input type="text" class="form-control" placeholder="thời gian khởi hành" id='thoi_gian'>
					<br>
					<label>SỐ ĐIỆN THOẠI TÀI SẾ</label>
					<input type="text" class="form-control" placeholder="số điện thoại tài sế" id='dien_thoai'>
					<br>
					<label>TIN NHẮN TỪ ADMIN</label>
					<input type="text" class="form-control" placeholder="gửi tin nhắn" id='tin_nhan'>
					
					
					<br>
					
					<input type="button" name="insert" value="XONG" class="btn btn-primery" id="click_button">
				</form>
				
		
		<script type="text/javascript">
			$(document).ready(function(){
				function fetch_data(){
			/*	$.ajax({
					url:"ajax_action.php",
					method:"GET",
					success:function(data){
						
						$('#load_data').html(data);
						fetch_data();
					}
				
				
				});*/
			}
			fetch_data();
			$('#click_button').on('click',function(){
				var ten_tai_xe = $('#ten_tai_xe').val();
				var xuat_phat = $('#xuat_phat').val();
				var dich_den = $('#dich_den').val();
			
				var thoi_gian = $('#thoi_gian').val();
				
				var dien_thoai = $('#dien_thoai').val();
				var tin_nhan = $('#tin_nhan').val();
				$.ajax({
					url:"ajax_action.php",
					method:"GET",
					data:{ten_tai_xe:ten_tai_xe,xuat_phat:xuat_phat,dich_den:dich_den,thoi_gian:thoi_gian,dien_thoai:dien_thoai,tin_nhan:tin_nhan,},
					success:function(data){
						alert('GỬI DỮ LIỆU THÀNH CÔNG');
						$('#data1')[0].reset();
						fetch_data();
					}
				});

			});
		});
		</script>

	
	</body>
</html>