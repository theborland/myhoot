<?php
session_start();
require 'dbsettings.php';

// An array of $_POST keys that are acceptable
$whitelist = array('lat','long','questionNumber');

foreach($whitelist as $key) {
   if (isset($_POST[$key])) {
     $$key = $conn->real_escape_string($_POST[$key]);
   }
   else if (isset($_GET[$key])) {
     $$key = $conn->real_escape_string($_GET[$key]);
   }
   else $$key = "";
}

$questionNumber=substr($questionNumber,1);
$sql = "INSERT INTO `answers` (`game_id`,`user_id`,`questionNum`,`lat`,`longg`) VALUES ('$_SESSION[game_id]', '$_SESSION[user_id]','$questionNumber','$lat','$long')";
//echo $sql;

$result = $conn->query($sql);

//SOCKET SENDING MESSAGE
    $entryData = array(
        'category' => "Game".$_SESSION['game_id'].$questionNumber
      , 'title'    => $_SESSION['user_id']
    );
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");
    $socket->send(json_encode($entryData));
    //END SOCKET SENDING
    $correct=Answer::loadCorrect($questionNumber);
    $message= "Distance away : ". LatLong::findDistance($correct->location,new LatLong($lat,$long)). " miles away.";
    //echo $correct->location->longg;
    header( 'Location: waitingScreen.php?message='.$message ) ;
 ?>
