<?php
session_start();
//echo $_SESSION["questionNumber"];
$whitelist = array('statesCB','numRounds','gsGeo','gsAge','gsHist','gsPop','gsTemp','gsRand','r_SA','r_EU','r_AF','r_NS','r_SS','r_ME','r_OC','r_NA');
require 'dbsettings.php';
//echo print_r($_GET["games"]);
if ($gsGeo=="false" || $gsGeo=="true")
{
  //die ($r_OC);
  $gamesSelected=array();
  $regionsSelected=array();
  $_SESSION["playedGames"]=array();
  if ($r_SA=="true"){$regionsSelected[]=2;$regionsSelected[]=3;$regionsSelected[]=4;}
  if ($r_NA=="true"){$regionsSelected[]=1;}
  if ($r_EU=="true"){$regionsSelected[]=6;$regionsSelected[]=5;}
  if ($r_AF=="true"){$regionsSelected[]=8;$regionsSelected[]=13;}
  if ($r_NS=="true"){$regionsSelected[]=11;}
  if ($r_ME=="true"){$regionsSelected[]=7;}
  if ($r_SS=="true"){$regionsSelected[]=9;}
  if ($r_OC=="true"){$regionsSelected[]=10;}
  if ($statesCB=="statesCB" && $r_NA=="true"){$regionsSelected[]=20;}
  if ($statesCB=="statesCB" && $r_NA=="false" && sizeof($regionsSelected)==0){$regionsSelected[]=20;}
  if ($gsAge=="true")$gamesSelected[]="age";
  if ($gsHist=="true")$gamesSelected[]="time";
  if ($gsTemp=="true")$gamesSelected[]="weather";
  if ($gsPop=="true")$gamesSelected[]="pop";
  if ($gsRand=="true")$gamesSelected[]="rand";
  if ($gsGeo=="true"){
    foreach ($gamesSelected as $key)
        if (sizeof($gamesSelected)<=7)
            $gamesSelected[]="geo";
    if (sizeof($gamesSelected)==0)
       $gamesSelected[]="geo";
  }
  //die (print_r($regionsSelected));
  $_SESSION["gamesSelected"]=$gamesSelected;
  $_SESSION["regionsSelected"]=$regionsSelected;
  $_SESSION["numRounds"]=$numRounds;
}
if (isset($_GET["auto"]))
    $_SESSION["auto"]=$_GET["auto"];
else
    $_SESSION["auto"]="";
if (isset($_GET["type"]))
            $_SESSION["type"]=$_GET["type"];
//die();
//die ($_SESSION["questionNumber"]);
if ($_SESSION["questionNumber"]>=$_SESSION["numRounds"]){
    //die ($_SESSION["questionNumber"]);
    echo $_SESSION["questionNumber"];
//die();
    header( 'Location: endScreen.php') ;
    //die();
}
else {
    $gamesSelected=$_SESSION["gamesSelected"];
    //print_r($gamesSelected);
    $playedGames=$_SESSION["playedGames"];
    if (sizeof($playedGames)==0)
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    else {
        $idealArray =array_count_values($gamesSelected);
        $currentArray =array_count_values($playedGames);
        //print_r($currentArray);
        //print_r($idealArray);
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
        while ($currentArray[$current]/sizeof($playedGames)>$idealArray[$current]/sizeof($gamesSelected))
                $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
        //echo "here";
    }
    $theQuestion=new Question($current);
    $playedGames[]=$current;
    $_SESSION["playedGames"]=$playedGames;
    //print_r($playedGames);
  }


?>

<html>
<head>
    <link rel="stylesheet" href="style/global.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.color-animation/1/mainfile"></script>
<script>
var counter = 30;
$(document).ready(function(){
$('#qTimer').animate({
	left: "+=50%",
	right: "+=50%",
	backgroundColor: "#ff0000"
}, 30000, "linear");

})




var interval = setInterval(function() {
    counter--;
  $('#timeLeft').html(counter);
    if (counter == 0) {
        window.location.replace("showAnswer.php");
    }
}, 1000);

