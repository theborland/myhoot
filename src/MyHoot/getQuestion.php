<?php
session_start();
//echo $_SESSION["questionNumber"];

include("controller/gameLogic.php");
//die();
?>
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
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script>
	  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>,'<?php echo $_SESSION["auto"]; ?>');
	  findingNumberOfUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
	</script>

	<script>

		window.onload = function(){


		    if (readCookie("playMusic")!="false"){
		    	muteOn();
		    }


			var counter = 30;

			$('#timer').animate({
				width: "0%"
			}, 30000, "linear");

			var interval = setInterval(function() {
			    counter--;
			  $('#timeLeft').html(counter);
			    if (counter == 0) {
			        window.location.replace("showAnswer.php");
			    }
			}, 1000);

			//animation clock
			var x=0;
			var interval = setInterval(function() {

			     x++;
			}, 50);



		}

	</script>

	<style>
		body{
			background-image:url("<?php echo $theQuestion->getImage() ?>");
		}
	</style>

</head>
<body>

	<div id="headerWrap">
		<div id="logoWrap">
			<img src="img/logo.png" id="logo">
		</div>
		<div id="roundWrap">
			#<?php echo $_SESSION["questionNumber"] ?>
		</div>
		<div id="questionWrap">
			<?php echo $theQuestion->getQuestionText(); ?><?php echo $theQuestion->getLabel(); ?>?
		</div>
		<div id="controlWrap">
			<a href="showAnswer.php" class="regButton" id="showAnswer">Show Answer</a>
			<div id="numAnswersContainer"><div id="numAnswers">0</div>/<div id="numPlayers">0</div> users have answered</div>
		</div>
	</div>
	<div id="timerContainer">
		<div id="timer"></div>
	</div>
<div id="answersWrap" class="scrollable">


</div>


<audio id="bgMusic" autoplay enablejavascript="yes"  volume="0">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">
<a href="#" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:<?php echo $_SESSION["game_id"] ?></div>

</body>
</html>
