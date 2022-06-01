<?php
  $con = mysqli_connect("localhost", "highalln_iot_project", "Hihomhinh99", "highalln_iot_project");
  
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart2);

      


    </script>
  
  </head>
  <body>
    
    <div id="linechart1" style="width: 500px; height: 500px;float: right;"></div>
    <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Three charts -->
                <!-- ============================================================== -->
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Nhiệt Độ</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash"><canvas width="67" height="30"
                                            style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>
                                <li class="ms-auto">
                                  <span class="counter text-success">
                                    <?php include('a.php');?>
                    <h3> <?php echo $temperature; ?></h3>
                  </span>
                </li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Độ Ẩm</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash2"><canvas width="67" height="30"
                                            style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>
                                <li class="ms-auto"><span class="counter text-purple"><h3> <?php echo $humidity; ?></h3></span></li>
                            </ul>
                            
                        </div>

                    </div>
                   
                </div>   
                  <div class="mx-auto" style=" border: 10px #05a38b;width: 1500px;">
              <iframe src="chart.php" scrolling="no" style="border:none; width: 1000px; height: 1000px;  overflow: hidden;"></iframe>

          </div>  
  
    </div>
  </body>
</html>
