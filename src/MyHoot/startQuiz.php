<?php
session_start();
//$_SESSION = array();
//$_SESSION["auto"]="";
require 'controller/dbsettings.php';
//create game
Game::createGame();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>Start a Quiz</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/startQuiz.css">
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script src="scripts/startQuiz.js"></script>
	<script src="scripts/mapdata.js"></script>
	<script src="scripts/continentmap.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="scripts/cloud.js"></script>



	<script>
		var clouds={};
		var numClouds=1;
		var actClouds=1;

		window.onload = function(){
			initChecks();
			initRegions();


			//set up clouds
			var docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;
			clouds[0] = new Cloud(0, docHeight);

			//animation clock
			var x=0;
			var interval = setInterval(function() {
			     animatePlane(x);
			     animateClouds(x);

			     if(Math.random()<0.003){
			     	clouds[numClouds] =  new Cloud(numClouds, docHeight);
			     	numClouds++;
			     	actClouds++;
			     }
			     x++;
			}, 50);

			// check and set music
		    if (readCookie("playMusic")=="false"){
		    	muteOff();
			}

		}

		function animateClouds(x){
			for(var i=0; i<actClouds; i++){
			    clouds[i].animate(x);
			}
		}

		loadWaitingForUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>);

	</script>

</head>
<body>

<form action="getQuestion.php">



<div id="settingBlur"  onclick="hideSetting()">
	<div id="settingCloseButton" onclick="hideSetting()"></div>
</div>
<div id="settingWrap">



	<div class="settingsLineWrap" id="settingsLineWrap">
		<div class="settingsHL" id="settingsNumRounds">

			<label class="settingsLabel" for="numRounds">NUMBER OF ROUNDS</label>
			<select id="numRounds" name="numRounds">
				<option value="2">2</option>
				<option value="10" selected>10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="9999">infinite</option>
			</select>

		</div><div class="settingsHL" id="settingsAutoplay">

			<label class="settingsLabel" for="autoplayCB" checked>AUTOPLAY</label>
			<input type="checkbox" id="autoplayCB" name="auto" value="yes">

		</div>
	</div>


	<div class="settingsLineWrap" id="settingsSelectRegionsWrap">
		<div class="settingsHL" id="selectRegionsLabel">
			Select Regions
		</div><div id="statesWrap" class="settingsHL">
			<label for="statesCB" class="settingsLabel" id="statesLabel">US States</label>
			<input type="checkbox" id="statesCB" name="statesCB" value="statesCB" checked>
		</div>
	</div>

	<div id="map"></div>

	<div id="settingFootnote">
		<input type="button" id="selectAll" class="regButton" onclick="selectall()" value="deselect all">
		<div id="footnote">
			These selections apply to Geography, Population and Weather categories.
		</div>
	</div>
</div>





<div id="headWrap">
	<div id="logoWrap">
		<img src="img/logo.png" id="logo">
	</div>

</div>

<div id="bodyWrap">
	<div id="colsWrap">
		<div class="col" id="col1">

			<div id="gsWrap">
			<div class="sqLabel" id="sqGameTypes">GAME TYPES</div>
				<div class="gsItem" id="gs1">
					<img src="img/map.svg" class="gsImg gsSel" alt="">
					<div class="gsName">GEOGRAPHY</div>
				</div>
				<div class="gsItem" id="gs2">
					<img src="img/population.svg" class="gsImg" alt="">
					<div class="gsName">POPULATIONS</div>
				</div>
				<div class="gsItem" id="gs3">
					<img src="img/temp.svg" class="gsImg" alt="">
					<div class="gsName">WEATHER</div>
				</div>
				<div class="gsItem" id="gs4">
					<img src="img/star.svg" class="gsImg" alt="">
					<div class="gsName">CELEBRITY AGES</div>
				</div>
				<div class="gsItem" id="gs5">
					<img src="img/history.svg" class="gsImg" alt="">
					<div class="gsName">HISTORY</div>
				</div>
	 			<div class="gsItem" id="gs6">
					<img src="img/temp.svg" class="gsImg" alt="">
					<div class="gsName">RANDOM</div>
				</div>
				<!--<div id="showMap" class="regButton" onclick="alert('sup')">Select Regions</div>-->
			</div>

		</div><div class="col" id="col2">
			<div class="sqLabel" id="sqQuizLabel">QUIZ ID</div>
			<div id="sqQuizID">
				<div id="quizID">
						<?php echo $_SESSION["game_id"] ; ?>
				</div>
			</div>
			<div class="sqLine" id="submitLine">
				<div id="sqSettingsButton" onclick="showSetting()"></div>
				<input type="submit" class="regButton" id="sqStart" value="Start">


					<input type="hidden" name="gsGeo" id="gsGeo" value="true">
					<input type="hidden" name="gsAge" id="gsAge" value="false">
					<input type="hidden" name="gsHist" id="gsHist" value="false">
					<input type="hidden" name="gsPop" id="gsPop" value="false">
					<input type="hidden" name="gsTemp" id="gsTemp" value="false">
   					<input type="hidden" name="gsRand" id="gsRand" value="false">
					<!--regions-->
					<input type="hidden" name="r_SA" id="sm_state_SA" value="true">
					<input type="hidden" name="r_NA" id="sm_state_NA" value="true">
					<input type="hidden" name="r_EU" id="sm_state_EU" value="true">
					<input type="hidden" name="r_AF" id="sm_state_AF" value="true">
					<input type="hidden" name="r_NS" id="sm_state_NS" value="true">
					<input type="hidden" name="r_SS" id="sm_state_SS" value="true">
					<input type="hidden" name="r_ME" id="sm_state_ME" value="true">
					<input type="hidden" name="r_OC" id="sm_state_OC" value="true">

			</div>


		</div><div class="col" id="col3">
			<div class="sqLabel" id="sqNumUsers"><div id="numUsers">0</div> USERS IN THE GAME</div>
				<div id="usersWrap">
					<div id="nameUsers" class="scrollable">
					</div>
				</div>

		</div>
	</div>
</div>

<div id="bannerWrap">
	<div id="joinHere" class="banner"> join at
	</div><div id="bannerLink" class="banner">
		MyOnlineGrades.com</div>
</div>


<audio id="bgMusic" autoplay enablejavascript="yes" loop="yes">
  <source src="music/title.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>

<!--
<div class="cloud" id="cloud1"></div>
<div class="cloud" id="cloud2"></div>
<div class="cloud" id="cloud3"></div>
-->
<div id="clouds">

</div>

<input type="button" id="muteButton" onclick="mute()">
<div id="sun"></div>
<div id="linkPlane"></div>
<div id="sqBackground"></div></form>
</body>
</html>
