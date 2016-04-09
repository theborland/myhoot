<?php
	session_start();
	require 'controller/dbsettings.php';
	$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
	$allAnswers->fillMissingAnswers();
?>

<html>
<head>
	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswerOther.css">
	<link rel="stylesheet" href="style/endScreen.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>

	<script>
		window.onload = function(){
			document.getElementById('bgMusic').volume = 0;
		    if (readCookie("playMusic")!="false"){
		    	muteOn();
		    }
		}
	</script>
</head>
<body>



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

<div id="winnerStandAligner">
	<div id="winnerStandWrap">

      		<?php if (sizeof($allAnswers->allAnswers )>=2){ ?>

		<div class="winnerStand" id="winnerStand1">
			<div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[1]->color; ?>;">
				<?php echo stripslashes($allAnswers->allAnswers[1]->name); ?>
			</div>
		</div>

    		<?php  } if (sizeof($allAnswers->allAnswers )>=1){ ?>

		<div class="winnerStand" id="winnerStand2">
			<div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[0]->color; ?>;">
				<?php echo stripslashes($allAnswers->allAnswers[0]->name); ?>
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


<audio id="bgMusic" autoplay enablejavascript="yes" volume="0">
  <source src="music/end.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">

<div id="winnerBackground"></div>



</body>
</html>
