<?php
session_start();
require 'dbsettings.php';
if (isset($_GET["question"]))
  if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
    header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));
?>
 <html>
 <head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style/global.css">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>

    <script>

	setTimeout( function(){
   if(XMLHttpRequest) var x = new XMLHttpRequest();
else var x = new ActiveXObject("Microsoft.XMLHTTP");
x.open("GET", 'inQuestion.php?question=<?php echo $_GET["question"]; ?>', true);
x.send();

  }  , 1500 );

    </script>
    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
    <script src="socketScripts.js"></script>
    <script>
      loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
    </script>
 </head>
 <body>

  <div id="overlayWrap">
    <img src="logo.png" id="logo">
    <h3>Round <?php echo $_GET["question"] ?></h3>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="text" id="answer" name="answer">
        <input type="submit" name="submit" id="userMapSubmit" value="Submit!">
      </form>

  </div>

    <div id="map-canvas"></div>
 </body>
 </html>
