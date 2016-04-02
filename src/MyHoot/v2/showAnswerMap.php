<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Answer</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswerMap.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>

	<script>
		
		window.onload = function(){
			var counter = 30;

			//animation clock
			var x=0;
			var interval = setInterval(function() {

			     x++;
			}, 50);

		    if (readCookie("playMusic")=="false"){
		    	muteOff();
			}


		}

	</script>

	<style>
		body{
			background-image:url('img/background.jpg');
		}
	</style>




</head>
<body>



<div id="sidebarWrap">
	
</div>



<audio id="bgMusic" autoplay enablejavascript="yes">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">
<a href="#" id="endGame" class="regButton">End Game</a>

	
</body>
</html>

