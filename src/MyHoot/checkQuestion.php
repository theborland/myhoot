<?php
session_start();
$whitelist = array('lat','long','questionNumber');
require 'controller/dbsettings.php';
if (!isset($_SESSION["game_id"]))
  Game::findGameID();
$game=Game::findGame();
$questionNumber=$game->round;
//die( $questionNumber);
if ($questionNumber==null)
  header( 'Location: joinQuiz.php');
else if ($questionNumber<0)
  header( 'Location: waitingScreen.php?message='."Sorry, there is no question in progress" ) ;
else
{
  if ($game->type=="geo"|| $game->type=="pt" || $game->type=="places")
          header( 'Location: userScreen.php?question='.$questionNumber ) ;
  else if ($game->type=="facts")
          header('Location: userScreenDecimal.php?perc=no&question='.$questionNumber ) ;
  else if ($game->type=="factsMax")
          header('Location: userScreenDecimal.php?perc=no&max=yes&question='.$questionNumber ) ;
  else if ($game->type=="factsPercent")
          header('Location: userScreenDecimal.php?perc=yes&question='.$questionNumber ) ;
  else if ($game->type=="science" || $game->type=="sports" || $game->type=="entertainment" || $game->type=="factsRand")
          header('Location: userScreenRand.php?question='.$questionNumber ) ;
  else if ($game->type=="WorldTime" || $game->type=="Time")//below is messed up I put 3 for region which is not right
          header('Location: userScreenWorldTime.php?region=3&perc=no&question='.$questionNumber ) ;
  else
      header('Location: userScreen'.$type=ucwords($game->type).'.php?question='.$questionNumber ) ;

//    else
//      header( 'Location: userScreen.php?question='.$questionNumber ) ;

}
 ?>
