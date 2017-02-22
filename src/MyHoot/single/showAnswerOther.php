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

$seconds=time();
$timeLeft=($lengthOfGame+$lengthOfBreak)-$seconds%($lengthOfGame+$lengthOfBreak);

if ($submit=="Join"){
    // $name=substr($name,0,20);
    // User::createUser($game_id,$name);

    date_default_timezone_set('America/New_York');
    //I have padded game id with 3 digit code for this date
    User::createUser($_SESSION["game_id"],$name);
    $_SESSION["game_id"]=$game_id;
    $game=Game::findGame();
    $questionNumber=$game->round;
    header( 'Location: checkQuestion.php') ;
}

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
	 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>

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
    <div class="saoItem">
      <div class="saoNumber">1</div>
      <div class="saoName">Orkhan</div>
      <div class="saoAve">99.9%</div>
    </div>
    <div class="saoItem">
      <div class="saoNumber">2</div>
      <div class="saoName">Someone</div>
      <div class="saoAve">93.5%</div>
    </div>
    <div class="saoItem">
      <div class="saoNumber">3</div>
      <div class="saoName">Else</div>
      <div class="saoAve">94%</div>
    </div>
    <div class="saoItem">
      <div class="saoNumber">4</div>
      <div class="saoName">On</div>
      <div class="saoAve">23.4%</div>
    </div>
    <div class="saoItem">
      <div class="saoNumber">5</div>
      <div class="saoName">Here</div>
      <div class="saoAve">10.3%</div>
    </div>
  </div>


<?php
if ($user->avg>0){
   echo "This round you did better than ".$user->place . "% of the people worldwide!<div id=\"mainMessageExtra\"";
   echo "You placed " .$user->singleStatsRound->place. " out of ".$user->singleStatsRound->numOfPlayers.".";
   echo "<br>Your average is ".$user->avg ."%.";
   echo "Overall you are " .$user->singleStatsGame->place. " out of ".$user->singleStatsGame->numOfPlayers.".";
 }
 ?>

  </div>
  </div>

</div>
<div id="tryContainer">
	Others playing?
	<a href="checkQuestion.php" class="regButton" id="tryHere">Catch Up!</a>
</div>
</body>
</html>
