<?php //$count=95;
?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Age', 'Weight'],<?php
          for ($i=0;$i<$count;$i++){
            $x=rand(0,100);
            $y=rand(0,100);
            echo "[$x,$y]";
            if ($i!=$count-1)echo ",";

          }


           ?>

        ]);

        var options = {
backgroundColor:'#cedbff',
 colors: ['green'],
 chartArea: {'width': '100%', 'height': '100%'},
          vAxis: {
    gridlines: {
        color: 'transparent'
    }
},
    hAxis: {
    gridlines: {
    color: 'transparent'
    }
    },
          legend: 'none'
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 600px; height: 600px; margin-top:8px;   margin-left: auto;
    margin-right: auto;"></div>
  </body>
</html>
