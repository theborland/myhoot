<?php
session_start();
$whitelist = array('lat','long','answer','questionNumber');
require 'controller/dbsettings.php';

if (!isset($_SESSION["game_id"]))
  User::findGameID();

if ($lat==0 && $long==0 && $answer==0 && $questionNumber==0){
    header("userScreen.php");
    die ();
  }

if ($questionNumber<=0)
{
  $game=Game::findGame();
  $questionNumber=abs($game->round);
}

if ($lat=="")$lat=0;
if ($long=="")$long=0;
$color=User::getColor();
//die ($color);
if ($_SESSION["user_id"]==0)die("Sorry this shouldnt happen - tell me about it...");
$correct=Answer::loadCorrect($questionNumber);
//place currently is true or false if they could submit (meaning 1st time)


//echo $sql;
//die();
$game=Game::findGame();

if ($game->type=="geo" || $game->type=="places"|| $game->type=="pt")
    $distanceAway=LatLong::findDistance($correct->location,new LatLong($lat,$long));
/*else if ($game->type=="age"){
  $datetime1 = date_create($answer-$correct->value);

}*/

else
   $distanceAway=abs($answer-$correct->value);

if ($answer>100000)
      $distanceAway=round($distanceAway,-5);
//die ($distanceAway);
$place=Answer::addAnswer($_SESSION["user_id"],$questionNumber,$lat,$long,$answer,$distanceAway,$color,$game->type);

//NOW WE FIND THE OVERALL PERCENT PLACE THAT PERCENT DID ON THAT question_id
//Answer::findPercentPlace()

//echo $sql;
//die();
//SOCKET SENDING MESSAGE
    if ($place>=0){
      $entryData = array(
          'category' => "Game".$_SESSION['game_id'].$questionNumber
        , 'title'    => stripslashes($_SESSION["name"])
        , 'miles'    => number_format($distanceAway)
        , 'color'    => $color
      );
      $context = new ZMQContext();
      $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
      $socket->connect("tcp://localhost:5555");
      $socket->send(json_encode($entryData));
    }
    //END SOCKET SENDING
    if ($distanceAway>1000)
      $distanceAway=number_format($distanceAway);
    if ($game->type=="pop")
      $message= "Off by: ". $distanceAway. " people";
    else if ($game->type=="weather")
        $message= "Off by: ". $distanceAway. " degrees";
    else if ($game->type=="age" || $game->type=="time")
          $message= "Off by: ". $distanceAway. " years";
    else if ($game->type=="geo" || $game->type=="pt"|| $game->type=="places")
      $message= "Distance away : ". $distanceAway. " miles";
    else //if ($game->type=="rand")
      $message= "Off by: ". $distanceAway. "";
  //  $place=
    //echo $correct->location->longg;
  //  die ($message);//place='.$place.
   header( 'Location: waitingScreen.php?message='.$message.'&place='.$place ) ;
 ?>
