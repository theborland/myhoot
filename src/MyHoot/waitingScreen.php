<?php

session_start();
$whitelist = array('message','submit','name','game_id');
require 'controller/dbsettings.php';

if ($submit=="Join"){
    // $name=substr($name,0,20);
     $_SESSION["game_id"] =$game_id;
    // User::createUser($game_id,$name);

    if (Game::findGame()==null){
         header( 'Location: joinQuiz.php?error=Bad Game&name='.$name);
         die();
    }
    if (!User::createUser($game_id,$name)){
         header( 'Location: joinQuiz.php?error=Bad Username&game='.$game_id);
         die();
    }
    $game=Game::findGame();
    $questionNumber=$game->round;
    if ($questionNumber!=-1){
        if ($game->type=="geo"){
          header( 'Location: userScreen.php?question='.$questionNumber ) ;
        }
        else {
          $type=ucwords($game->type);
          header( 'Location: userScreen'.$type.'.php?question='.$questionNumber ) ;
        }
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

	<script>
		window.onload = function(){
	   		loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
		}
	</script>
</head>
<body>

<div id="headerContainer">
	<img src="img/logo.png" id="logo">
	<div id="waiting">Waiting...</div>

</div>
<div id="messageWrap">


	<?php if (is_numeric($message)){ ?>
		<div id="score"><div style="font-size:18px;font-weight:bold;">Your answer was</div> <?php echo $message . "<br>"; ?> miles away.</div>
	<?php } else { ?>
		<div id="score"><div style="font-size:18px;font-weight:bold;"> <?php echo $message . "<br>"; ?> </div>
	<?php }  ?>


</div>
<div id="tryContainer">
	Everyone else playing?
	<a href="checkQuestion.php" class="regButton" id="tryHere">Catch Up!</a>
</div>
</body>
</html>
