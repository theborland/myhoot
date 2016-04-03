<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>MyHoot</title>


	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/getQuestion.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>

	<script>
		
		window.onload = function(){
			var counter = 30;

			$('#timer').animate({
				width: "0%"
			}, 30000, "linear");

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
	
	<div id="headerWrap">
		<div id="logoWrap">
			<img src="img/logo.png" id="logo">
		</div>
		<div id="roundWrap">
			#24
		</div>
		<div id="questionWrap">
			What city is this? Do you know? I didn't think so. Donchu pretend that you know, cuz you don't.
		</div>
		<div id="controlWrap">
			<a href="#" class="regButton" id="showAnswer">Show Answer</a>
			<div id="numAnswersContainer"><div id="numAnswers">3/4</div> users have answered</div>
		</div>
	</div>
	<div id="timerContainer">
		<div id="timer"></div>
	</div>
<div id="answersWrap" class="scrollable">
	<div class="userAnswer" style="background:#38D38E;">John <div class="userResult">434,134</div></div>
	<div class="userAnswer" style="background:#B15751;">Someone <div class="userResult">483</div></div>
	<div class="userAnswer" style="background:#5291D6;">Else <div class="userResult">99,148,431</div></div>

</div>


<audio id="bgMusic" autoplay enablejavascript="yes">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">
<a href="#" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:22332</div>

</body>
</html>