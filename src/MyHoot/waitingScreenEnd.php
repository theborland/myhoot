<?php

session_start();
require 'controller/dbsettings.php';


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
         loadWaitingForNextGame('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
     }
   </script>

</head>
<body>

<div id="headerContainer">
				<a href="#" id="logoLink"><img src="img/logo.svg" id="logo"></a>

	<div id="waiting">Game Over - all good things must come to an end...</div>

</div>
<div id="messageWrap">



</div>
<div id="tryContainer">
	Or maybe not:
	<a href="joinQuiz.php" class="regButton" id="tryHere">Play again!</a>
</div>
</body>
</html>
