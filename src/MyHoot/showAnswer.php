<?php
session_start();
//echo "sdfsdf";
require 'dbsettings.php';

$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
?>

<html>
<head>
<style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>
      <script>

function initialize() {

  var locations = <?php echo $allAnswers->getLocations(); ?>;

  var myLatlng = new google.maps.LatLng(<?php echo $allAnswers->correctAns->location->lat; ?>,<?php echo $allAnswers->correctAns->location->longg; ?>);//ll.lat(),ll.lng());
  var mapOptions = {
    zoom: 4,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      animation: google.maps.Animation.BOUNCE,
      title: '<?php echo $allAnswers->correctAns->name; ?>'
  });


  var marker2, i;

    for (i = 0; i < locations.length; i++) {
      marker2 = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        title: locations[i][0],
        icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
      });
    }

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>

Answer:
<div id="map-canvas"></div>
<a href="showScoreBoard.php">ScoreBoard
</a>
<a href="getQuestion.php">Next Question
</a>
<?php

foreach ($allAnswers->allAnswers as $key => $value) {
  echo $value->name . ":".$value->distanceAway . " miles away. <br>";
  # code...
}

 ?>
</body>
</html>
