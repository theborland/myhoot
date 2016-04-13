<html>
<head>

	<title>GameOn.World</title>

	<link rel="stylesheet" href="style/global.css">
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
			<img src="img/logo.svg" id="logo">
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
				<h4 class="formHeader">Join a Quiz</h4>
				<form action="waitingScreen.php">
					<label for="game_id" class="jqLabel"> QUIZ ID </label>
					<input type="text" name="game_id" id="game_id" class="jqInput" value=""  maxlength="5">

					<label for="name" class="jqLabel">YOUR NAME</label>
					<input type="text" name="name" id="name" class="jqInput"maxlength="20" >

					<Center><input type="submit" name="submit" value="Join" id="jqJoin" class="regButton"></Center>
				</form>
			</div>

		</div>
		<div id="colRight">

			<div id="welcomeMessage">Starting a quiz is fast, easy, and doesn't require registration!</div>
			<a href="startQuiz.php" id="startQuizButton" class="regButton">Start a Quiz!</a>
		</div>
	</div>


		<div id="pageFooter">
			Copyright &copy; 2016 GameOnWorld
		</div>
		
</div>
</body>
</html>
