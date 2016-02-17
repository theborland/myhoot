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
      <link rel="stylesheet" href="style/endScreen.css">
      <link href="nouislider.min.css" rel="stylesheet">
      <style type="text/css">
            html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
          html{
            background:#000;
          }

          body{
            <?php if($theQuestion->type == "age"){ ?>
              background-size: contain;
            <?php }else{ ?>
              background-size: cover;
            <?php } ?>
            background-repeat: no-repeat;
            background-position:center center;
            background: url('paris.jpeg');

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
}  , 10000 );



<?php
}
?>

</script>
</head>
<body>
<script src="nouislider.min.js"></script>
  <div id="scoresWrap">
    <div id="scoresGraphWrap">

      <h1>Scoreboard</h1>
      <?php
        $allAnswers->getTP();
        //echo($allAnswers->allAnswers[0]->color);
        //echo sizeof($allAnswers);
           foreach ($allAnswers->allAnswers as $key => $value)
            { ?>
              <div class="scoresLine">
                <div class="scoresName" style="background:#<?php echo $value->color; ?>"><?php echo $value->name; ?></div>
                <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>
              </div>
      <?php }?>

    </div>


</div>


<div id="winnerStandAligner">
  <div id="winnerStandWrap">
    <h1>WINNERS</h1>

    <div id="standsWrap">
      <?php if (sizeof($allAnswers->allAnswers )>=2){ ?>
      <div class="standWrap">
        <div class="winnerStand" id="ws2">2</div>
        <div class="winnerNameWrap" id="wn2">
            <div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[1]->color; ?>;"><?php
            echo $allAnswers->allAnswers[1]->name;
            ?></div>
        </div>
      </div>
        <?php  } if (sizeof($allAnswers->allAnswers )>=1){ ?>
      <div class="standWrap">
        <div class="winnerStand" id="ws1">1</div>
        <div class="winnerNameWrap" id="wn1">
            <div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[0]->color; ?>;"><?php
                 echo $allAnswers->allAnswers[0]->name;
            ?></div>
        </div>
      </div>
      <?php  } if (sizeof($allAnswers->allAnswers )>=3){ ?>
      <div class="standWrap">
        <div class="winnerStand" id="ws3">3</div>
        <div class="winnerNameWrap" id="wn3">
            <div class="winnerName" style="background:#<?php echo $allAnswers->allAnswers[2]->color; ?>;"><?php
                 echo $allAnswers->allAnswers[2]->name;
            ?></div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

</body>
</html>
