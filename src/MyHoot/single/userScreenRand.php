<?php
session_start();


require '../controller/dbsettings.php';
if (isset($_GET["question"])){
  if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
    header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));


}
$_SESSION["questionNumber"]=Game::questionStatusRedirect();

$theQuestion=Question::loadQuestion();
$min=$theQuestion->min;
$max=$theQuestion->max;
//die ($max);
//$max=100;
$seconds=time();
$timeLeft=($seconds%($lengthOfGame+$lengthOfBreak)-$lengthOfGame)*-1;

?>
<html>
  <head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="../style/global.css">
      <link href="../style/nouislider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/inputSlider.css">
    <style>
      html, body {
        height: 100%;
        margin: 0px;
        padding: 0px
        background: #84392A;
      }

      #overlayWrap{
        top:0px;
        bottom: 0px;
        height: auto;
        position: fixed;
        background: #84392A;
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
    var count=<?php echo $timeLeft; ?>;

    var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

    function timer()
    {
      count=count-1;
      if (count <= 0)
      {
         clearInterval(counter);
         window.location.href = "showAnswerOther.php";
         count=33333;
         return;
      }
      else if (count<30)
        document.getElementById("timer2").innerHTML=count + " secs"; // watch for spelling
    }
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




    window.setTimeout(function(){
            window.location.href = "waitingScreen.php";
        }, 31000);




    </script>
    <script src="http://gameon.world/scripts/autobahn.min.js"></script>
    <script src="../scripts/socketScripts.js?ver=1"></script>
    <link href="../style/nouislider.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/inputSlider.css">
    <script>
  //    loadWaitingForQuestionSingle('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
    </script>

 </head>
 <body>
  <script src="../scripts/nouislider.min.js"></script>
  <div id="overlayWrap">
  <div id="questionWrap">
    <div id="questionType"><?php echo $theQuestion->getQuestionText(); ?></div>
    <div id="actualQuestion"><?php echo $theQuestion->getLabel(); ?> <?php echo $theQuestion->getQuestionTextEnd(); ?>?</div>
<span id="timer2"></span>
  </div>

    			<a href="http://GameOn.World" id="logoLink"><img src="../img/logo.svg" id="logo"></a>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="hidden" id="answer" name="answer">


          <center id="submitWrap">
            <input type="text" id="isValue" name="isValue" value="5,000,000" readonly>
            <div id="newSlider"></div>
          </center>
                      <input type="submit" class="regButton" name="submit" id="userMapSubmit" value="Submit!">

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

  noUiSlider.create(slider, {
    start: [50],
    connect: "lower",
    orientation: "vertical",
    direction: 'rtl',
    range: {
      'min': [<?php echo $min; ?>],
      '25%': [<?php echo round($min+($max-$min)*.25); ?>],
      '50%': [<?php echo round($min+($max-$min)*.5); ?>],
      '75%': [<?php echo round($min+($max-$min)*.75); ?>],
      'max': [<?php echo $max; ?>]
    },pips: { // Show a scale with the slider
      mode: 'steps',
      density: 2
    }
  });


  slider.noUiSlider.on('update', function( values, handle ) {
      a = Math.round(values[handle]);
      valbox.value = a + "";
      answer.value = a;
      //changeValue(values[handle]);
  });

  labels = document.getElementsByClassName("noUi-value-large");
  for(var i=0; i<labels.length;i++){
    //val = parseInt(labels[i].innerHTML);
    //labels[i].innerHTML = comma(val);
    if(i%2 != 0)
      labels[i].className = labels[i].className + " smallLabel";
  }

  markers = document.getElementsByClassName("noUi-marker-large");
  for(var i=0; i<markers.length;i++){
    //val = parseInt(markers[i].innerHTML);
    //markers[i].innerHTML = comma(val);
    if(i%2 != 0)
      markers[i].className = markers[i].className + " smallMarker";
  }


};

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
