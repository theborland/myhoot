<?php
session_start();
//echo "sdfsdf";
//echo $_SESSION["questionNumber"];
require 'controller/dbsettings.php';
//  $_SESSION["game_id"]=90993;
  //$_SESSION["questionNumber"]=7;
$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
$theQuestion=Question::loadQuestion();

$max=$allAnswers->getMax();
$min=$allAnswers->getMin();
if ($max-$min==0)
		$max=($allAnswers->correctAns->value)*2;
$rounding=(strlen($max)-3)*-1;
if ($rounding>0)$rounding=0;
//echo "sdfd".$max;
$reg0=round($min,$rounding);
$reg1=round((($max-$min)*.25)+$min,$rounding);
$reg2=round((($max-$min)*.5)+$min,$rounding);
$reg3=round((($max-$min)*.75)+$min,$rounding);
$reg4=round($max,$rounding);
$correctLoc=($allAnswers->correctAns->value-$reg0)/($reg4-$reg0);
//die ($reg2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Answer</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswerOther.css">
	<link rel="stylesheet" href="style/nouislider.min.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/showAnswer.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="scripts/nouislider.min.js"></script>

	<script>



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
			window.onload = function(){
		    answer = document.getElementById("answer").innerHTML;
			  //alert(answer);
			  answer = answer.length >= 10 ? answer :new Array(2).join("x") + answer;
			  //alert(answer);
			  setTimeout( function(){

			    for(n=0; n < answer.length; n++){
			      time = 50 + Math.round(Math.random() * 50);
			      for(i=0; i < 25; i++){
			          val = (answer.charAt(n) == "x".charAt(0)) ? "&nbsp;" : answer.charAt(n) + "";
			          animateNum(i, n, (i==24), val, time);
			      }
			    }
			  }, 500);

			var timeline = document.getElementById('timeline');

			noUiSlider.create(timeline, {
			  start: [<?php echo $reg2 ?>],
			  connect: "upper",
			  direction: 'ltr',
			  range: {
			    'min': [<?php echo $reg0 ?>],
			    '25%': [<?php echo $reg1 ?>],
			    '50%': [<?php echo $reg2 ?>],
			    '75%': [<?php echo $reg3 ?>],
			    'max': [<?php echo $reg4 ?>]
			  },pips: { // Show a scale with the slider
			    mode: 'steps',
			    density: 2
			  }
			});

			  labels = document.getElementsByClassName("noUi-value-large");
			  for(var i=0; i<labels.length;i++){
			    val = parseInt(labels[i].innerHTML);
			    labels[i].innerHTML = comma(val);
			  }
			}
	</script>

	<style>
		body{
			background-color:black;
	    background-image: url('<?php echo $theQuestion->loadImage(); ?>');
			background-attachment : fixed;
			<?php
				if(Game::findGame()->type == "age"){
			?>
			background-size       : contain;
			background-position   : top center;
			<?php }else{ ?>
			background-size       : cover;
			background-position   : center;
			<?php } ?>
			background-repeat: no-repeat;
		}
	</style>




</head>
<body>


<div id="topBarWrap">
	<div id="topLeftCell">


		  <div id="answerLabel"> <?php echo $theQuestion->getLabel(); ?> </div>
		  <div id="answerWrap">
		    <?php for ($i=0;$i<=strlen($allAnswers->correctAns->value);$i++){  ?>
		       <div class="answerNum" id="answerNum<?php echo $i; ?>">0</div>
		       <?php if ((strlen($allAnswers->correctAns->value)+1-$i)%3==1 && $i!=strlen($allAnswers->correctAns->value)){   ?>
		         <div class="answerNum noB" id="answerNumC">&nbsp;</div>
		       <?php  }  ?>
		    <?php } ?>
		    <div class="answerNum noB" id="answerNumC"  style="width:70px;"><?php echo $theQuestion->getQuestionUnits(); ?></div>
		  </div>




	</div><div id="topRightCell">
		<a href="getQuestion.php" class="regButton" id="userMapSubmit"><?php if ($_SESSION["questionNumber"]<$_SESSION["numRounds"]) echo "Next Question"; else echo "Game Over"; ?></a>

	</div>
</div>
<div id="sidebarWrap">
	<div id="sidebarHeader">Scoreboard 			<?php echo $_SESSION["questionNumber"];  if ($_SESSION["numRounds"]<999) echo " of ". $_SESSION["numRounds"]; ?>
</div>
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




<div id="timelineWrap" style="display:none;">


  <div id="timeline">
    <div class="timelineMarker" id="timelineCA" style="border-color:#E12027;margin-left:calc(<?php echo $correctLoc*100; ?>% - 10px);">&nbsp;</div>
    <?php foreach ($allAnswers->allAnswers as $key => $value)
    {
        $loc=($value->ans-$reg0)/($reg4-$reg0);
        ?>
    <div class="timelineMarker" style="border-color:#<?php echo $value->color ?>;margin-left:calc(<?php echo $loc*100; ?>% - 10px);">&nbsp;</div>
    <?php } ?>

  </div>
</div>
<div id="answer"><?php echo ($allAnswers->correctAns->value); ?></div>



<input type="button" class="utilityButton" id="muteButton" 			onclick="mute()">
<input type="button" class="utilityButton" id="fullscreenButton" 	onclick="parent.fullscreen()">
<a href="endScreen.php" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:<?php echo substr($_SESSION["game_id"],0,5); ?></div>

</body>
</html>
