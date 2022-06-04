<?php include('ab22.php');?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>He Thong Dieu Khien</title>
    <link rel = "stylesheet" type="text/css" href="style.css"/>
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous">    
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body  align="center">
 		<br />
		<header> 
       
        <br />
         
   	 
      </h2>
      </header>
    <script>
     var res = "success";
    </script>
   <?php
    //     $jsonString = file_get_contents("app/control1/control1.json");
    //     $data = json_decode($jsonString, true); //take a json encode string and convert it into a PHP variable
	
    //     $user='abcd_ef';
    //     $Mode='abcd_ef';
    // 	if(isset($_GET['LED']))
    // 	{
    		
    // 	}
    //     $data['Mode'] = "$nhiet_do"; 
    // 	$data['Mode1'] = "$do_am"; 
                                 
    // 	$newJsonString = json_encode($data);
    // 	file_put_contents("app/control1/control1.json", $newJsonString);
   ?>       


 

        <form method="GET" id="data1" class="form-group">
          
            <label>ON/OFF </label>
          <!--   <div id='on' class='btn-group btn-group-toggle' data-toggle='buttons'> -->
            <label class='btn btn-secondary'>
              <input  type='checkbox' name='LED' value='ON' id='option1' > ON </input>
            </label>
            <label class='btn btn-secondary'>
              <input type='checkbox' name='LED' value='OFF' id='option2' > OFF </input>
            </label>
          </div>
          <div class="form-group">
            <label>CHỌN NHIỆT ĐỘ</label>
            <input type="text" class="form-control" placeholder="nhập nhiệt độ cài đặt" id="tem1">
          </div>
          
          <input type="button" name="insert" value="XONG" class="btn btn-primery" id="click_button">
        </form>
        <br>
        <br>
        <form>
          <!--   <div id='on' class='btn-group btn-group-toggle' data-toggle='buttons'> -->
            <label class='btn btn-secondary'>
              <input  type='checkbox' name='LED1' value='ON' id='option1' > ON </input>
            </label>
            <label class='btn btn-secondary'>
              <input type='checkbox' name='LED1' value='OFF' id='option2' > OFF </input>
            </label>
          </div>
          <div class="form-group">
            <label>CHỌN ĐỘ ẨM</label>
            <input type="text" class="form-control" placeholder="nhập độ ẩm cài đặt" id="tem2">
          </div>
                 
          <input type="button" name="insert" value="XONG" class="btn btn-primery" id="click_button1">
        </form>
          <br>

 <?php
   $jsonString = file_get_contents("app/control2/control2.json");
	$data = json_decode($jsonString, true);
 
 // if ($data['led1'] == 'on')
 // {
	
 //   	echo "  <script> document.getElementById('myImage1').src = 'pic_bulbon10.png' </script>";  	
 // }
 
 
 ?>
 

    <script type="text/javascript">
      $(() => {
        function fetch_data(){
      
        }
        fetch_data();
        $('#click_button').on('click',function(){
          var tem1 = $('#tem1').val();
          var btnValue = $("input[name='LED']:checked").val();
          const sendData = {};
          sendData.data = btnValue != null ? btnValue : tem1;
          if(sendData.data == "")
          {
              return;
          }

          $.ajax({
           url:"ajax_action_auto2.php",
           method:"GET",
           data:sendData,
           success:function(data){
             alert('GỬI DỮ LIỆU THÀNH CÔNG');
             fetch_data();
             window.location.reload();
           }
          });
        });

        $("input[name = 'LED']").on("click", function(){
          $("input[name = 'LED']").not(this).each((index, elem) => $(elem).prop("checked", false));
          if($(this).is(":checked")){
            $("#tem1").prop("disabled", true);
          }else{
            $("#tem1").prop("disabled", false);           
          }

          $('#click_button').trigger("click");
        })
      });

  </script>
  
  
  <script type="text/javascript">
      $(() => {
        function fetch_data(){
      
        }
        fetch_data();
        $('#click_button1').on('click',function(){
          var tem2 = $('#tem2').val();
          var btnValue1 = $("input[name='LED1']:checked").val();
          const sendData1 = {};
          sendData1.data1 = btnValue1 != null ? btnValue1 : tem2;
          if(sendData1.data1 == "")
          {
              return;
          }

          $.ajax({
           url:"ajax_do_am2.php",
           method:"GET",
           data:sendData1,
           success:function(data){
             alert('GỬI DỮ LIỆU THÀNH CÔNG');
             fetch_data();
             window.location.reload();
           }
          });
        });

        $("input[name = 'LED1']").on("click", function(){
          $("input[name = 'LED1']").not(this).each((index, elem) => $(elem).prop("checked", false));
          if($(this).is(":checked")){
            $("#tem2").prop("disabled", true);
          }else{
            $("#tem2").prop("disabled", false);           
          }

          $('#click_button1').trigger("click");
        })
      });

  </script>


  


   