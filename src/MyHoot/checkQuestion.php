<?php
session_start();
$whitelist = array('lat','long','questionNumber');
require 'controller/dbsettings.php';

$game=Game::findGame();
$questionNumber=$game->round;
//die( $questionNumber);
if ($questionNumber==null)
  header( 'Location: joinQuiz.php');
else if ($questionNumber<0)
  header( 'Location: waitingScreen.php?message='."Sorry, there is no question in progress" ) ;
else
{
    if ($game->type=="geo")
      header( 'Location: userScreen.php?question='.$questionNumber ) ;
    else {
      $type=ucwords($game->type);
      header( 'Location: userScreen'.$type.'.php?question='.$questionNumber ) ;
    }
//    else
//      header( 'Location: userScreen.php?question='.$questionNumber ) ;

}
 ?>
