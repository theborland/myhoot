<?php
session_start();
     
require 'dbsettings.php';
//echo "Game".$_SESSION["game_id"]."Status";
$theQuestion=new Question();

?>

<html>
<head>
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
 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForAnswers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>,<?php echo $_SESSION["questionNumber"]; ?>);
</script>
</head>
<body>

Questions: <?php echo $_SESSION["questionNumber"] ?>
What are the coordinates of <?php echo $theQuestion->city ?>, <?php echo $theQuestion->country ?> ?

Time left  <div id="timeLeft"></div>
<a href="showAnswer.php">Show Answer
</a>
 The number of answers so far: is <div id="numAnswers">0</div>
 <image src="<?php echo $theQuestion->getImage() ?>"></image>
</body>
</html>
