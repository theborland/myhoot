<?php
session_start();
$whitelist = array('lat','long','answer','questionNumber');
require 'dbsettings.php';

if ($lat=="")$lat=0;
if ($long=="")$long=0;
$sql = "INSERT INTO `answers` (`game_id`,`user_id`,`questionNum`,`lat`,`longg`,`answer`) VALUES ('$_SESSION[game_id]', '$_SESSION[user_id]','$questionNumber','$lat','$long','$answer')";
//echo $sql;
//die();
$result = $conn->query($sql);
$game=Game::findGame();
$correct=Answer::loadCorrect($questionNumber);
if ($game->type=="geo")
    $distanceAway=LatLong::findDistance($correct->location,new LatLong($lat,$long));
/*else if ($game->type=="age"){
  $datetime1 = date_create($answer-$correct->value);

}*/

else
   $distanceAway=abs($answer-$correct->value);
//die ($distanceAway);

//echo $sql;
//die();
//SOCKET SENDING MESSAGE
    $entryData = array(
        'category' => "Game".$_SESSION['game_id'].$questionNumber
      , 'title'    => $_SESSION["name"]
      , 'miles'    => number_format($distanceAway)
    );
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");
    $socket->send(json_encode($entryData));
    //END SOCKET SENDING
    if ($distanceAway>1000)
      $distanceAway=number_format($distanceAway);
    if ($game->type=="pop")
      $message= "Off by: ". $distanceAway. " people";
    if ($game->type=="weather")
        $message= "Off by: ". $distanceAway. " degrees";
    if ($game->type=="age")
          $message= "Off by: ". $distanceAway. " years";
    if ($game->type=="geo")
      $message= "Distance away : ". $distanceAway. " miles away";
    //echo $correct->location->longg;
  //  die ($message);
   header( 'Location: waitingScreen.php?message='.$message ) ;
 ?>
