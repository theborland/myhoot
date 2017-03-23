<html>
<head>

	<title>GameOn.World</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/joinQuiz.css">
	<link rel="stylesheet" href="style/content.css">
	<script src="http://gameon.world/AutobahnJS/build/autobahn.min.js"></script>
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
<div id="wholeWrap">
	<?php include_once("controller/analyticstracking.php") ?>
	<div id="headWrap">
		<div id="logoWrap">
			<a href="http://GameOn.World" id="logoLink">			<a href="http://GameOn.World" id="logoLink"><img src="img/logo.svg" id="logo"></a></a>
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
		<div id="pageHeader">About</div>

		<div id="bodyTextWrap">

			<h3>Welcome to GameOn.World!</h3>
			<p>
				This is the first draft (beta 1.0) of our website which we hope will be a fun, communal way to learn geography and other interesting trivia. The site was started by Jeff Borland, a teacher in Portland, Maine and one of his former students, Orkhan Nadirli. We believe that students need to know much more about the world and we thought that this game format would be a wonderful way to do that. In the coming months we hope to add lots of new features (like the ability to create your own trivia sets), so keep checking back. And if you have ideas or comments we would love to hear them.
				<br>
				Enjoy playing.
			</p>


			<h3>Who?</h3>
			<p>
				Really anyone.  When we thought of GameOn.World, we imagined teachers (especially Social Studies teachers) using it for practice geography.  But we hope you find it fun enough to use anytime you want.  Maybe the next get together you have you try it out - the more people the merrier.
			</p>

			<h3>Why?</h3>
			<p>Well, it's fun but hopefully it will teach you a bit about the world too.</p>

			<h3>Where?</h3>
			<p>
				It works great in a classroom with a projector (the person starting the quiz will use the projector) while everyone else joins on their mobile device.  But you can play GameOn.World anywhere you have an internet connect screen (so at a party on a TV?)
			</p>

			<h3>How?</h3>
			<p>
				The person at the big display will start a game.  They have an option of choosing categories and regions of the world.  They also can select autoplay (so the game just goes without user input) and also the number of rounds. The users on their mobile devices will join the game using the id and their choosen name.
			</p>
			<!--
			<h3>What?</h3>
			<p>
				A trivia game that encourages users to learn about the world.
			</p>
			-->
			<p><br></p>
			<p><br></p>

		</div>
			<p><br></p>

			<p><br></p>
			<p><br></p>
			<p><br></p>

	</div>

	<div id="pageFooter">
		Copyright &copy; 2016 GameOnWorld
	</div>

</div>

</body>
</html>
