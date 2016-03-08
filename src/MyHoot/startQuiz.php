<?php
session_start();
//$_SESSION = array();
//$_SESSION["auto"]="";
require 'dbsettings.php';
//create game
Game::createGame();

//die ($_SESSION["auto"]);
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
		background: red; /* For browsers that do not support gradients */
		background: -webkit-linear-gradient(top, #328DE5 , #7EC7E5); /* For Safari 5.1 to 6.0 */
		background: -o-linear-gradient(top, #328DE5, #7EC7E5); /* For Opera 11.1 to 12.0 */
		background: -moz-linear-gradient(top, #328DE5, #7EC7E5); /* For Firefox 3.6 to 15 */
		background: linear-gradient(top, #328DE5 , #7EC7E5); /* Standard syntax */


		padding:0px;
		padding-top: 5%;
	}
	#jqWrap{
		width:80%;
		border:0px;
		box-shadow: 0px 0px 0px;
		background: transparent;

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
		text-align: left;
	}
	#jqWrap #quizid{
		display: inline-block;
		text-align: left;
		font-size:50px;
		font-weight: 300;
		color:#fff;
		padding:5px 30px;
		border:0px solid #fff;
		border-radius: 5px;
		background:rgba(0,0,0,.3);
	}

	input[type="checkbox"]{
		-webkit-appearance:none;
		width: 20px;
		height: 20px;
		border:1px solid #fff;
		background:rgba(0,0,0,0);
		border-radius: 0px;
		cursor: pointer;
		position: relative;
		top:5px;
		box-sizing:border-box;
	}
	input[type='checkbox']:hover {
		background:rgba(0,0,0,0.1);
	}
	input[type='checkbox']:checked {
		border:1px solid #fff;
		outline: 0px solid #fff;
		background:url('img/check1.png');
		background-size: contain;
	}
	input[type='checkbox']:focus {
		outline:0px;
	}

	#numRounds{
		margin-left: 10px;
		margin-top: -5px;
		vertical-align: middle;
	}

	#jqJoin{
		position: relative;
		vertical-align: middle;
	}
	#usersWrap{
		border:0px solid #fff;
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
		width: 32%;
		text-align: left;
	}
	#col2{
		display: inline-block;
		width: 32%;
		padding-left:20px;
		vertical-align: top;
		border:0px solid red;
		text-align: left;
	}
	#col3{
		display: inline-block;
		width: 32%;
		padding-left:0px;
		vertical-align: top;
		text-align: left;
	}

	#gsWrap{
		width: 300px;
		border: 0px solid #fff;
		text-align: center;
		padding:2px;
		margin-top:10px;
		margin:10px auto 0px;
	}
	.gsItem{
		width: 88px;
		height: 105px;
		border:3px solid rgba(255,255,255,0);
		box-sizing:border-box;
		margin:3px 2px;
		display: inline-block;
		cursor:pointer;
		position: relative;
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
	    padding: 40px 40px 20px;
	    margin: 20px auto 0px;
	    background: #008E4C;
	    border: 0px;
	    border-radius: 0px;
	    box-shadow: 0px 0px 50px rgba(0,0,0,0);
	    color:#fff;
	    font-size:45px;
	    font-weight: 100;
	    text-align: center;
	    position: fixed;
	    right: 0px;
	    bottom: 0px;
	    left: 0px;
	    height: 130px;
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
		padding:6px 15px 4px;
		margin-top: 5px;
		border-radius: 5px;
		color:#fff;
		background:rgba(0,0,0,.5);
		cursor:pointer;
		transition:all .1s;
		text-transform: uppercase;
		font-size: 13px;
	}
	#showMap:hover{
		background:rgba(0,0,0,.7);
	}


	#mapWrap{
		position: fixed;
		top:-600px;
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
		transition:.5s all;
	}
	#closeMap{
		display: inline-block;
		color:#fff;
		border-radius: 10px;
		padding:10px 20px;
		text-align: center;
		transition:all .1s;
		cursor:pointer;
		float: right;
		margin-top:-20px;
		font-size: 19px;
		background: #F76116;
		border-bottom: 3px solid #BB3B08;

	}
	#closeMap:hover{
		background: #F77C27;
	}
	#mapFootnote{
		color:rgba(255,255,255,.3);
		text-align: left;
		font-size: 14px;
		padding-top:20px;
		position: relative;
	}
	#mapWrap h1{
		font-size:30px;
		font-weight: 30px;
		color:rgba(255,255,255,.5);
		font-weight: 300;
		font-size: 28px;
		margin:0px;
	}
	#map path{
		fill: rgba(255,255,255,.0);
		transition:.2s all;
	}
	#map path:hover{
		fill: rgba(255, 255, 255,1);
	}
	#map .pathSelected{
		fill: rgba(13, 206, 84,1);
		box-shadow:0px 0px 10px red;
	}
	#tt_sm{
		display: none !important;
	}

	#statesWrap{
		float: right;
		display: inline-block;
	}
	#muteButton{
		background:url('img/mute1.png');
		border:0px;
		position: fixed;
		bottom: 10px;
		right: 10px;
		width: 40px;
		height: 40px;
		outline:0px;
		cursor:pointer;
		z-index:10;
	}
	#muteButton:focus{
		outline:0px;
		border:0px;
	}
	#selectAll{
		width: 30px;
		height: 30px;
		background:url('img/uncheckall.png');
		background-size: contain;
		border:0px;
		outline:0px;
		cursor:pointer;
		position: absolute;
		top:-50px;
		left: 10px;
	}
	#selectAll:focus{
		outline:0px;
		border:0px;
	}

	</style>

 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
