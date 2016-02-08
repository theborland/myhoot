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

	<script src="mapdata.js"></script>
	<script src="continentmap.js"></script>

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
		z-index: 9;
		box-shadow: 0px 0px 10px rgba(0,0,0,.3);
	}
	.gsItem:hover{

		background: rgba(0,0,0,.7);
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
	#joinHere{
		display: block;
		width: 650px;
	    padding: 20px 40px;
	    margin: 20px auto;
	    background: rgba(0,0,0,.5);
	    border: 0px;
	    border-radius: 10px;
	    box-shadow: 0px 0px 50px rgba(0,0,0,.5);
	    color:#fff;
	    font-size:45px;
	    font-weight: 100;
	    text-align: center;
	}

	#joinHere #link{
		display: inline-block;
		font-weight: 500;
		padding: 0px 20px;
		background: rgba(0,0,0,0);
	}

	#showMap{
		display: inline-block;
		border:0px solid #fff;
		padding:4px 15px;
		margin-top: 5px;
		border-radius: 5px;
		color:#fff;
		background:rgba(0,0,0,.5);
		cursor:pointer;
		transition:all .1s;
	}
	#showMap:hover{
		background:rgba(0,0,0,.7);
	}


	#mapWrap{
		position: fixed;
		top:-500px;
		left: calc(50% - 350px);
		width: 700;
		box-sizing:border-box;
		padding:25px 50px;
		display: block;
		background:rgba(0,0,0,.8);
		z-index: 11;
		transition:.5s all;
		box-shadow: 0px 0px 30px rgba(0,0,0,.5);
		border-radius: 10px;
		border-top-right-radius: 0px;
		border-top-left-radius: 0px;
		display: block;

	}

	#mapBlur{
		position: fixed;
		background:rgba(0,0,0,.5);
		top:0px;
		bottom: 0px;
		left: 0px;
		right: 0px;
		display: none;
		z-index: 10;
		opacity: 0;
		transition:1s all;
	}
	#closeMap{
		position:absolute;
		display: inline-block;
		top:10px;
		right: 10px;
		color:#fff;
		border-radius: 30px;
		width: 25px;
		height: 25px;
		text-align: center;
		transition:all .1s;
		cursor:pointer;
	}
	#closeMap:hover{
		background:rgba(255,255,255,.1);
	}
	#mapFootnote{
		color:rgba(255,255,255,.4);
		text-align: center;
		font-size: 14px;
	}
	#mapWrap h1{
		font-size:30px;
		font-weight: 30px;
		color:rgba(255,255,255,.5);
		font-weight: 300;
		font-size: 28px;
		margin:0px;
	}
	</style>

 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
var mapSet = false;


function showmap(){
	if(mapSet==false){

		mapSet = true;
	}
	document.getElementById("mapBlur").style.display = "block";
	document.getElementById("mapBlur").style.opacity = "1";
	document.getElementById("mapWrap").style.top = 0;

}

function hidemap(){
	document.getElementById("mapBlur").style.display = "none";
	document.getElementById("mapWrap").style.top = -500;

}


window.onload = function() {
	var games = ['gsGeo', 'gsPop', 'gsTemp', 'gsAge', 'gsHist', 'gsRand'];

    for(var i = 0; i < 6; i++) {
        var gs = document.getElementById("gs"+(i+1));
       var c = gs.className;
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


            }else{
            	this.classList.add("gsSel");
            	check.style.display = "block";
            	document.getElementById(name).value = "true";

            }
        }
    }
}

 loadWaitingForUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>);

</script>
 </head>
 <body>
<div id="mapBlur"  onclick="hidemap()"></div>

<div id="mapWrap">
	<h1>Select Regions</h1>
	<div id="closeMap" onclick="hidemap()">X</div>
	<div id="map"></div>
	<div id="mapFootnote">These selections apply to Geography, Population and Weather categories.</div>
</div>

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
						<img src="img/population.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">POPULATIONS</div>
					</div>
					<div class="gsItem" id="gs3">
						<img src="img/temp.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">WEATHER</div>
					</div>
					<div class="gsItem" id="gs4">
						<img src="img/star.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">CELEBRITY AGES</div>
					</div>
					<div class="gsItem" id="gs5">
						<img src="img/history.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">HISTORY</div>
					</div>
         			<div class="gsItem" id="gs6">
						<img src="img/temp.png" class="gsImg" alt="">
						<div class="gsCheck"></div>
						<div class="gsName">RANDOM</div>
					</div>
					<div id="showMap" onclick="showmap()">Select Regions</div>
				</div>
			</div>
			<div id="col3">
				<label class="jqLabel" style="margin-left:50px;">QUIZ ID</label>
				<div id="quizidWrap">
					<div id="quizid">
						<?php echo $_SESSION["game_id"] ; ?>
					</div>
				</div>
				<br><Br>
				<form action="getQuestion.php">
					<input type="hidden" name="gsGeo" id="gsGeo" value="true">
					<input type="hidden" name="gsAge" id="gsAge" value="false">
					<input type="hidden" name="gsHist" id="gsHist" value="false">
					<input type="hidden" name="gsPop" id="gsPop" value="false">
					<input type="hidden" name="gsTemp" id="gsTemp" value="false">
          <input type="hidden" name="gsRand" id="gsRand" value="false">
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
	<div id="joinHere">
		Join at <div id="link">myonlinegrades.com/j</div>
	</div>
	<div id="footer">
		Copyright and stuff.
	</div>

 </body>
 </html>
