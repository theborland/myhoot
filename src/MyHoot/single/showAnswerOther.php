<?php

session_start();
$whitelist = array('message','submit','name','place');
$_SESSION["game_id"]=$game_id=1111;
$_SESSION["single"]=true;


require '../controller/dbsettings.php';
Game::findGame()->type;
if (Game::findGame()->type!="geo" && Game::findGame()->type!="pt" && Game::findGame()->type!="places" && strpos($_SERVER['REQUEST_URI'],'Other')==false)
     header( 'Location: showAnswerOther.php') ;
$theQuestion=Question::loadQuestion();
$user=User::loadUserSingle();
//$_SESSION["questionNumber"]=$theQuestion->
//echo $_SESSION["questionNumber"];
$allAnswers=new AllAnswers($_SESSION["questionNumber"]);

$seconds=time();
$timeLeft=($lengthOfGame+$lengthOfBreak)-$seconds%($lengthOfGame+$lengthOfBreak);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Waiting...</title>
  <link rel="stylesheet" href="../style/global.css">
  <link rel="stylesheet" href="../style/showAnswerOtherSingle.css">
	<link rel="stylesheet" href="../style/waitingScreen.css">

	<script src="../scripts/global.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="../scripts/socketScripts.js"></script>
	 <script src="http://gameon.world/AutobahnJS/build/autobahn.min.js"></script>

<style>
  #mainMessageWrap{
  background:rgba(255,255,255,0.5);
  color:#333;
  padding:20px;
  border:0px;
  border-radius: 10px;
  font-size:25px;
  font-weight: 300;
}

#mainMessageExtra{
  margin-top:5px;
  font-size:18px;
  font-weight:300;
  color:rgba(0,0,0,0.5);
}

#timerBar{
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 5px;
  background: #17DB91;
}

</style>
	<script>

  window.onload = function(){


      var timeleft = <?php echo $timeLeft; ?> * 1000;
      console.log(timeleft);
      $('#timerBar').animate({
        width: "0%"
      }, timeleft, "linear");


      var counter=setInterval(timer, <?php echo $timeLeft; ?> * 1000); //1000 will  run it every 1 second

      function timer()
      {
           window.location.href = "userScreen.php";
           clearInterval(counter);
           count=0999;
           return;
      }

    }


	</script>
</head>
<body>

<div id="headerContainer">
				<a href="#" id="logoLink"><img src="../img/logo.svg" id="logo"></a>
    	<div id="waiting">Answer</div>
<span id="timerBar"></span>
</div>
<div id="messageWrap">

  <div id="mainMessageWrap">
  <div id="saoTable">
    <?php
    $statsGame=$user->singleStatsGame;
    foreach ($statsGame->topFive as $key=>$value)
    {
      ?>
            <div class="saoItem">
              <div class="saoNumber"><?php echo $key ?> </div>
              <div class="saoName">
                   <?php
                   if ($statsGame->place==$key)  //meaning the user is in a place
                      echo "<b>";
                   echo $value->name;
                   if ($statsGame->place==$key)  //meaning the user is in a place
                     echo "</b>";
                   ?>
              </div>
              <div class="saoAve"><?php echo $value->avg  ?>%</div>
            </div>
    <?php
    }
    if ($statsGame->place>3 && $user->avg>0){
    ?>
            <div class="saoItem">
              <div class="saoNumber"><?php echo $statsGame->place ?> </div>
              <div class="saoName">
                   Your best
              </div>
              <div class="saoAve"><?php echo $user->avg  ?>%</div>
            </div>
    <?php
    }
    if ($statsGame->tempPlace>3 && $user->tempAvg>0){
    ?>
            <div class="saoItem">
              <div class="saoNumber"><?php echo $statsGame->tempPlace ?> </div>
              <div class="saoName">
                   Your current streak
              </div>
              <div class="saoAve"><?php echo $user->tempAvg  ?>%</div>
            </div>
    <?php
    }
    if ($user->tempAvg<0){
    ?>
            <div class="saoItem">
              <div class="saoNumber"> </div>
              <div class="saoName">
                   Your current streak (you need 5 in a row to place)
              </div>
              <div class="saoAve"><?php echo -1*$user->tempAvg;  ?>%</div>
            </div>

    <?php }
    ?>

  </div>


<?php
$statsRound=$user->singleStatsRound;
//echo sizeOf($allAnswers->allAnswers);
if ($user->avg>0 && isset($allAnswers->allAnswers[$user->id])){
   echo "This round you were ". $theQuestion->getUnitsAway($user->place). " away.  This is better than ".
           $allAnswers->allAnswers[$user->id]->avg . "% of the people worldwide!<div id=\"mainMessageExtra\"";
   if ($user->singleStatsRound->numOfPlayers>1)
        echo "<br>You placed " .$user->singleStatsRound->place. " out of ".$user->singleStatsRound->numOfPlayers.".";
   }
 ?>

  </div>
  </div>

</div>

</body>
</html>
