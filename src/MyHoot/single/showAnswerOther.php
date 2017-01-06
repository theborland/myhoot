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
	<link rel="stylesheet" href="../style/waitingScreen.css">

	<script src="../scripts/global.js"></script>
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
	<script>

  var count=<?php echo $timeLeft; ?>;

  var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

  function timer()
  {
    count=count-1;
    if (count <= 0)
    {
      window.location.href = "userScreen.php";
       clearInterval(counter);
       count=999;
       return;
    }
   else if (count<30)
      document.getElementById("timer2").innerHTML=count + " secs"; // watch for spelling
  }


	</script>
</head>
<body>

<div id="headerContainer">
				<a href="#" id="logoLink"><img src="../img/logo.svg" id="logo"></a>
    	<div id="waiting">Show Answer</div>
<span id="timer2"></span>
</div>
<div id="messageWrap">

  <div id="mainMessageWrap">
<?php
if ($user->avg>0){
   echo "This round you scored ".$user->place . "%";
   echo "You placed " .$user->singleStatsRound->place. " out of ".$user->singleStatsRound->numOfPlayers;
   echo "<br>Your average is ".$user->avg . "%";
   echo "Overall you are " .$user->singleStatsGame->place. " out of ".$user->singleStatsGame->numOfPlayers;
 }
 ?>


  </div>

</div>
<div id="tryContainer">
	Others playing?
	<a href="checkQuestion.php" class="regButton" id="tryHere">Catch Up!</a>
</div>
</body>
</html>
