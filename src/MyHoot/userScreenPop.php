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
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <link rel="stylesheet" href="style/global.css">
<link rel="stylesheet" href="style/inputSlider.css">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }

      #overlayWrap{
        top:0px;
        bottom: 0px;
        height: auto;
        position: fixed;
        background: #43cea2; /* fallback for old browsers */
        background: -webkit-linear-gradient(to bottom, #43cea2 , #185a9d); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to bottom, #43cea2 , #185a9d); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
              }

      #newSlider{
        height: 65%;
        box-shadow: 0px 0px 0px;
        border:1px solid #fff;
        margin:auto;
        margin-top:185px;
        left:-50px;
      }
      #newSlider.noUi-background{
        background: transparent;
      }
      #newSlider .noUi-connect{
        background: #fff;
        box-shadow: 0px 0px 0px inset;
      }
      #newSlider .noUi-handle{
        border:0px;
        background: #F76116;
        border:2px solid #fff;
        box-shadow: inset 0px 0px 5px  rgba(0,0,0,.1), 0px 0px 10px  rgba(0,0,0,.3);
        cursor: pointer;
        height: 28px;
        width: 28px;
        border-radius: 50px;
      }
      #newSlider .noUi-handle::before{
        background: #BB3B08;
        top:10px;
        width: 12px;
        box-shadow: 0px 0px 3px  rgba(0,0,0,.3);
      }
      #newSlider .noUi-handle::after{
        background: #BB3B08;
        top: 13px;
        width: 12px;
        box-shadow: 0px 0px 3px  rgba(0,0,0,.3);
      }
      #newSlider .noUi-pips{
        color: rgba(255,255,255,.8);
        font-size: 13px;


      }
      #submitWrap{
        display: block;
        position: fixed;
        bottom: 30px;
        left: 0px;
        right: 0px;
        top:0px;
        text-align: center;

      }
      .smallLabel{
        font-size: 11px;
        color:rgba(255,255,255,.5);
      }
    </style>

    <script>

    var nf = new Intl.NumberFormat();

    function changeValue(value) {
      //var range = document.getElementById("isRange");
      var valbox = document.getElementById("isValue");
      var answer = document.getElementById("answer");

      if(parseInt(value) > 2140){
        valbox.value = "2,000,000,000";
        answer.value = "2000000000";
      }else{
        var afterScale = Math.round(Math.pow(Math.E, (parseInt(value)/100))/100000)*100000
        valbox.value = afterScale.toLocaleString();
        answer.value = afterScale;
      }
            //Math.round(value/100)*100
        //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    





/*	setTimeout( function(){
   if(XMLHttpRequest) var x = new XMLHttpRequest();
else var x = new ActiveXObject("Microsoft.XMLHTTP");
x.open("GET", 'inQuestion.php?question=<?php echo $_GET["question"]; ?>', true);
x.send();

  }  , 2500 );
*/




    </script>
    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
    <script src="socketScripts.js"></script>
    <script>
      loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
    </script>

<link href="nouislider.min.css" rel="stylesheet">

 </head>
 <body>
 <script src="nouislider.min.js"></script>

  <div id="overlayWrap">
    <img src="logo.png" id="logo">
    <h3>Round <?php echo $_GET["question"] ?></h3>
      <form name="form1" method="post" action="submitAnswer.php">
        <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
        <input type="hidden" id="answer" name="answer">


          <center id="submitWrap">
            <input type="text" id="isValue" name="isValue" value="5,000,000" readonly>
            <div id="newSlider"></div>
          </center>
                      <input type="submit" name="submit" id="userMapSubmit" value="Submit!">

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

    <div id="map-canvas"></div>


    <script type="text/javascript">
  



window.onload = function(){

  var slider = document.getElementById('newSlider');
  var valbox = document.getElementById("isValue");
  var answer = document.getElementById("answer");

  noUiSlider.create(slider, {
    start: [10000000],
    connect: "lower",
    orientation: "vertical",
    direction: 'rtl',
    range: {
      'min': [500000],
      '10%': [2000000],
      '20%': [5000000],
      '30%': [10000000],
      '40%': [20000000],
      '50%': [40000000],
      '60%': [80000000],
      '70%': [160000000],
      '80%': [320000000],
      '90%': [1000000000],
      'max': [2000000000]
    },pips: { // Show a scale with the slider
      mode: 'steps',
      density: 2
    }
  });


  slider.noUiSlider.on('update', function( values, handle ) {
      a = (Math.round(values[handle]/100000)*100000);
      valbox.value = comma(a);
      answer.value = a;
      //changeValue(values[handle]);
  });

  labels = document.getElementsByClassName("noUi-value-large");
  for(var i=0; i<labels.length;i++){
    val = parseInt(labels[i].innerHTML);
    labels[i].innerHTML = comma(val);
    if((i)%5 != 0)
      labels[i].className = labels[i].className + " smallLabel";
  }


};

  function comma(num){
    num = num+"";
    arr = num.split("");
    newS = "";
    for(var i=0; i<arr.length; i++){
      if((arr.length - i)%3 == 0 && i!=0)
        newS = newS + "," + arr[i];
      else
        newS = newS + arr[i];
    }
    return newS;
  }

</script>
 


 </body>
 </html>
