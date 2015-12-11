<?php
session_start();

require 'dbsettings.php';

if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
   header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));

?>
 <html>
 <head>

    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
var map;
var worldCenter = new google.maps.LatLng(20.6743890, -3.9455);
var inputMarker;

var MY_MAPTYPE_ID = 'custom_style';


function initialize() {


  var featureOpts = [
    { 
      elementType: 'labels',
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      stylers: [{ visibility: 'off' }]
    },
    {
      featureType: 'water',
      stylers: [
        { color: '#57AAC5' }
      ]
    }


  ];

  var styledMapOptions = {
    name: 'Custom Style'
  };

  var mapOptions = {
    zoom: 3,
    center: worldCenter,
    mapTypeControl: false,
    streetViewControl: false,
    mapTypeId: MY_MAPTYPE_ID
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  var styledMapOptions = {
    name: 'Custom Style'
  };

  inputMarker = new google.maps.Marker({
    position: worldCenter,
    map: map
  });


  google.maps.event.addListener(map, 'click', function(e) {
    document.getElementById('lat').value = e.latLng.lat();
    document.getElementById('long').value = e.latLng.lng();
    inputMarker.setPosition(e.latLng);
  });




  var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);

  map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
}

function moveMarker(position) {
  inputMarker.setPosition(position);
}



google.maps.event.addDomListener(window, 'load', initialize);

    </script>



 </head>
 <body>

   <p>Round <?php echo $_GET["question"] ?>
   <form name="form1" method="post" action="submitAnswer.php">
   <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
    <input type="hidden" id="lat" name="lat">
    <input type="hidden" id="long" name="long">

   <input type="submit" name="submit" id="submit" value="Submit">
   </form>

   </p>

    <div id="map-canvas"></div>


   <p>&nbsp;</p>
 </body>
 </html>
