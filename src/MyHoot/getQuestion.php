<?php
session_start();
require 'dbsettings.php';
//echo "Game".$_SESSION["game_id"]."Status";
$theQuestion=new Question();

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
}, 60000, "linear");

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
	body{
	background: url('<?php echo $theQuestion->getImage() ?>');
	background-size: cover;
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
<<<<<<< HEAD
		z-index: 3;
=======
		z-index: 3;
>>>>>>> d019771b6be1b8ad4bd0388e2bb62c8d32fd2a27
		box-shadow: 0px 0px 20px rgba(0,0,0,.7);
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
	#qAnswersWrap #numAnswers{
		display: inline-block;
		color: #fff;
		font-weight: bold;
		font-size: 15px;
	}

	#overlayWrap #userMapSubmit{
		font-size: 20px;
		padding:8px 25px;

	}
</style>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
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
					<div class="qInfoLabel">Where is</div>
					<div class="qInfoMain"><?php echo $theQuestion->getLabel(); ?></div>
				</div>

		 	</div>
		 <a href="showAnswer.php" id="userMapSubmit">Show Answer</a>
		 <div id="qAnswersWrap"> <div id="numAnswers">0</div> answers so far</div>of  <div id="numPlayers">0</div> num players</div>
	</div>
	<div id="qTimer">&nbsp;</div>
	<div id="qTimerBG">&nbsp;</div>
  User Answers<div  id="userAnswers">
  </div>
</body>
</html>
