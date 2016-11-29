<?php

session_start();
$whitelist = array('message','submit','name','game_id','place');
require 'controller/dbsettings.php';
if (!isset($_SESSION["game_id"]))
  Game::findGameID();

if ($submit=="Join"){
    // $name=substr($name,0,20);
    // User::createUser($game_id,$name);

    date_default_timezone_set('America/New_York');
    //I have padded game id with 3 digit code for this date
    $_SESSION["game_id"]=$game_id.(	str_pad(date("z"), 3, "0", STR_PAD_LEFT));
    if (Game::findGame($_SESSION["game_id"])==null){
        $_SESSION["game_id"]=$game_id.(	str_pad(date("z"), 3, "0", STR_PAD_LEFT))-1;
        if (Game::findGame($_SESSION["game_id"])==null){
            header( 'Location: joinQuiz.php?error=Bad Game&name='.$name);
            die();
        }
    }
    if (!User::createUser($_SESSION["game_id"],$name)){
         header( 'Location: joinQuiz.php?error=Bad Username&game_id='.$game_id);
         die();
    }
    $game=Game::findGame();
    $questionNumber=$game->round;
    if ($questionNumber>0){
      if ($game->type=="geo"|| $game->type=="pt" || $game->type=="places")
              header( 'Location: userScreen.php?question='.$questionNumber ) ;
      else if ($game->type=="facts")
              header('Location: userScreenDecimal.php?perc=no&question='.$questionNumber ) ;
      else if ($game->type=="factsMax")
              header('Location: userScreenDecimal.php?perc=no&max=yes&question='.$questionNumber ) ;
      else if ($game->type=="factsPercent")
              header('Location: userScreenDecimal.php?perc=yes&question='.$questionNumber ) ;
      else if ($game->type=="science" || $game->type=="sports" || $game->type=="entertainment" || $game->type=="factsRand")
              header('Location: userScreenRand.php?question='.$questionNumber ) ;
      else if ($game->type=="WorldTime")//below is messed up I put 3 for region which is not right
              header('Location: userScreenWorldTime.php?region=3&perc=no&question='.$questionNumber ) ;
      else
          header('Location: userScreen'.$type=ucwords($game->type).'.php?question='.$questionNumber ) ;
      }
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Waiting...</title>
	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/waitingScreen.css">

	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
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

</style>
	<script>
		window.onload = function(){
	   		loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
		}
	</script>
</head>
<body>

<div id="headerContainer">
				<a href="#" id="logoLink"><img src="img/logo.svg" id="logo"></a>
    	<div id="waiting">Waiting...</div>

</div>
<div id="messageWrap">

  <div id="mainMessageWrap">

      <?php if ($submit=="Join") { ?>
        <div id="welcome">Game on, <?php echo $name; ?>!</div>
      <?php  }
       else if ($message=="nosubmit"){ ?>
        <div id="score"><div style="font-size:30px;">The question is over - remember to hit submit next time.</div>
      <?php }
      else if (is_numeric($message)){ ?>
        <div id="score"><div style="font-size:30px;">Your answer was</div> <?php echo $message ; ?> miles away.</div>
      <?php } else {
        echo $message;
       }  ?>

        <div id="mainMessageExtra">
          <?php if (is_numeric($place) && $place>0){ ?>You were closer than <?php echo $place ?>% of other people worldwide.
      <?php }  ?>
    </div>

  </div>

</div>
<div id="tryContainer">
	Others playing?
	<a href="checkQuestion.php" class="regButton" id="tryHere">Catch Up!</a>
</div>
</body>
</html>