var playing = true;
var selected = 8;
var continents = ['sm_state_SA', 'sm_state_NA', 'sm_state_EU', 'sm_state_AF', 'sm_state_NS','sm_state_SS','sm_state_ME','sm_state_OC'];

function showmap(){
	document.getElementById("mapBlur").style.display = "block";
	document.getElementById("mapBlur").style.opacity = "1";
	document.getElementById("mapWrap").style.top = 0;

}

function hidemap(){
	document.getElementById("mapBlur").style.display = "none";
	document.getElementById("mapWrap").style.top = -500;
}

function mute(){
	if(playing==true)
		muteOff();
	else
		muteOn();
}

function muteOn(){
	var music = document.getElementById("bgMusic");
	var button = document.getElementById("muteButton");
	music.volume = 1;
	playing = true;
	button.style.backgroundImage = "url(img/mute1.png)";
	document.cookie="playMusic=true";
}

function muteOff(){
	var music = document.getElementById("bgMusic");
	var button = document.getElementById("muteButton");
	music.volume = 0;
	playing = false;
	button.style.backgroundImage = "url(img/mute2.png)";
    document.cookie="playMusic=false";
}

function selectall(){
    for(var i = 0; i < continents.length; i++) {
        var gs = document.getElementsByClassName(continents[i]);
        if(((String)(gs[0].classList)).indexOf("pathSelected") >= 0 && selected>4){
        	gs[0].classList.remove("pathSelected");
        	document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "false";
        }else if(((String)(gs[0].classList)).indexOf("pathSelected") < 0 && selected <= 4){
        	gs[0].classList.add("pathSelected");
        	document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "true";
        }
    }

    if(selected>4){
    	selected = 0;
    }else{
    	selected = 8;
    }

    document.getElementById('statesCB').checked = !document.getElementById('statesCB').checked;

    animateSelectAll();
}

function animateSelectAll(){
    if(selected>4){
    	document.getElementById('selectAll').style.backgroundImage = "url('img/uncheckall.png')";
    }else{
    	document.getElementById('selectAll').style.backgroundImage = "url('img/checkall.png')";
    }

}

