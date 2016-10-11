<?php

session_start();
require 'controller/dbsettings.php';


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Game Over!</title>
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
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '203380266741886',
      xfbml      : true,
      version    : 'v2.7'
    });
  };


  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


<div id="headerContainer">
				<a href="#" id="logoLink"><img src="img/logo.svg" id="logo"></a>

	<div id="waiting">Game Over</div>

</div>
<div id="messageWrap">

<input type="button" value="Share on Facebook!" id="shareOnFB" class="regButton">
<br>
<div id="surveyWrap">
	Enjoyed our game or have any suggestions? <a href="#">Give us feedback!</a>
</div>

</div>
<div id="tryContainer">
	Or maybe not:
	<a href="joinQuiz.php" class="regButton" id="tryHere" class="regButton">Play again!</a>
</div>

<script>
	document.getElementById('shareOnFB').onclick = function() {
	  FB.ui({
	    method: 'share',
	    display: 'iframe',
	    quote: 'I just got nth place playing against my friends on GameOn.World! Start a game now, it\'s free and easy to play!',
	    href: 'https://gameon.world/game.php',
	  }, function(response){});
	}

</script>
</body>
</html>
