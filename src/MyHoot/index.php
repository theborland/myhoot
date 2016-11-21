<html>
<head>

	<title>GameOn.World - Online Trivia Game</title>

	<meta name="description" content="GameOn.World is an online, educational trivia game for groups! Starting a game is fast, easy, and doesn't require registration." />

	<link rel="stylesheet" href="style/global.css?ver=1">
	<link rel="stylesheet" href="style/form.css">
	<link rel="stylesheet" href="style/content.css">
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script src="scripts/startQuiz.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>



  <script type="text/javascript">

		window.onload = function(){

			  if (screen.width <= 800)
			    window.location = "joinQuiz.php";
			  /*else
			    window.location = "startQuiz.php";*/

			//set up clouds
		}



</script>

</head>
<body>
<?php include_once("controller/analyticstracking.php") ?>
<div id="wholeWrap">
	<div id="headWrap">
		<div id="logoWrap">
						<a href="http://GameOn.World" id="logoLink"><img src="img/logo.svg" id="logo"></a>
		</div>
		<div id="menuWrap">
			<div id="menuItemContainer">
				<div class="menuItem"><a href="joinQuiz.php" class="menuLink">Play!</a></div>
				<div class="menuItem"><a href="aboutUs.php" class="menuLink">About</a></div>
				<div class="menuItem"><a href="contactUs.php" class="menuLink">Contact</a></div>
			</div>
		</div>
	</div>

	<div id="bodyWrap">
		<div id="colLeft">
			<div id="jqWrap">
				<h4 class="formHeader">Join a Game</h4>
				<form action="waitingScreen.php">
					<label for="game_id" class="jqLabel"> GAME ID </label>
					<input type="text" name="game_id" id="game_id" class="jqInput" value=""  maxlength="5">

					<label for="name" class="jqLabel">YOUR NAME</label>
					<input type="text" name="name" id="name" class="jqInput"maxlength="20" >

					<Center><input type="submit" name="submit" value="Join" id="jqJoin" class="regButton"></Center>
				</form>
			</div>

		</div>
		<div id="colRight">

			<div id="welcomeMessage">Starting a game is fast, easy, and doesn't require registration!</div>
			<a href="game.php" id="startQuizButton" class="regButton">Start a Game!</a>
			<div id="extraMessage">Our website had some issues recently.  We are working on it and believe its fixed. The site is run by a full time teacher and some students and we hope you continue to use the site to encourage students to learn about the world. Sorry for the inconvenience.
				If you have any issues (or ideas) - tell us <a href="contactUs.php">here</a>.<br>
</div>
		</div>
	</div>

		<div id="pageFooter">
			Copyright &copy; 2016 GameOnWorld
		</div>

</div>
</body>
</html>
