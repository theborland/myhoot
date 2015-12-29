<?php
session_start();
$whitelist = array('lat','long','questionNumber');
require 'dbsettings.php';

$game=Game::findGame();
$questionNumber=$game->round;
//die( $questionNumber);
if ($questionNumber==null)
  header( 'Location: joinquiz.php');
else if ($questionNumber==-1)
  header( 'Location: waitingScreen.php?message='."Sorry, there is no question in progress" ) ;
else
{
    if ($game->type=="pop")
      header( 'Location: userScreenPop.php?question='.$questionNumber ) ;
    else
      header( 'Location: userScreen.php?question='.$questionNumber ) ;

}
 ?>
