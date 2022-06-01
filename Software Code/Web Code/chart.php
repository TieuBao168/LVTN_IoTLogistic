<?php
  $con = mysqli_connect("localhost", "highalln_iot_project", "Hihomhinh99", "highalln_iot_project");
  
?>
<html>
  <head>
    <meta http-equiv="refresh" content="10">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart2);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['temperature1', 'Temperature'],
         <?php
         $sql = "SELECT * FROM data1";
         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['id']."',".$result['nhiet_do']."],";
          }

         ?>
        ]);

        var options = {
          title: 'Nhiệt độ'
        };

        var chart = new google.visualization.LineChart(document.getElementById('linechart'));

        chart.draw(data, options);
      }

       function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['temperature', 'Humidity'],
         <?php
         $sql = "SELECT * FROM data1";
         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['id']."',".$result['do_am']."],";
          }

         ?>
        ]);

        var options = {
          title: 'Độ ẩm'
        };
        var chart = new google.visualization.LineChart(document.getElementById('linechart1'));
        chart.draw(data, options);
      }


    </script>


    </script>
  
  </head>
  <body>
    <div id="linechart" style="width: 100%;height: 50%; "></div>
    <div id="linechart1" style="width: 100%;height: 50%; "></div>
  </body>
</html>
