<?php
session_start();

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

  var locations = [
  <?php
  $i=0;
  foreach($allAnswers->allAnswers as $key=>$answer){

      echo "['".$answer->name."', ".$answer->location->lat.", ".$answer->location->longg.", 4]";
      if (++$i!= count($allAnswers->allAnswers))
          echo ",";
}
  ?>  ];

  //var myLatlng2 = new google.maps.LatLng(0,0);
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
<a href="getQuestion.php">Next Question
</a>
<?php

foreach ($allAnswers->allAnswers as $key => $value) {
  echo $value->name . ":".$value->distanceAway . " miles away. Total miles: ".$value->totalMiles."<br>";
  # code...
}
/*
$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".$_SESSION["questionNumber"]."'";
//echo $sql;
$result = $conn->query($sql);
if ($result)
{
   while($row = $result->fetch_assoc()){
     $lat1 = $row["lat"];
     $long1 = $row["longg"];
     $user_id = $row["user_id"];
     $submitTime= $row["submitTime"];
     $miles=round(LatLong::distance($lat,$long,$lat1,$long1,"M"));
     echo "<bR>user: ".getUserName($user_id) . " was : ".$miles ." miles away.  Submitted at time " . $submitTime ;
   }
}
*/

function getUserName($id)
{
  global $conn;
  $sql = "SELECT * FROM `users` WHERE user_id ='".$id."'";
  //echo $sql;
  $result = $conn->query($sql);
  if ($result)
  {
     $row = $result->fetch_assoc();
     return $row["name"];
   }
}
 ?>
</body>
</html>
