<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>Start a Quiz</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/startQuiz.css">

	<script src="scripts/startQuiz.js"></script>

	<script>
		
		window.onload = function(){
			initChecks();

			document.getElementById('linkPlane').style.top="20px";
			var x=0;
			 var interval = setInterval(function() {
			     animatePlane(x);
			     x++;
			     if (x > 100000) clearInterval(interval);
			 }, 50);

		}

		function animatePlane(i){
			document.getElementById('linkPlane').style.top = ( 20 + Math.sin(i/10)*15 ) + "px";
			document.getElementById('joinHere').style.top = ( 20 + Math.sin((i-20)/10)*15 ) + "px";
			document.getElementById('bannerLink').style.top = ( 20 + Math.sin((i-10)/10)*15 ) + "px";
		}

	</script>

</head>
<body>

<div id="headWrap">

	<div id="logoWrap">
		<img src="img/logo.png" id="logo">
	</div><div id="subHeadWrap"></div>

</div>

<div id="bodyWrap">
	<div id="colsWrap">
		<div class="col" id="col1">



				<div id="gsWrap">
				<div class="sqLabel" id="sqGameTypes">GAME TYPES</div>
					<div class="gsItem" id="gs1">
						<img src="img/map.svg" class="gsImg" alt="">
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
					<div id="showMap" class="regButton" onclick="alert('sup')">Select Regions</div>
				</div>





		</div><div class="col" id="col2">
			<div class="sqLabel" id="sqQuizLabel">QUIZ ID</div>
			<div id="sqQuizID">
				<div id="quizID">
					22342
				</div>
			</div>

			<div class="sqLine" id="sqNumRounds">
				<label class="sqLabel" for="numRounds">NUMBER OF ROUNDS</label>
				<select id="numRounds" name="numRounds">
					<option value="2">2</option>
					<option value="10" selected>10</option>
					<option value="15">15</option>
					<option value="20">20</option>
					<option value="9999">infinite</option>
				</select>
			</div>

			<div class="sqLine" id="sqAutoplay">
				<label class="sqLabel" for="autoplayCB">AUTOPLAY</label>
				<input type="checkbox" id="autoplayCB" name="auto" value="yes">

			</div>

			<div class="sqLine" id="submitLine">
				<input type="submit" class="regButton" id="sqStart" value="Start">
			</div>


		</div><div class="col" id="col3">
			<div class="sqLabel" id="sqNumUsers"><div id="numUsers">0</div> USERS IN THE GAME</div>
				<div id="usersWrap">
					<div id="nameUsers">
						<div class="sqName" style="background:#38D38E;">John</div>
						<div class="sqName" style="background:#B15751;">Someone</div>
						<div class="sqName" style="background:#5291D6;">Else</div>
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

<div id="linkPlane"></div>
<div id="sqBackground"></div>
</body>
</html>