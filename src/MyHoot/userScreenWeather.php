<?php
session_start();
require 'dbsettings.php';
if (isset($_GET["question"]))
  if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
    header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));
?>
<html>
  <head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/inputSlider.css">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }

      #overlayWrap{
        height: 220px;
      }


    </style>

    <script>

    var nf = new Intl.NumberFormat();

    function changeValue() {
      var range = document.getElementById("isRange");
      var valbox = document.getElementById("isValue");
      var answer = document.getElementById("answer");

      if(parseInt(range.value) > 2140){
        valbox.value = "2,000,000,000";
        answer.value = "2000000000";
      }else{
        var afterScale = Math.round(Math.pow(Math.E, (parseInt(range.value)/100))/100000)*100000
        valbox.value = afterScale.toLocaleString();
        answer.value = afterScale;
      }
            //Math.round(value/100)*100
        //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }






	setTimeout( function(){
   if(XMLHttpRequest) var x = new XMLHttpRequest();
else var x = new ActiveXObject("Microsoft.XMLHTTP");
x.open("GET", 'inQuestion.php?question=<?php echo $_GET["question"]; ?>', true);
x.send();

  }  , 2500 );





    </script>
    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
    <script src="socketScripts.js"></script>
    <script>
      loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
    </script>
 </head>
 <body>
<h1>Weather</h1>
  <div id="overlayWrap">
    <img src="logo.png" id="logo">
    <h3>Round <?php echo $_GET["question"] ?></h3>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="hidden" id="answer" name="answer">

      <center>
        <input type="text" id="isValue" name="isValue" value="5,000,000" readonly>
        <div id="relativeWrap">
          <div id="rangeLineWrap">
            <div class="rangeLine"></div>
            <div class="rangeLine"></div>
            <div class="rangeLine"></div>
            <div class="rangeLine"></div>
            <div class="rangeLine"></div>
          </div>
          <input type="range" id="isRange" name="isRange" for="isValue" min="1320" max="2141.64130" value="1541" oninput="changeValue()">
        </div>
      </center>



        <input type="submit" name="submit" id="userMapSubmit" value="Submit!">
      </form>

  </div>

    <div id="map-canvas"></div>
 </body>
 </html>
