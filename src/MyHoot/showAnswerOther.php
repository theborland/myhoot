<?php
session_start();
//echo "sdfsdf";
require 'dbsettings.php';

$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
$theQuestion=Question::loadQuestion();

?>

<html>
<head>
      <link rel="stylesheet" href="style/global.css">
      <link rel="stylesheet" href="style/inputButton.css">
      <link href="nouislider.min.css" rel="stylesheet">
      <style type="text/css">
            html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}

          body{
            background-size: cover;
            background-repeat: no-repeat;
            background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('<?php echo Question::loadImage($allAnswers->correctAns->name,Game::findGame()->type); ?>');

          }

          #overlayWrap{
            left:400px;
            height:140px;
            background: rgba(0,0,0,.7);
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
            background: rgba(0,0,0,.8);
            box-shadow: 0px 0px 25px rgba(0,0,0,.5);
            color: #fff;
          }

          #scoresWrap h1{
            font-size: 40px;
            font-weight: 100;

          }

          #scoresGraphWrap{
            display: block;
            height: 99%;
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
            padding:8px 10px;
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
          .noUi-pips{
            color:#fff;
          }
          .noUi-marker-normal{
            background:rgba(255,255,255,.5);
          }
          .noUi-marker-large{
            background:rgba(255,255,255,1);
          }
          .noUi-handle{
            display: none;
          }
          .noUi-connect{
            display: none;
          }
          .noUi-target{
            background:rgba(255,255,255,0);
            border:1px solid rgba(255,255,255,1);
            box-shadow: none;
            height:8px;
          }
    </style>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>
      <script>




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
<script src="nouislider.min.js"></script>
<div id="overlayWrap">
  <div id="answerLabel"> <?php echo $theQuestion->getQuestionText(); ?> <?php echo $theQuestion->getLabel(); ?></div>
  <div id="answerWrap">
    <?php for ($i=0;$i<=strlen($allAnswers->correctAns->value);$i++){  ?>
       <div class="answerNum" id="answerNum<?php echo $i; ?>">0</div>
       <?php if ((8-$i)%3==1 && $i!=strlen($allAnswers->correctAns->value)){   ?>
         <div class="answerNum noB" id="answerNumC">&nbsp;</div>
       <?php  }  ?>
    <?php } ?>
    <div class="answerNum noB" id="answerNumC"  style="width:70px;"><?php echo $theQuestion->getQuestionUnits(); ?></div>
  </div>

  <a href="showScoreBoard.php" style="display:none;">ScoreBoard</a>
  <a href="getQuestion.php" id="userMapSubmit">Next Question</a>
</div>
<div id="scoresWrap">
    <div id="scoresGraphWrap">

      <h1>Scoreboard</h1>
      <?php
        $allAnswers->getTP();
           foreach ($allAnswers->allAnswers as $key => $value) {?>
              <div class="scoresLine">
                <div class="scoresName" style="background:#<?php echo $value->color; ?>"><?php echo $value->name; ?></div>
                <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>
                <div class="roundPoints"><?php echo $value->roundPoints; ?></div>
              </div>
      <?php }?>
    </div>


</div>
<div id="timelineWrap">



  <div id="timeline">
    <div class="timelineMarker" id="timelineCA" style="border-color:#E12027;margin-left:calc(<?php //echo $allAnswers->correctAns->value/120*90; ?>% - 5px);">&nbsp;</div>
    <?php foreach ($allAnswers->allAnswers as $key => $value){  ?>
    <div class="timelineMarker" style="border-color:#<?php echo $value->color ?>;margin-left:calc(<?php echo $value->ans/120*90; ?>% - 5px);">&nbsp;</div>
    <?php } ?>

  </div>
</div>
<div id="answer"><?php echo ($allAnswers->correctAns->value); ?></div>
<script>

window.onload = function(){

function animateNum(i, n, fin, finNum, time){
      setTimeout( function(){
        if(fin)
          document.getElementById("answerNum" + n).innerHTML = finNum;
        else
          document.getElementById("answerNum" + n).innerHTML = Math.round(Math.random() * 9);
    }, Math.pow(i, 1.05) * time);

}

  answer = document.getElementById("answer").innerHTML;
  //alert(answer);
  answer = answer.length >= 10 ? answer :new Array(2).join("x") + answer;
  //alert(answer);
  setTimeout( function(){

    for(n=0; n < answer.length; n++){
      time = 50 + Math.round(Math.random() * 50);
      for(i=0; i < 25; i++){
          val = (answer.charAt(n) == "x".charAt(0)) ? "&nbsp;" : answer.charAt(n) + "";
          animateNum(i, n, (i==24), val, time);
      }
    }
  }, 500);





var timeline = document.getElementById('timeline');

noUiSlider.create(timeline, {
  start: [60],
  connect: "upper",
  direction: 'ltr',
  range: {
    'min': [0],
    '25%': [30],
    '50%': [60],
    '75%': [90],
    'max': [120]
  },pips: { // Show a scale with the slider
    mode: 'steps',
    density: 2
  }
});

};

</script>
</body>
</html>
