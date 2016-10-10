<?php

session_start();
$whitelist = array('message','submit','name','place');
$_SESSION["game_id"]=$game_id=1111;
$_SESSION["single"]=true;

require '../controller/dbsettings.php';

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

</style>
	<script>
		window.onload = function(){
	   		loadWaitingForQuestionSingle('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
		}
	</script>
</head>
<body>

<div id="headerContainer">
				<a href="#" id="logoLink"><img src="../img/logo.svg" id="logo"></a>
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
