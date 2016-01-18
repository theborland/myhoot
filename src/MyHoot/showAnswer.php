<?php
session_start();
//echo "sdfsdf";
require 'dbsettings.php';
if (Game::findGame()->type=="pop")
      header( 'Location: showAnswerPop.php') ;
if (Game::findGame()->type=="weather")
      header( 'Location: showAnswerWeather.php') ;
if (Game::findGame()->type=="age")
      header( 'Location: showAnswerAge.php') ;
if (Game::findGame()->type=="user")
      header( 'Location: showAnswerAge.php') ;
if (Game::findGame()->type=="time")
      header( 'Location: showAnswerTime.php');
$allAnswers=new AllAnswers($_SESSION["questionNumber"]);
?>

<html>
<head>
      <link rel="stylesheet" href="style/global.css">
      <style type="text/css">
            html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}



          #overlayWrap{
            left:400px;
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
            border-bottom:1px solid rgba(255,255,255,0.2);
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


    </style>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs"></script>
      <script>

<?php
if (isset($_SESSION["auto"]) && $_SESSION["auto"]=='yes')
{
?>
//automatically forward if automode is on
setTimeout( function(){
      window.location.href='getQuestion.php';
}  , 10000 );
<?php
}
?>

function initialize() {

  var locations = <?php echo $allAnswers->getLocations(); ?>;

  var myLatlng = new google.maps.LatLng(<?php echo $allAnswers->correctAns->location->lat; ?>,<?php echo $allAnswers->correctAns->location->longg; ?>);//ll.lat(),ll.lng());
  var mapOptions = {
    zoom: 4,
        mapTypeControl: false,
    streetViewControl: false,
center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      animation: google.maps.Animation.BOUNCE
  });


  var marker2, i;

    for (i = 0; i < locations.length; i++) {
      marker2 = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        title: locations[i][0],
        icon: new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" +locations[i][3])
        //icon:  //'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
      });
    }

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>

<div id="map-canvas"></div>
<div id="overlayWrap">
  <a href="showScoreBoard.php" style="display:none;">ScoreBoard</a>
  <a href="getQuestion.php" id="userMapSubmit">Next Question</a>
</div>
<div id="scoresWrap">
  <h1>Scoreboard</h1>
  <div id="scoresGraphWrap">

<!--
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Joe</div>
      <div class="scoresGraphAll" style="width:260;">285</div>
      <div class="scoresGraphNew" style="width:25;">&nbsp;</div><div class="scoresGraphNewLabel">+25</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Jim</div>
      <div class="scoresGraphAll" style="width:245;">275</div>
      <div class="scoresGraphNew" style="width:30;">&nbsp;</div><div class="scoresGraphNewLabel">+30</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Tim</div>
      <div class="scoresGraphAll" style="width:235;">245</div>
      <div class="scoresGraphNew" style="width:10;">&nbsp;</div><div class="scoresGraphNewLabel">+10</div>
    </div>
        <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Ben</div>
      <div class="scoresGraphAll" style="width:230;">235</div>
      <div class="scoresGraphNew" style="width:5;">&nbsp;</div><div class="scoresGraphNewLabel">+5</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Jan</div>
      <div class="scoresGraphAll" style="width:210;">230</div>
      <div class="scoresGraphNew" style="width:20;">&nbsp;</div><div class="scoresGraphNewLabel">+20</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Ken</div>
      <div class="scoresGraphAll" style="width:195;">200</div>
      <div class="scoresGraphNew" style="width:5;">&nbsp;</div><div class="scoresGraphNewLabel">+5</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Dan</div>
      <div class="scoresGraphAll" style="width:160;">185</div>
      <div class="scoresGraphNew" style="width:25;">&nbsp;</div><div class="scoresGraphNewLabel">+25</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Ron</div>
      <div class="scoresGraphAll" style="width:140;">170</div>
      <div class="scoresGraphNew" style="width:30;">&nbsp;</div><div class="scoresGraphNewLabel">+30</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Pan</div>
      <div class="scoresGraphAll" style="width:130;">140</div>
      <div class="scoresGraphNew" style="width:10;">&nbsp;</div><div class="scoresGraphNewLabel">+10</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Kim</div>
      <div class="scoresGraphAll" style="width:110;">135</div>
      <div class="scoresGraphNew" style="width:25;">&nbsp;</div><div class="scoresGraphNewLabel">+25</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Yan</div>
      <div class="scoresGraphAll" style="width:100;">125</div>
      <div class="scoresGraphNew" style="width:25;">&nbsp;</div><div class="scoresGraphNewLabel">+25</div>
    </div>
    <div class="scoresGraphBar">
      <div class="scoresGraphLabel">Lin</div>
      <div class="scoresGraphAll" style="width:80;">120</div>
      <div class="scoresGraphNew" style="width:40;">&nbsp;</div><div class="scoresGraphNewLabel">+40</div>
    </div>-->
   <?php
$allAnswers->getTP();
    foreach ($allAnswers->allAnswers as $key => $value) {
        ?><div class="scoresLine"><font color="<?php echo $value->color; ?>"><?php echo $value->name; ?></font>:
           <div class="roundPoints"><?php echo $value->roundPoints; ?></div>
          <div class="scoresGraphScore"><?php echo $value->totalPoints; ?></div>
        </div><?php
    }
 ?>


  </div>
</div>
</body>
</html>
