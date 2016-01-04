<?php

session_start();
$whitelist = array('message','submit','name','game_id');
require 'dbsettings.php';

if ($submit=="Join")
     User::createUser($game_id,$name);

 ?>
 <html>
 <head>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/joinQuiz.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
		body{
			background:#e1e1e1;
		}

		#jqWrap{
			background:#333;
			box-shadow: 0px 0px 30px rgba(0,0,0,.5);
			color:#fff;
			border:0px;
		}
		#score{
			font-weight: 100;
			font-size: 30px;
		}
		#waitingDiv{
			font-size:17px;
			color:#b1b1b1;
		}
		#gameID{
			text-align: center;
			color: #a1a1a1;
			font-size: 15px;
		}
		#joinHere:link, #joinHere:visited{
			color:#fff;
		}
		#everyonePlaying{
			color:#a1a1a1;
			display: inline;
		}
		#waitingDiv{
			display: inline;
		}
		hr{
			border:0px;
			height: 1px;
			background: #a1a1a1;
			margin:15px 0px;
		}
    </style>
	 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	 <script src="socketScripts.js"></script>
	 <script>
	   loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
	 </script>

</script>
 </head>
 <body>

	<div id="jqWrap">
		<img src="logo.png" id="logo">
		<h4>Waiting</h4>
		<hr>
    <?php if (is_numeric($message)){ ?>
	 	<div id="score"><div style="font-size:18px;font-weight:bold;">Your answer was</div> <?php echo $message . "<br>"; ?> miles away.</div>
<?php } else { ?>
  <div id="score"><div style="font-size:18px;font-weight:bold;"> <?php echo $message . "<br>"; ?> </div>
<?php }  ?>
  	<hr>
		<div id="waitingDiv">We are waiting... </div><div id="everyonePlaying">Everybody else playing? <a href="checkQuestion.php" id="joinHere">Try here.</a></div>
		<br><br>
		<div id="gameID">Game id: <?php echo $_SESSION["game_id"]; ?></div>
	</div>
 </body>
 </html>
