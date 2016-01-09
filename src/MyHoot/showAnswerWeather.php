<?php
session_start();
//echo "sdfsdf";
require 'dbsettings.php';

$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
?>

<html>
<head>
      <link rel="stylesheet" href="style/global.css">
      <link rel="stylesheet" href="style/inputButton.css">
      <style type="text/css">
            html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}

          body{
            background: url('<?php echo "paris.jpeg"; ?>');
            background-size: cover;
            background-repeat: no-repeat;

          }

          #overlayWrap{
            left:400px;
            height:140px;
          }

          #scoresWrap{
            display: block;
            position: fixed;
            left: 0px;
            top: 0px;
            bottom:0px;
            padding:0px 20px;
            box-sizing:border-box;
            width: 400px;
            z-index: 10;
            background: rgba(0,0,0,.7);
            box-shadow: 0px 0px 25px rgba(0,0,0,.5);
            color: #fff;
          }

          #scoresWrap h1{
            font-size: 40px;
            font-weight: 100;

          }

          #scoresGraphWrap{
            display: block;
            height: 80%;
            overflow-y:auto;
            width: 350px;
            padding-right: 10px;
          }

          .scoresGraphLabel{
            display: block;
            font-size: 16px;
            font-weight: 300;
            color: #fff;
          }

          .scoresGraphBar{
            padding:0px 0px 15px;
            margin:5px 0px;
            border-bottom:1px solid rgba(255,255,255,0.2);ß
          }

          .scoresGraphAll{
            display: inline-block;
            box-sizing:border-box;
            margin:0px;
            padding:5px 0px 5px 10px;
            background: #1D81CF;
            font-weight: 500;
          }
          .scoresGraphNew{
            display: inline-block;
            box-sizing:border-box;
            margin:0px;
            padding:5px 0px 5px 10px;
            background: #F76116;
            margin-left:-4px;
            margin-right: 5px;
          }
          .scoresGraphBar:last-child{
            border-bottom: 0px;
          }

          .scoresGraphNewLabel{
            display: inline-block;
            font-weight: 500;
          }


          #scoresGraphWrap::-webkit-scrollbar {
              width: 10px;
          }

          #scoresGraphWrap::-webkit-scrollbar-track {
             display: none;
          }

          #scoresGraphWrap::-webkit-scrollbar-thumb {
              border-radius: 2px;
              background:rgba(255,255,255,0);
              cursor: pointer;
          }

          #scoresWrap:hover #scoresGraphWrap::-webkit-scrollbar-thumb {
              background:rgba(255,255,255,.2);
          }


          .scoresLine{
            display: block;
            font-size: 22px;
            font-weight: 300;
            border:0px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding:5px 10px;
          }

          .scoresGraphScore{
            display: inline-block;
            float: right;
            font-weight: bold;
          }
          .scoresLine:last-child{
            border-bottom: 0px;
          }

          #overlayWrap #userMapSubmit{
            top:45px;
            right:30px;
          }

          #answerLabel{
            font-weight: 500;
            color:rgba(255,255,255,.8);
            font-size: 20px;
            font-family: overpass;
            margin-left: 40px;
            margin-top:20px;
          }
          #answer{
            display: none;
          }
    </style>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>
      <script>


window.onload = function() {



function animateNum(i, n, fin, finNum, time){
      setTimeout( function(){
        if(fin)
          document.getElementById("answerNum" + n).innerHTML = finNum;
        else
          document.getElementById("answerNum" + n).innerHTML = Math.round(Math.random() * 9);
    }, Math.pow(i, 1.05) * time);

}

answer = document.getElementById("answer").innerHTML;
answer = answer.length >= 3 ? answer :new Array(3 - answer.length + 1).join("x") + answer;

setTimeout( function(){

  for(n=0; n < answer.length; n++){
    time = 50 + Math.round(Math.random() * 50);
    for(i=0; i < 25; i++){
        val = (answer.charAt(n) == "x".charAt(0)) ? "&nbsp;" : answer.charAt(n) + "";
        animateNum(i, n, (i==24), val, time);
    }
  }
}, 500);
}




<?php
if ($_SESSION["auto"]=='yes')
{
?>
//automatically forward if automode on
setTimeout( function(){
      window.location.href='getQuestion.php';
}  , 100000 );



<?php
}
?>

</script>
</head>
<body>
<div id="map-canvas"></div>
<div id="overlayWrap">
  <div id="answerLabel">Correct Answer</div>
  <div id="answerWrap">
    <div class="answerNum" id="answerNum0">0</div>
    <div class="answerNum" id="answerNum1">0</div>
    <div class="answerNum" id="answerNum2">0</div>
    <div class="answerNum noB" id="answerNumC"  style="width:200px;">&deg;F</div>
  </div>

  <a href="showScoreBoard.php" style="display:none;">ScoreBoard</a>
  <a href="getQuestion.php" id="userMapSubmit">Next Question</a>
</div>
<div id="scoresWrap">
  <h1>Scoreboard</h1>
  <div id="scoresGraphWrap">

<!--
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Lin</div>
      <div class="scoresGraphAll" style="width:80;">120</div>
      <div class="scoresGraphNew" style="width:40;">&nbsp;</div><div class="scoresGraphNewLabel">+40</div>
    </div>-->
    <?php
  $allAnswers->getTP();
     foreach ($allAnswers->allAnswers as $key => $value) {
         ?><div class="scoresLine"><?php echo $value->name; ?>:
           + <div class="roundPoints"><?php echo $value->roundPoints; ?></div>
           <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>
         </div><?php
     }
  ?>


  </div>

</div>
<div id="answer"><?php echo ($allAnswers->correctAns->value); ?></div>
</body>
</html>
