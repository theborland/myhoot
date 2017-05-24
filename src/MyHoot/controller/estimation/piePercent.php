<?php $perc= rand(1,99); ?>
<html>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

      var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Work',     <?php echo $perc; ?>],
        ['Eat',      <?php echo 100-$perc; ?>]
      ]);

      var options = {
        pieSliceText:'none',
        legend: {position:'none'},
        slices: {0: {color: 'red'}, 1: {color: 'yellow'}},
        pieStartAngle:<?php echo rand(10,350); ?>
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));



      // Wait for the chart to finish drawing before calling the getImageURI() method.
      google.visualization.events.addListener(chart, 'ready', function () {
//alert('s');
        //console.log(chart_div.innerHTML);
        //window.location.href = chart.getImageURI();
      });

chart.draw(data, options);


  }
  </script>

  <div id="piechart" style="width: 900px; height: 500px;"></div>
