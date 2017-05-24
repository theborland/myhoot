<?php
//die (print $target);
$count=500;//$target;
$height1=rand(30,1000);
$height2=rand(30,1000);
while (($height1<$height2*2 && $height2<$height1*2) || $height1<$height2/8 || $height2<$height1/8){
  $height1=rand(1,1000);
  $height2=rand(1,1000);
}
$scaled=$height1*$count/$height2;

 ?>
<html>
  <head>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
   google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["<?php echo round($scaled) ?>", <?php echo $scaled ?>, "red"],
        ["", <?php echo $count ?>, "yellow"]

      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 0   ,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {

        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },

        hAxis: {
        textPosition: 'none'
      },
        vAxis: {
          baseline:5,
        textPosition: 'none'
        }


      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));

      jQuery.post("action_save64png.php", {pngImageData :chart.getImageURI(), CourseID: 23, charttype: 'incomesplit' });

      // Wait for the chart to finish drawing before calling the getImageURI() method.
      google.visualization.events.addListener(chart, 'ready', function () {
          document.getElementById("link").innerHTML=chart.getImageURI();
      });
        chart.draw(view, options);
  }
  </script>
  </head>
  <body>
    <div id="columnchart_values" style="width: 600px; height: 600px;"></div>
    <div id="link"></div>a
  </body>
</html>
