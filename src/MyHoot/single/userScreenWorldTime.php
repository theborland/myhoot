<?php
session_start();
$whitelist = array('region');
require '../controller/dbsettings.php';
if (isset($_GET["question"]))
  if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
    header("Location: waitingScreen.php?message=".urlencode("Come on - you can't submit twice..."));
    $_SESSION["questionNumber"]=Game::questionStatusRedirect();
    $theQuestion=Question::loadQuestion();
?>
<html>
  <head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="../style/global.css">
  <link rel="stylesheet" href="../style/getQuestion.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <link href="../style/nouislider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/inputSlider.css">
    <script src="../scripts/socketScripts.js"></script>
    <style>
      html, body {
        height: 100%;
        margin: 0px;
        padding: 0px
        background: #4C3D91; /* fallback for old browsers */
      }

      #overlayWrap{
        top:0px;
        bottom: 0px;
        height: auto;
        position: fixed;
        background: #4C3D91; /* fallback for old browsers */
      }
      #newSlider .noUi-pips{
        font-size: 16px;
      }
      #smallLabel{
        font-size: 14px;
      }
      #newSlider{
        left:-25px;
      }

      .noUi-value-large{
        margin-top:-12px;
      }
      .smallLabel{
        margin-top:-8px;
      }

    </style>

    <script>
    window.setTimeout(function(){
            window.location.href = "waitingScreen.php";
        }, 31000);
    //var nf = new Intl.NumberFormat();

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

    var counter = 30;
    window.onload = function(){
      $('#timer').animate({
        width: "0%"
      }, 30000, "linear");

      var interval = setInterval(function() {
          counter--;
        $('#timeLeft').html(counter);
          if (counter == 0) {
            window.location.replace("showAnswer.php");
          }
      }, 1000);

      //animation clock
      var x=0;
      var interval = setInterval(function() {

           x++;
      }, 50);



    }


    </script>
    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>

    <script>

      loadWaitingForQuestionSingle('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
    </script>

 </head>
 <body>
  <script src="../scripts/nouislider.min.js"></script>

  <div id="overlayWrap">
    <div id="timerContainer">
      <div id="timer"></div>
    </div>
    <div id="questionWrap">
      <div id="questionType"><?php echo $theQuestion->getQuestionText(); ?></div>
      <div id="actualQuestion"><?php echo $theQuestion->getLabel(); ?> <?php echo $theQuestion->getQuestionTextEnd(); ?>?</div>
    </div>
    			<a href="http://GameOn.World" id="logoLink"><img src="../img/logo.svg" id="logo"></a>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="hidden" id="answer" name="answer">


          <center id="submitWrap">
            <input type="text" id="isValue" name="isValue" value="5,000,000" readonly>
            <div id="newSlider"></div>
          </center>
                      <input type="submit" name="submit" class="regButton" id="userMapSubmit" value="Submit!">

          <!--
          <div id="relativeWrap">
            <div id="rangeLineWrap">
              <div class="rangeLine"></div>
              <div class="rangeLine"></div>
              <div class="rangeLine"></div>
              <div class="rangeLine"></div>
              <div class="rangeLine"></div>
            </div>
            <input type="range" id="isRange" list="numbers" step="10" name="isRange" for="isValue" min="1320" max="2141.64130" value="1541" oninput="changeValue()">
            <datalist id="numbers">
              <option>10</option>
              <option label="30">30</option>
              <option label="midpoint">50</option>
              <option>70</option>
              <option>90</option>
            </datalist>
            -->

          </div>
      </form>
  </div>

    <script type="text/javascript">



window.onload = function(){

  var slider = document.getElementById('newSlider');
  var valbox = document.getElementById("isValue");
  var answer = document.getElementById("answer");

  var realAnswer = <?php echo $region; ?>; ///  ************ change this to the correct answer

  var range = {   'min': [1600],    '25%': [1700],    '50%': [1800],    '75%': [1900],   'max': [2020] };



  // if (realAnswer < -200){
  //   range = {   'min': [-800],    '25%': [-600],    '50%': [-400],    '75%': [-200],   'max': [0] };
  // }else if (realAnswer >= -200 && realAnswer < 600){
  //   range = {   'min': [-200],    '25%': [0],    '50%': [200],    '75%': [400],   'max': [600] };
  // }else if (realAnswer >= 600 && realAnswer < 1300){
  //   range = {   'min': [600],    '25%': [800],    '50%': [1000],    '75%': [1200],   'max': [1400] };
  // }else if (realAnswer >= 1300 && realAnswer < 1700){
  //   range = {   'min': [1300],    '25%': [1400],    '50%': [1500],    '75%': [1600],   'max': [1700] };
  // }else if (realAnswer >= 1700){
  //   range = {   'min': [1600],    '25%': [1700],    '50%': [1800],    '75%': [1900],   'max': [2020] };
  // }

    if (realAnswer == 0){
    range = {   'min': [-800],    '25%': [-600],    '50%': [-400],    '75%': [-200],   'max': [0] };
  }else if (realAnswer == 1){
    range = {   'min': [-200],    '25%': [0],    '50%': [200],    '75%': [400],   'max': [600] };
  }else if (realAnswer == 2){
    range = {   'min': [600],    '25%': [800],    '50%': [1000],    '75%': [1200],   'max': [1400] };
  }else if (realAnswer == 3){
    range = {   'min': [1200],    '25%': [1300],    '50%': [1400],    '75%': [1500],   'max': [1600] };
  }


  noUiSlider.create(slider, {
    start: [range['50%']],
    connect: "lower",
    orientation: "vertical",
    direction: 'rtl',
    range: range
    ,pips: { // Show a scale with the slider
      mode: 'steps',
      density: 2
    }
  });



  slider.noUiSlider.on('update', function( values, handle ) {

    if(true){ // true if you want decimals
      a = Math.round(values[handle]);
    }else{
      a = Math.round(values[handle]);
    }
      valbox.value = formatYear(a);
      answer.value = a;
      //changeValue(values[handle]);
  });

  labels = document.getElementsByClassName("noUi-value-large");
  for(var i=0; i<labels.length;i++){
    val = parseInt(labels[i].innerHTML);
    labels[i].innerHTML = formatYear(val);
    if(i%2 != 0)
      labels[i].className = labels[i].className + " smallLabel";
  }

  markers = document.getElementsByClassName("noUi-marker-large");
  for(var i=0; i<markers.length;i++){
    //val = parseInt(markers[i].innerHTML);
    //markers[i].innerHTML = formatYear(val);
    if(i%2 != 0)
      markers[i].className = markers[i].className + " smallMarker";
  }


};

function formatYear(year){
      if (year < 0){
        return( (year * -1) + " BC" );
      }else{
        return(year);
      }

}

  function decimalize(numberAsString){
    var numberInt = parseInt(numberAsString)
    var numberDecimalized = 2
  }

  function comma(num){
    num = num+"";
    arr = num.split("");
    newS = "";
    for(var i=0; i<arr.length; i++){
      if((arr.length - i)%i == 0 && i!=0)
        newS = newS + "," + arr[i];
      else
        newS = newS + arr[i];
    }
    return newS;
  }

</script>


 </body>
 </html>
