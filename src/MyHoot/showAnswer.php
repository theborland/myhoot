<?php
session_start();
//echo "sdfsdf";
require 'controller/dbsettings.php';
if (Game::findGame()->type!="geo")
     header( 'Location: showAnswerOther.php') ;

$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
$theQuestion=Question::loadQuestion();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Answer</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswer.css">
	<link rel="stylesheet" href="style/showAnswerOther.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>

	<script>

		window.onload = function(){
		    if (readCookie("playMusic")!="false"){
		    	muteOn();
		    }

			<?php
			if (isset($_SESSION["auto"]) && $_SESSION["auto"]=='yes')
			{
			?>
			//automatically forward if automode is on
			setTimeout( function(){
			      window.location.href='getQuestion.php';
			}  , 7000 );
			<?php
			}
			?>


		}

		function initialize() {

		  var locations = <?php echo $allAnswers->getLocations(); ?>;

		  var myLatlng = new google.maps.LatLng(<?php echo $allAnswers->correctAns->location->lat; ?>,<?php echo $allAnswers->correctAns->location->longg; ?>);//ll.lat(),ll.lng());
		  var mapOptions = {
		    zoom: 4,
		        mapTypeControl: false,
		    streetViewControl: false,
		center: myLatlng
		  }
		  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		  var marker = new google.maps.Marker({
		      position: myLatlng,
		      map: map,
		      animation: google.maps.Animation.BOUNCE
		  });


		  var marker2, i;

		    for (i = 0; i < locations.length; i++) {
		      marker2 = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        map: map,
		        title: locations[i][0],
		        icon: new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" +locations[i][3])
		        //icon:  //'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
		      });
		    }

		}

google.maps.event.addDomListener(window, 'load', initialize);
	</script>




</head>
<body>


<div id="topBarWrap">
  <div id="answerLabel"> <?php echo $theQuestion->getLabel(); ?></div>

	<div id="topLeftCell">
	</div><div id="topRightCell">

		<a href="getQuestion.php" class="regButton" id="userMapSubmit">Next Question</a>

	</div>
</div>
<div id="sidebarWrap">
	<div id="sidebarHeader">Scoreboard</div>
	<div id="scoresWrap" class="scrollable">


      <?php
        $allAnswers->getTP();
        //echo($allAnswers->allAnswers[0]->color);
           foreach ($allAnswers->allAnswers as $key => $value)
            { ?>
              <div class="scoresLine">
                <div class="scoresName" style="background:#<?php echo $value->color; ?>"><?php echo stripslashes($value->name); ?></div>
                <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>
                <div class="roundPoints"><?php echo $value->roundPoints; ?></div>
              </div>
      <?php }?>



	</div>
</div>

<div id="map-canvas"></div>


<audio id="bgMusic" autoplay enablejavascript="yes" volume="0">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">
<a href="endScreen.php" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:<?php echo $_SESSION["game_id"] ?></div>


</body>
</html>
