<?php
session_start();
$whitelist = array('lat','long','questionNumber');
require 'dbsettings.php';


$sql = "INSERT INTO `answers` (`game_id`,`user_id`,`questionNum`,`lat`,`longg`) VALUES ('$_SESSION[game_id]', '$_SESSION[user_id]','$questionNumber','$lat','$long')";

$result = $conn->query($sql);
//echo $sql;
//die();
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
