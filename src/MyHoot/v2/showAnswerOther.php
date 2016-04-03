

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Answer</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/showAnswer.css">
	<link rel="stylesheet" href="style/nouislider.min.css">

	<!--<script src="scripts/getQuestion.js"></script>-->
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="scripts/nouislider.min.js"></script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>

	<script>
		
		window.onload = function(){
		    if (readCookie("playMusic")=="false"){
		    	muteOff();
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
			background-image:url('img/background.jpg');
		}
	</style>




</head>
<body>


<div id="topBarWrap">
	<div id="topLeftCell">


		  <div id="answerLabel"> <?php echo $theQuestion->getQuestionText(); ?> <?php echo $theQuestion->getLabel(); ?></div>
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




<div id="timelineWrap" style="display:none;">

  <?php
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
  ?>

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







<audio id="bgMusic" autoplay enablejavascript="yes">
  <source src="music/quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>


<input type="button" id="muteButton" onclick="mute()">
<a href="#" id="endGame" class="regButton">End Game</a>
<div id="gameID">ID:22332</div>

	
</body>
</html>