window.onload = function() {
	//alert($("#qInfoLocation").height());
    if (readCookie("playMusic")=="false"){
    	muteOff();
	}

  //set originally on restart
  <?php
  $games = array('gsGeo', 'gsPop', 'gsTemp', 'gsAge', 'gsHist', 'gsRand');
  $match=array('geo','pop','weather','age','time','rand');
  for ($i=0;$i<6;$i++)
      if (isset($_SESSION["gamesSelected"]) && in_array($match[$i],$_SESSION["gamesSelected"]) ||
         ($i==0 && !isset($_SESSION["gamesSelected"]))
      ){
  ?>
        var check="<?php echo $games[$i] ?>";
        var name="<?php echo "gs".($i+1); ?>";
        var cs = document.getElementById(name).children;
        var child = null;
        for(i=0; i<cs.length; i++){
          if(cs[i].className == "gsCheck"){
            child = cs[i];
          }
        }
        child.style.display = "block";
        document.getElementById(name).classList.add("gsSel");
        document.getElementById(check).value = "true";
  <?php
      }
      // now autoplay

      if (!isset($_SESSION["auto"]) || $_SESSION["auto"]=="yes")
          echo 'document.getElementById("autoplayCB").checked = "true";'."\n";
      if (isset($_SESSION["numRounds"]))
          echo "var rounds=".$_SESSION["numRounds"].";\n";
      else
          echo "var rounds=15;\n";
          ?>
      var allRounds = document.getElementById("numRounds");
      for (i = 0; i < allRounds.options.length; i++) {
          if  (allRounds.options[i].value==rounds){
             allRounds.selectedIndex=i;
           }
      }


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


    /*  ----   ---- */



    setTimeout(function(){
        for(var i = 0; i < continents.length; i++) {
        var gs = document.getElementsByClassName(continents[i]);
       //var c = gs.className;
       //alert(gs.className);
       	gs[0].classList.add("pathSelected");
        gs[0].onclick = function() {
            if(((String)(this.classList)).indexOf("pathSelected") >= 0){
            	this.classList.remove("pathSelected");
            	document.getElementById(((String)(this.classList)).split(" ")[0]).value = "false";
            	selected--;
            }else{
            	this.classList.add("pathSelected");
            	document.getElementById(((String)(this.classList)).split(" ")[0]).value = "true";
				selected++;
            }
            animateSelectAll();
        }
    }

    }, 100);



}

 loadWaitingForUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>);

</script>
 </head>
 <body>

<audio id="bgMusic" autoplay loop enablejavascript="yes">
  <source src="title.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>

<input type="button" id="muteButton" onclick="mute()">

<form action="getQuestion.php">
<div id="mapBlur"  onclick="hidemap()"></div>
<div id="mapWrap">
	<h1>
		Select Regions
		<div id="statesWrap">
			<label for="statesCB" class="jqLabel" style="display:inline-block; margin-right:10px; margin: 0px 0px 0px 0px;font-size:18px;">US STATES</label>
			<input type="checkbox" id="statesCB" name="statesCB" value="statesCB" checked>
		</div>
	</h1>

	<div id="map"></div>

	<div id="mapFootnote">
		<input type="button" id="selectAll" onclick="selectall()">
		These selections apply to Geography, Population and Weather categories.
		<div id="closeMap" onclick="hidemap()">Done</div>
	</div>
</div>

	<div id="jqWrap" method="GET">
		<img src="logo.png" id="logo">
		<h4>Starting a Quiz</h4>

		<div id="colsWrap">
			<div id="col1">
				<label class="jqLabel"><div id="numUsers" style="display:inline-block;">0</div> USERS IN THE GAME</label>
				<div id="usersWrap">
					<div id="nameUsers">
					</div>
				</div>

			</div>
			<div id="col2">
				<div id="gsWrap">
				<label class="jqLabel" style="margin-left:10px;text-align: left;">GAME TYPES</label>
					<div class="gsItem" id="gs1">
						<img src="img/map.png" class="gsImg" alt="">
						<div class="gsCheck" style="display:none;"></div>
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
				<label class="jqLabel">QUIZ ID</label>
				<div id="quizidWrap">
					<div id="quizid">
						<?php echo $_SESSION["game_id"] ; ?>
					</div>
				</div>
				<br>

					<input type="hidden" name="gsGeo" id="gsGeo" value="false">
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

						<label for="numRounds" class="jqLabel" style="display:inline-block; margin-right:10px;position:relative; top:0px;margin-bottom: 15px;margin-top:10px;">
							NUMBER OF ROUNDS
							<select id="numRounds" name="numRounds">
	              <option value="2">2</option>
              	<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="9999">infinite</option>
							</select>
						</label>
						<label for="autoplayCB" class="jqLabel" style="display:inline-block; margin-right:10px;position:relative; top:7px;">
							AUTOPLAY
							<input type="checkbox" id="autoplayCB" name="auto" value="yes">
						</label>
						<input type="submit" id="jqJoin" value="Start">
			</div>
		</div>
	</form>



	</div>
	<div id="joinHere">
		Join at <div id="link">myonlinegrades.com/j</div>
	</div>
	<div id="footer">
		Copyright and stuff.
	</div>

 </body>
 </html>
