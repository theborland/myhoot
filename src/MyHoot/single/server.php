<?php

require '../controller/dbsettings.php';
include ('../controller/siteFunctions.php');
include ('../controller/Question.php');
include ('../controller/Game.php');
include ('../controller/Answer.php');
include ('../controller/User.php');

$_SESSION["questionNumber"]=1;
$_SESSION["single"]=true;
$playedGames=array();
$_SESSION["regionsSelected"]=$regionsSelected=array(1,2,3,4,5,6,7,8,9,10,11);
$gamesSelected=array("time","weather","pt","places","places","facts","facts","geo","geo","geo","geo","geo");
Game::createGame(false,true);

echo "<Br>Starting";

while (true){
    if (sizeof($playedGames)==0)
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    else {
        $idealArray =array_count_values($gamesSelected);
        $currentArray =array_count_values($playedGames);
        $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
        while (array_key_exists($current,$currentArray) && $currentArray[$current]/sizeof($playedGames)>$idealArray[$current]/sizeof($gamesSelected))
        {
          //echo ("current". $current.($currentArray[$current]/sizeof($playedGames)));
           $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
        }
        //
    }


    $theQuestion=new Question($current);
    if ($current=="age")$current="entertainment";
    if ($current=="pop")$current="facts";
    $playedGames[]=$current;
    echo $theQuestion->getLabel();
    sleep(4);
    $allAnswers=new AllAnswers($_SESSION["questionNumber"]);
    $theQuestion->alertUsers(-1);
    sleep(2);
    //echo $theQuestion->getLabel();
}

 ?>
