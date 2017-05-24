<?php //$perc= rand(1,99);
?>

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
        pieSliceTextStyle:{fontSize:33},
        pieSliceText:'<?php if ($showAnswer=="yes")echo "percentage"; else echo "none"; ?>',
        legend: {position:'none'},
        slices: {0: {color: 'red'}, 1: {color: 'blue'}},
        pieStartAngle:<?php echo rand(10,350); ?>,
        enableInteractivity : false

      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));

chart.draw(data, options);


  }
  </script>

  <div id="piechart" style="width: 600px; height: 500px;margin-left: auto;
  margin-right: auto;"></div>
