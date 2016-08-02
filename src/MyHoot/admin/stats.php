<?php
require '../controller/dbsettings.php';
$sql = "DELETE FROM `games` WHERE `numOfUsers`<2";
$result = $conn->query($sql);

?>
<html>
 <head>
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
     google.charts.load('current', {'packages':['corechart']});
     google.charts.setOnLoadCallback(drawChart);

     function drawChart() {
       var jsonData = $.ajax({
          url: "statsDATA.php",
          dataType:"json",
          async: false
          }).responseText;


        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

       var options = {
         title: 'Users',
         curveType: 'function',
         legend: { position: 'bottom' }
       };

       var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

       chart.draw(data, options);
     }
   </script>
 </head>
 <body>
   <div id="curve_chart" style="width: 900px; height: 500px"></div>
   The total number of questions is:
   <?php
$sql="SELECT (SELECT count(`id`) FROM `data-geo`) + (SELECT count(`id`) FROM `data-people`)+ (SELECT count(`id`) FROM `data-rand`)+ (SELECT count(`id`) FROM `data-time`) AS total";
$result = $conn->query($sql);
//die($sql);
if ($result)
{
    $row = $result->fetch_assoc();
    echo $row ['total'];
  }
    ?>
 </body>
</html>
