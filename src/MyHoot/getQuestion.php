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
<script>
var counter = 60;
var interval = setInterval(function() {
    counter--;
  $('#timeLeft').html(counter);
    if (counter == 0) {
        // Display a login box
        clearInterval(interval);
    }
}, 1000);
</script>
<style>
	body{
	background: url('paris.jpeg<?php //echo $theQuestion->getImage() ?>');
	background-size: cover;
	background-repeat: no-repeat;

	}
	#overlayWrap{
		height: 130px;
		text-align: center;
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
		padding: 15px 10px;
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
		font-weight: bold;
		font-size:20px;
		display: block;
		color:rgba(255, 255, 255, .7);
		text-align: left;
	}
	.qInfoMain{
		font-size: 40px;
		font-weight: 100;
	}
</style>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
</script>
</head>
<body>

	<div id="overlayWrap">
		<img src="logo.png" id="logo">
			<div id="qInfoWrap">
				<div class="qInfoBlock" id="qInfoNumber">
					<div class="qInfoLabel">Question</div>
					<div class="qInfoMain"><?php echo $_SESSION["questionNumber"] ?></div>
				</div>
				<div class="qInfoBlock" id="qInfoLocation">
					<div class="qInfoLabel">Where is</div>
					<div class="qInfoMain"><?php echo $theQuestion->city ?>, <?php echo $theQuestion->country ?></div>
				</div>
				<div class="qInfoBlock" id="qInfoTime">
					<div class="qInfoLabel">Time left</div>
					<div class="qInfoMain" id="timeLeft">60</div>
				</div>
				<div class="qInfoBlock" id="qInfoAnswers">
					<div class="qInfoLabel">Number of anwers</div>
					<div class="qInfoMain" id="numAnswers">0</div>
				</div>
		 	</div>
		 <a href="showAnswer.php" id="userMapSubmit">Show Answer</a>
	</div>
</body>
</html>
