<?php
	session_start();
	require 'controller/dbsettings.php';
	//$_SESSION["game_id"]=64733165;
	//$_SESSION["questionNumber"]=10;
	if (!isset($_SESSION["game_id"]))
		$_SESSION["game_id"]=Game::findGameID();
	$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
	//	$allAnswers->getTP();
	//print_r($allAnswers);
	$allAnswers->fillMissingAnswers();
	$theQuestion=Question::loadQuestion();
  $theQuestion->alertUsers(-1,"end");
?>

<html>
<head>
	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswerOther.css">
	<link rel="stylesheet" href="style/endScreen.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js?ver=1"></script>

	<script>
		window.onload = function(){
			document.getElementById('bgMusic').volume = 0;
		    if (readCookie("playMusic")!="false"){
				document.getElementById('bgMusic').play();
				muteOn();
		    }else{
		    	muteOff();
		    }
		}
    <?php if (isset($_SESSION["continuous"]) && $_SESSION["continuous"]==true){ ?>
		window.setTimeout(function(){window.location.href = "startQuizD.php?replay=yes";}, 10000);
   <?php } ?>
	</script>
</head>
<body>



<div id="sidebarWrap">
	<div id="sidebarHeader">Final Scoreboard</div>
	<div id="scoresWrap" class="scrollable">

      <?php
        $allAnswers->getTP();
        //echo($allAnswers->allAnswers[0]->color);
           foreach ($allAnswers->allAnswers as $key => $value)
            { ?>
              <div class="scoresLine">
                <div class="scoresName" style="background:#<?php echo $value->color; ?>"><?php echo stripslashes($value->name); ?></div>
                <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>

              </div>
      <?php }?>



	</div>
</div>

<div id="winnerStandAligner">
	<div id="winnerStandWrap">

      		<?php if (sizeof($allAnswers->allAnswers )>=1){ ?>

		<div class="winnerStand" id="winnerStand1">
			<div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[0]->color; ?>;">
				<?php echo stripslashes($allAnswers->allAnswers[0]->name); ?>
			</div>
		</div>

    		<?php  } if (sizeof($allAnswers->allAnswers )>=2){ ?>

		<div class="winnerStand" id="winnerStand2">
			<div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[1]->color; ?>;">
				<?php echo stripslashes($allAnswers->allAnswers[1]->name); ?>
			</div>
		</div>

			<?php  } if (sizeof($allAnswers->allAnswers )>=3){ ?>


		<div class="winnerStand" id="winnerStand3">
			<div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[2]->color; ?>;">
				<?php echo stripslashes($allAnswers->allAnswers[2]->name); ?>
			</div>
		</div>

			<?php } ?>
	</div>
</div>


<a href="startQuiz.php?replay=yes" id="replayButton" class="regButton">Replay</a>
<a href="https://docs.google.com/forms/d/e/1FAIpQLSd6Ffh6mC0tBQ52YoEWNdu696FHKfh5dxGYTinzr4fC7nK1OQ/viewform?c=0&w=1" id="feedbackButton" class="regButton">Give Feedback</a>

<audio id="bgMusic"  enablejavascript="yes" volume="0">
  <source src="music/end.mp3"  type="audio/mpeg" volume="0">
	Your browser does not support the audio element.
</audio>


<input type="button" class="utilityButton" id="muteButton" 			onclick="mute()">
<input type="button" class="utilityButton" id="fullscreenButton" 	onclick="parent.fullscreen()">

<div id="winnerBackground"></div>



</body>
</html>
