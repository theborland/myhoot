<?php
session_start();
$whitelist = array('gsGeo','gsAge','gsHist','gsPop','gsTemp');
require 'dbsettings.php';
//echo print_r($_GET["games"]);
if ($gsGeo=="false" || $gsGeo=="true")
{
  $gamesSelected=array();
  if ($gsAge=="true")$gamesSelected[]="age";
  if ($gsHist=="true")$gamesSelected[]="time";
  if ($gsTemp=="true")$gamesSelected[]="weather";
  if ($gsPop=="true")$gamesSelected[]="pop";
  if ($gsGeo=="true"){
    foreach ($gamesSelected as $key)
        if (sizeof($gamesSelected)<4)
            $gamesSelected[]="geo";
    if (sizeof($gamesSelected)==0)
       $gamesSelected[]="geo";
  }
  //print_r($gamesSelected);
  $_SESSION["gamesSelected"]=$gamesSelected;
}
if (isset($_GET["auto"]))
    $_SESSION["auto"]=$_GET["auto"];
if (isset($_GET["type"]))
            $_SESSION["type"]=$_GET["type"];

    $gamesSelected=$_SESSION["gamesSelected"];
    //print_r($gamesSelected);
    $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    $theQuestion=new Question($current);
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
</style>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>,'<?php echo $_SESSION["auto"]; ?>');
  findingNumberOfUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
</script>
</head>
<body>

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
		//alert($("#qInfoLocation").height());
		$("#overlayWrap").css("height", $("#qInfoLocation").height()+30);
		$("#qTimer").css("top", $("#qInfoLocation").height()+30);
		$("#qTimerBG").css("top", $("#qInfoLocation").height()+30);

	});


	</script>
</body>
</html>
