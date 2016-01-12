<?php
session_start();
$_SESSION = array();
$_SESSION["auto"]="";
require 'dbsettings.php';
//create game
Game::createGame();
 ?>
 <html>
 <head>
 	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/joinQuiz.css">
    <style>
	body{
		background: url('paris.jpeg');
		background-size: cover;
		background-repeat: no-repeat;
		padding:0px;
		padding-top: 5%;
}
	#jqWrap{
		width:900px;
	}
	#jqWrap h4{
		font-size:30;
		margin-top:38px;
		margin-bottom:20px;
	}
	#jqWrap #logo{
		width:160px;
	}
	#jqWrap .jqLabel{
		font-size:18px;
	}
	#jqWrap #quizidWrap{
		display: block;
		text-align: center;
	}
	#jqWrap #quizid{
		display: inline-block;
		text-align: left;
		font-size:50px;
		font-weight: 300;
		color:#fff;
		padding:5px 30px;
		border:1px solid #fff;
		border-radius: 5px;
		background:rgba(0,0,0,.3);
	}

	input[type="checkbox"]{
		-webkit-appearance:none;
		width: 18px;
		height: 18px;
		border:3px solid rgba(0,0,0,0);
		outline: 1px solid #fff;
		background:rgba(0,0,0,0);
		border-radius: 0px;
		cursor: pointer;
		position: relative;
		top:5px;
		-moz-background-clip: padding;     /* Firefox 3.6 */
		-webkit-background-clip: padding;  /* Safari 4? Chrome 6? */
		background-clip: padding-box;      /* Firefox 4, Safari 5, Opera 10, IE 9 */
	}
	input[type='checkbox']:checked {
		border:3px solid rgba(0,0,0,0);
		outline: 1px solid #fff;
		background:#fff;
		-moz-background-clip: padding;     /* Firefox 3.6 */
		-webkit-background-clip: padding;  /* Safari 4? Chrome 6? */
		background-clip: padding-box;      /* Firefox 4, Safari 5, Opera 10, IE 9 */
	}
	#jqJoin{
		position: relative;
		vertical-align: middle;
	}
	#usersWrap{
		border:1px solid #fff;
		border-radius: 5px;
		background:rgba(0,0,0,.3);
		margin-bottom: 20px;
		padding:7px 5px 7px 0px;
	}
	#nameUsers{
		min-height: 200px;
		max-height: 270px;
		overflow-y:auto;
		font-size: 18px;
		font-weight: 300;
		color:#fff;
		padding:10px 20px;
	}

	#nameUsers::-webkit-scrollbar {
		width: 10px;
	}

	#nameUsers::-webkit-scrollbar-track {
		display: none;
	}


	#nameUsers::-webkit-scrollbar-thumb {
		border-radius: 2px;
		background:rgba(255,255,255,.4);
		cursor: pointer;
	}


	#colsWrap{
		border-top:1px solid rgba(255,255,255,.5);
		padding-top:20px;
	}
	#col1{
		display: inline-block;
		width: 290px;
	}
	#col2{
		display: inline-block;
		width: 290px;
		padding-left:20px;
		vertical-align: top;
		border:0px solid red;
	}
	#col3{
		display: inline-block;
		width: 290px;
		padding-left:0px;
		vertical-align: top;
	}

	#gsWrap{
		width: 280px;
		border: 0px solid #fff;
		text-align: left;
		padding:2px;
		margin-top:10px;
	}
	.gsItem{
		width: 88px;
		height: 105px;
		border:3px solid rgba(255,255,255,0);
		box-sizing:border-box;
		margin:2px;
		display: inline-block;
		cursor:pointer;
		position: relative;
		float: left;
		background-position:  center 10px;
		text-align: center;
		background: rgba(0,0,0,.5);
		transition:all .1s;
		border-radius: 2px;
		box-shadow: 0px 0px 10px rgba(0,0,0,.3);
	}
	.gsItem:hover{
		border:3px solid rgba(255,255,255,.3);
		box-shadow: 0px 0px 10px rgba(0,0,0,.5);
	}
	.gsSel{
		border:3px solid #0DCE54;	
		background: rgba(0,0,0,.6);
	}
	.gsSel:hover{
		border:3px solid #0DF262;	
	}
	.gsName{
		position: absolute;
		bottom: 3px;
		display: inline-block;
		color:#fff;
		font-size:9px;
		right: 0px;
		left: 0px;
		text-align: center;
		font-weight: 500;
	}
	.gsCheck{
		position: absolute;
		top:-3px;
		right: -3px;
		width: 20px;
		height: 20px;
		background: url('img/check1.png');
		background-size: cover;
		display: none;
	}
	.gsItem:hover .gsCheck{
		background: url('img/check2.png');
		background-size: cover;
	}
	</style>

 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>);

