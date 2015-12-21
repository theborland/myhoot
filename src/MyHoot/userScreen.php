<?php
session_start();
require 'dbsettings.php';
if (isset($_GET["question"]))

if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
   header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));
?>
 <html>
 <head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style/global.css">
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
var worldCenter = new google.maps.LatLng(20.6743890, -3.9455); //starting position
var inputMarker;
var MY_MAPTYPE_ID = 'custom_style';

function initialize() {
  var featureOpts = [
    {
      elementType: 'labels',
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      stylers: [{ visibility: 'off' }]
    },
      { featureType: "administrative.province", stylers: [ { visibility: "off" } ] },
    { featureType: "road", stylers: [ { visibility: "off" } ] },
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
    minZoom: 2,
    maxZoom:8,
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



// TEST CODE START

// bounds of the desired area
// TEST CODE END



    </script>

 </head>
 <body>

  <div id="overlayWrap">
    <img src="logo.png" id="logo">
    <h3>Round <?php echo $_GET["question"] ?></h3>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="long" name="long">
        <input type="submit" name="submit" id="userMapSubmit" value="Go!">
      </form>

  </div>

    <div id="map-canvas"></div>
 </body>
 </html>
