<?php
//die ("Tuesday noon: We are done for some upgrades - sorry, come back later (should be up in a couple of hours)");
 ?><html>
<head>

	<title>GameOn.World - Online Trivia Game</title>

	<meta name="description" content="GameOn.World is an online, educational trivia game for groups! Starting a game is fast, easy, and doesn't require registration." />

	<link rel="stylesheet" href="style/global.css?ver=1">
	<link rel="stylesheet" href="style/form.css">
	<link rel="stylesheet" href="style/content.css?ver=2">
	<script src="http://gameon.world/scripts/autobahn.min.js"></script>
	<script src="scripts/startQuiz.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js?ver=1"></script>



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
        <div class="menuItem"><a href="#" class="menuLink">
          <?php
                        include_once ("controller//dbsettings.php");
      $SQL="SELECT COUNT(id) FROM answers";

        $retid= $conn->query($SQL);
        $row = $retid->fetch_row();
        $num= "".$row[0];
            //$splitstring = str_split($num);
        for ($i=0; $i<strlen($num); $i++)
          echo "<font>$num[$i]</font>";
                        ?> <br>Answers Played
      </a>  </div>

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
<div id="jqWrap2">What's new
  <div class="newText">We have been busy at school (this site is made entirely by a teacher and some of his students), but things are winding down and we are starting to add some new features.
    We just added some number sense questions - you will see it as estimation.  </div>
</div>
		</div>


  </div>
		<div id="pageFooter">
			Copyright &copy; 2017 GameOnWorld
		</div>

</div>
</body>
</html>
<?php
//	<div id="extraMessage">All should be working now - we have spent a lot of time diagnosing and feel good about it.
	//		<br>If you have any issues (or ideas) - tell us <a href="contactUs.php">here</a>.<br>
//</div>
 ?>
