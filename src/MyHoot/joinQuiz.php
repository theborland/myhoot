<?php

session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["game_id"]) && $_SESSION["game_id"]>0 && $_SESSION["user_id"]>0){
		$ref=$_SERVER['HTTP_REFERER'];
	  if (strpos($ref,"wait")!==false || strpos($ref,"submitAns")!==false || strpos($ref,"user")!==false){
			if (strpos($ref,"End")==false)
				header( 'Location: checkQuestion.php');
		}

}
$whitelist = array('error','name','game_id');
require 'controller/dbsettings.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/joinQuiz.css">
  <title>Join a Game</title>
</head>
<body>
	<?php include_once("controller/analyticstracking.php") ?>
	<div id="jqWrap">
					<a href="http://GameOn.World" id="logoLink"><img src="img/logo.svg" id="logo"></a>
		<h4 class="formHeader">Join a Game</h4>
		<form action="waitingScreen.php">
			<label for="game_id" class="jqLabel"> GAME ID <?php if ($error=="Bad Game") echo " (Game ID is not valid)"; ?></label>
			<input type="number" pattern="[0-9]*" name="game_id" id="game_id" class="jqInput" value="<?php echo $game_id ?>"  min="00000" max="99999">

			<label for="name" class="jqLabel">YOUR NAME<?php
			if ($error=="Bad Name") echo " (That name has been used)";
			if ($error=="Reject") echo " (Really, come on.  Grow up.)";
			 ?></label>
			<input type="text" name="name" id="name" value="<?php echo $name ?>" class="jqInput"maxlength="20" >

			<Center>
				<input type="submit" name="submit" value="Join" id="jqJoin" class="regButton">
				<label class="jqLabel" id="orLabel">OR</label>
				<a href="startQuiz.php" class="regButton" id="startGame">Start a Game</a>
			</Center>

		</form>
	</div>
	<div id="footer">
		Copyright and stuff.
	</div>


</body>
</html>
