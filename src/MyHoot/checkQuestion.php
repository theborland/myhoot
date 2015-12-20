<?php
session_start();
$whitelist = array('lat','long','questionNumber');
require 'dbsettings.php';

$questionNumber=Game::findRound();
//die( $questionNumber);
if ($questionNumber==null)
  header( 'Location: joinquiz.php');
else if ($questionNumber==-1)
  header( 'Location: waitingScreen.php?message='."Sorry, there is no question in progress" ) ;
else
    header( 'Location: userScreen.php?question='.$questionNumber ) ;
 ?>