window.onload = function() {
	var games = ['gsGeo', 'gsAge', 'gsHist', 'gsPop', 'gsTemp'];

    for(var i = 0; i < 5; i++) {
        var gs = document.getElementById("gs"+(i+1));
        //var c = gs.className;
        gs.onclick = function() {
        	var name = games[parseInt(this.id.charAt(this.id.length-1)) - 1];
        	var cs = this.children;
        	var check = null;
        	for(i=0; i<cs.length; i++){
        		if(cs[i].className == "gsCheck"){
        			check = cs[i];
        		}
        	}

            if(this.className.indexOf("gsSel") >= 0){
            	this.classList.remove("gsSel");
            	check.style.display = "none";
            	document.getElementById(name).value = "false";
            	alert(document.getElementById(name).value);

            }else{
            	this.classList.add("gsSel");
            	check.style.display = "block";
            	document.getElementById(name).value = "true";
            	alert(document.getElementById(name).value);
            }
        }
    }
}

</script>
 </head>
 <body>


	<div id="jqWrap" method="GET">
		<img src="logo.png" id="logo">
		<h4>Creating a Quiz</h4>

		<div id="colsWrap">
			<div id="col1">
				<label class="jqLabel"><div id="numUsers" style="display:inline-block;">0</div> USERS IN THE GAME</label>
				<div id="usersWrap">
					<div id="nameUsers">
					</div>
				</div>

			</div>
			<div id="col2">
				<label class="jqLabel" style="margin-left:10px;">GAME TYPES</label>
				<div id="gsWrap">
					<div class="gsItem gsSel" id="gs1">
						<img src="img/map.png" class="gsImg" alt="">
						<div class="gsCheck" style="display:block;"></div>
						<div class="gsName">GEOGRAPHY</div>
					</div>
					<div class="gsItem" id="gs2">
						<img src="img/star.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">CELEBRITY AGES</div>
					</div>
					<div class="gsItem" id="gs3">
						<img src="img/history.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">HISTORY</div>
					</div>
					<div class="gsItem" id="gs4">
						<img src="img/population.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">POPULATIONS</div>
					</div>
					<div class="gsItem" id="gs5">		
						<img src="img/temp.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">WEATHER</div>
					</div>
				</div>
			</div>
			<div id="col3">
				<label class="jqLabel" style="margin-left:50px;">QUIZ ID</label>
				<div id="quizidWrap">
					<div id="quizid">
						33149<?php echo $_SESSION["game_id"] ; ?>
					</div>
				</div>
				<br><Br>
				<form action="getQuestion.php">
					<input type="hidden" name="gsGeo" id="gsGeo" value="true">
					<input type="hidden" name="gsAge" id="gsAge" value="false">
					<input type="hidden" name="gsHist" id="gsHist" value="false">
					<input type="hidden" name="gsPop" id="gsPop" value="false">
					<input type="hidden" name="gsTemp" id="gsTemp" value="false">
					<center >
						<label for="autoplayCB" class="jqLabel" style="display:inline-block; margin-right:10px;position:relative; top:7px;">
							AUTOPLAY
							<input type="checkbox" id="autoplayCB" name="auto" value="yes"></label>
						<input type="submit" id="jqJoin" value="Start">
					</Center>
				</form>
			</div>
		</div>




	</div>
	<div id="footer">
		Copyright and stuff.
	</div>

 </body>
 </html>
