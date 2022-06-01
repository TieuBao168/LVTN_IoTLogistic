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
				
				<form method="GET" id="data1">
					<label>CHON NHIET DO</label>
					<input type="text" class="form-control" placeholder="nhập tên tài sễ" id="tem1">
					<br>				
					<input type="button" name="insert" value="XONG" class="btn btn-primery" id="click_button">
				</form>
				
		
		<script type="text/javascript">
			$(document).ready(function(){
				function fetch_data(){
			
			}
			fetch_data();
			$('#click_button').on('click',function(){
				var tem1 = $('#tem1').val();

				var iframe = document.getElementById('espControl');
				var innerDoc = iframe.contentDocument || iframe.contentWindow.document;

				var btnValue = innerDoc.querySelector("input[name='LED]:checked");
				console.log(btnValue);

				// $.ajax({
				// 	url:"ajax_action_auto.php",
				// 	method:"GET",
				// 	data:{tem1:tem1,},
				// 	success:function(data){
				// 		alert('GỬI DỮ LIỆU THÀNH CÔNG');
				// 		$('#data1')[0].reset();
				// 		fetch_data();
				// 	}
				// });

			});
		});
		</script>

	
	</body>
</html>