</script>
<style>
	html{
		background:#000;
	}
	body{
		background: url("<?php echo $theQuestion->getImage() ?>");
        background-position:center center;
	<?php if($current == "age"){ ?>
		background-size: contain;
	<?php }else{ ?>
		background-size: cover;
	<?php } ?>
		background-repeat: no-repeat;
	}
	#overlayWrap{
		height: 130px;
		text-align: left;
		box-shadow: 0px;
	}

	#overlayWrap #logo{
		width: 150px;
		top:20px;
		left: 20px;
	}
	#overlayWrap #userMapSubmit{
		top:34px;
		right: 20px;
	}

	#qInfoWrap{
		display: inline-block;
		padding: 20px 10px;
		margin-left:170px;
	}

	.qInfoBlock{
		display: inline-block;
		border:0px;
		border-right: 1px solid rgba(255, 255, 255, .7);
		padding:5px 30px;
	}
	.qInfoBlock:last-child{
		border-right: 0px;
	}

	.qInfoLabel{
		font-weight: 500;
		font-size:20px;
		color:rgba(255, 255, 255, .7);
		text-align: left;
		display: block;
	}
	.qInfoMain{
		font-size: 35px;
		font-size: 2.5vw;
		font-weight: 100;
		text-align: center;
	}

	#qInfoLocation .qInfoMain{
		text-align: left;

	}
	#qInfoLocation{
				position: absolute;
		top:10px;
		right: 200px;
		left: 330px;

	}

	#qTimer{
		position: fixed;
		top: 130px;
		height: 15px;
		background-color: #66ff66;
		left:0px;
		right: 0px;
		z-index: 4;
	}
	#qTimerBG{
		position: fixed;
		top: 130px;
		height: 15px;
		background: rgba(0,0,0,.5);
		left:0px;
		right: 0px;
		z-index: 3;
		box-shadow: 0px 0px 20px rgba(0,0,0,.5);
		text-align: center;
	}

	#qAnswersWrap{
		font-size: 16px;
		font-weight: 300;
		color: rgba(255,255,255,.7);
		position: fixed;
		top: 95px;
		right: 10px;
		width: 200px;
		border: 0px solid red;
		text-align: center;
	}
	#qAnswersWrap #numAnswers, #numPlayers{
		display: inline-block;
		color: #fff;
		font-weight: bold;
		font-size: 15px;
	}



	#overlayWrap #userMapSubmit{
		font-size: 20px;
		padding:8px 25px;

	}

	#userAnswers{
		position: fixed;
		top: 150px;
		left: 20px;
		display: table;
	}

	#userAnswers .uaItem{
		padding:3px 10px;
		background: rgba(0,0,0,.5);
		box-shadow: 0px 0px 10px rgba(0,0,0,.5);
		margin-top: 5px;
		border-radius: 5px;
		display: inline-block;
		clear: left;
		float: left;
	}

	#userAnswers .uLabel{
		font-size: 16px;
		font-weight: 300;
		color: #fff;
		display: inline-block;
		margin-right: 3px;
	}
	#userAnswers .uScore{
		font-size: 15px;
		font-weight: bold;
		color: #fff;
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
	}
	#muteButton:focus{
		outline:0px;
		border:0px;
	}

</style>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>,'<?php echo $_SESSION["auto"]; ?>');
  findingNumberOfUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
</script>

<script type="text/javascript">
var playing = true;
//window.onload = function() {
/*  //alert (readCookie("playMusic")+"df");
  if (readCookie("playMusic")=="false")
  {
      playing=false;
  }
  else {
    var backgroundAudio=document.getElementById("bgMusic");
    backgroundAudio.volume=1.0;
  }

}*/

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

</script>

</head>
<body>


<audio id="bgMusic" autoplay enablejavascript="yes">
  <source src="quiz<?php echo rand(1,2); ?>.mp3"  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>

<input type="button" id="muteButton" onclick="mute()">
<a id="endGameLink" href="endScreen.php">end game</a>


	<div id="overlayWrap">
		<img src="logo.png" id="logo">
			<div id="qInfoWrap">
				<div class="qInfoBlock" id="qInfoNumber">
					<div class="qInfoLabel">Question</div>
					<div class="qInfoMain">#<?php echo $_SESSION["questionNumber"] ?></div>

				</div>
				<div class="qInfoBlock" id="qInfoLocation">
					<div class="qInfoLabel"><?php echo $theQuestion->getQuestionText(); ?></div>
					<div class="qInfoMain"><?php echo $theQuestion->getLabel(); ?></div>
				</div>

		 	</div>
		 <a href="showAnswer.php" id="userMapSubmit">Show Answer</a>
		 <div id="qAnswersWrap"> <div id="numAnswers">0</div>/<div id="numPlayers">0</div> answers so far</div>
	</div>
	<div id="qTimer">&nbsp;</div>
	<div id="qTimerBG">&nbsp;</div>
	<div  id="userAnswers">

	</div>
	<script>
	$(document).ready(function(){
	    if (readCookie("playMusic")=="false"){
	    	muteOff();
		}

		$("#overlayWrap").css("height", $("#qInfoLocation").height()+30);
		$("#qTimer").css("top", $("#qInfoLocation").height()+30);
		$("#qTimerBG").css("top", $("#qInfoLocation").height()+30);

	});


	</script>
</body>
</html>
