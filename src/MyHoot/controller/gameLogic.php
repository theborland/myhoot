<?php  //file used just for getQuestion.php
$whitelist = array('statesCB','numRounds','gsGeo','gsAge','gsHist','gsPop','gsTemp','gsRand','r_SA','r_EU','r_AF','r_NS','r_SS','r_ME','r_OC','r_NA');
require 'controller/dbsettings.php';
//echo print_r($_GET["games"]);
if ($_SESSION["game_id"]==0)
   die("no game id");
if ($gsGeo=="false" || $gsGeo=="true")
{
  //die ($r_OC);
  $gamesSelected=array();
  $regionsSelected=array();
  $_SESSION["playedGames"]=array();
  if ($r_SA=="true"){$regionsSelected[]=2;$regionsSelected[]=3;$regionsSelected[]=4;}
  if ($r_NA=="true"){$regionsSelected[]=1;}
  if ($r_EU=="true"){$regionsSelected[]=6;$regionsSelected[]=5;}
  if ($r_AF=="true"){$regionsSelected[]=8;$regionsSelected[]=13;}
  if ($r_NS=="true"){$regionsSelected[]=11;}
  if ($r_ME=="true"){$regionsSelected[]=7;}
  if ($r_SS=="true"){$regionsSelected[]=9;}
  if ($r_OC=="true"){$regionsSelected[]=10;}
  if ($statesCB=="statesCB" && $r_NA=="true"){$regionsSelected[]=20;}
  if ($statesCB=="statesCB" && $r_NA=="false" && sizeof($regionsSelected)==0){$regionsSelected[]=20;}
  if ($gsAge=="true")$gamesSelected[]="age";
  if ($gsHist=="true")$gamesSelected[]="time";
  if ($gsTemp=="true")$gamesSelected[]="weather";
  if ($gsPop=="true")$gamesSelected[]="pop";
  if ($gsRand=="true")$gamesSelected[]="rand";
  if ($gsGeo=="true"){
    foreach ($gamesSelected as $key)
        if (sizeof($gamesSelected)<=7)
            $gamesSelected[]="geo";
  }
  if (sizeof($gamesSelected)==0)
     $gamesSelected[]="geo";
  //die (print_r($regionsSelected));
  $_SESSION["gamesSelected"]=$gamesSelected;
  $_SESSION["regionsSelected"]=$regionsSelected;
  $_SESSION["numRounds"]=$numRounds;

  if (isset($_GET["auto"]))
      $_SESSION["auto"]=$_GET["auto"];
  else
      $_SESSION["auto"]="";
  //echo "her";
  //print_r($regionsSelected);
}

if ($_SESSION["questionNumber"]>=$_SESSION["numRounds"]){
  //  header( 'Location: endScreen.php') ;
}
else {
    $gamesSelected=$_SESSION["gamesSelected"];
    $playedGames=$_SESSION["playedGames"];
    if (sizeof($playedGames)==0)
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    else {
        $idealArray =array_count_values($gamesSelected);
        $currentArray =array_count_values($playedGames);
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
        while ($currentArray[$current]/sizeof($playedGames)>$idealArray[$current]/sizeof($gamesSelected))
                $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    }
    $theQuestion=new Question($current);
    $playedGames[]=$current;
    $_SESSION["playedGames"]=$playedGames;
    //print_r($playedGames);
  }
?>
