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
	<script src="scripts/jquery.pause.min.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script>
	  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>,'<?php echo $_SESSION["auto"]; ?>',<?php echo Game::getNumberUsers(); ?>);
	  findingNumberOfUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
	</script>

	<script>
    var counter = 30;
		window.onload = function(){


			document.getElementById('bgMusic').volume = 0;
		    if (readCookie("playMusic")!="false"){
		    	document.getElementById('bgMusic').play();
		    	muteOn();
		    }else{
		    	muteOff();
		    }

			$('#timer').animate({
				width: "0%"
			}, 30000, "linear");

			var interval = setInterval(function() {
			    if(typeof gameplaying !== 'undefined' && gameplaying==false);
					else
							 counter--;
					console.log(counter);
			  //$('#timeLeft').html(counter);
			    if (counter <= 0) {
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
			<?php
				if(Game::findGame()->type == "age" || Game::findGame()->type == "entertainment" || Game::findGame()->type == "rand"){
			?>
			background-size       : contain;
			background-position   : top center;
			<?php }else{ ?>
			background-size       : cover;
			background-position   : center;
			<?php } ?>
		}
	</style>

</head>
<body>

	<div id="headerWrap">
		<div id="logoWrap">
			<a href="#" onclick="parent.redirectHome()" id="logoLink"><img src="img/logo.svg" id="logo"></a>
		</div>
		<div id="roundWrap">
			<?php echo $_SESSION["questionNumber"];  if ($_SESSION["numRounds"]<999) echo " of ". $_SESSION["numRounds"]; ?>
		</div>
		<div id="questionWrap">

<div id="questionType"><?php echo $theQuestion->getQuestionText(); ?></div>
<div id="actualQuestion"><?php echo $theQuestion->getLabel(); ?> <?php echo $theQuestion->getQuestionTextEnd(); ?>?</div>



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


<audio id="bgMusic" enablejavascript="yes"  volume="0">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg" volume="0">
	Your browser does not support the audio element.
</audio>


<input type="button" class="utilityButton" id="muteButton" 			onclick="mute()">
<input type="button" class="utilityButton" id="playGameButton" 		onclick="switchGameWAnimation()">
<input type="button" class="utilityButton" id="fullscreenButton" 	onclick="parent.fullscreen()">
<a href="endScreen.php" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:<?php echo substr($_SESSION["game_id"],0,5); ?></div>


</body>
</html>
