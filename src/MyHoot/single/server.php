<?php

require '../controller/dbsettings.php';
include ('../controller/siteFunctions.php');
include ('../controller/Question.php');
include ('../controller/Game.php');
include ('../controller/Answer.php');
include ('../controller/User.php');

//$sql = "DELETE FROM `questionsSingle`";

$result = $conn->query($sql);


$_SESSION["single"]=true;
$playedGames=array();
$_SESSION["regionsSelected"]=$regionsSelected=array(1,2,3,4,5,6,7,8,9,10,11);
$gamesSelected=array("time","weather","pt","places","places","facts","facts","geo","geo","geo","geo","geo");
$gamesSelected=array("time","weather");

Game::createGame(false,true);
$_SESSION["questionNumber"]=getLastQuestion()+1;
echo $_SESSION["questionNumber"]."<Br>Starting";

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
    echo $theQuestion->getLabel()."\n";
    sleep(7);
    $allAnswers=new AllAnswers($_SESSION["questionNumber"]);
    $numUsers=sizeof($allAnswers->allAnswers);
    echo "Question:".$_SESSION["questionNumber"]." Num Users:".$numUsers."\n";
    if ($numUsers>0)
      $theQuestion->alertUsers(-1);
    else{
      $sql = "DELETE FROM `questionsSingle` WHERE `questionNum`='".$_SESSION["questionNumber"]."'";
      $result = $conn->query($sql);
      $_SESSION["questionNumber"]--;
    }


    sleep(2);
    //echo $theQuestion->getLabel();
}

function getLastQuestion(){
  global $conn;
  $sql = "SELECT * FROM `questionsSingle` WHERE gameid ='1111' ORDER BY `questionNum` DESC LIMIT 1";
  $result = $conn->query($sql);
  if ($result)
  {
      $row = $result->fetch_assoc();
      return $row["questionNum"];
    }
}
 ?>
