<?php
//  tail -f name of file to view in process
// ./script.sh
// crontab -e
date_default_timezone_set('America/New_York');
$datetime1 = strtotime("now");//current datetime object
$datetime2 = mktime(23, 59, 59);//next day at midnight
$timeUntilStop= ($datetime2 - $datetime1);
//die("time is ".$timeUntilStop);


set_time_limit(0);
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
//$gamesSelected=array("places");

Game::createGame(false,true);
$_SESSION["questionNumber"]=getLastQuestion()+1;
echo $_SESSION["questionNumber"]."<Br>Starting";
$counter=0;

while ($timeUntilStop>$lengthOfGame+$lengthOfBreak){
  echo "time is ".$timeUntilStop;
   $counter++;
   echo "$counter \n";
  /*
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
*/
    $current=$gamesSelected[rand(0,count($gamesSelected)-1)];
    echo $current;
    $theQuestion=new Question($current);
    echo "got question";
    if ($current=="age")$current="entertainment";
    if ($current=="pop")$current="facts";
    //$playedGames[]=$current;
    echo $theQuestion->getLabel()."\n";
    $seconds=time();
    while ($seconds%($lengthOfGame+$lengthOfBreak)!=$lengthOfGame)
    {
       sleep(1);
       $seconds=time();
     }
    $allAnswers=new AllAnswers($_SESSION["questionNumber"]);
    $numUsers=sizeof($allAnswers->allAnswers);
    echo "Question:".$_SESSION["questionNumber"]." Num Users:".$numUsers."\n";
    $theQuestion->alertUsers(-1);
    if ($numUsers>0)
      ;//
    else{
      sleep(3);
      $sql = "DELETE FROM `questionsSingle` WHERE `questionNum`='".$_SESSION["questionNumber"]."'";
      $result = $conn->query($sql);
      $_SESSION["questionNumber"]--;
    }
    $theQuestion->alertUsers(-1);

    $seconds=date("s");
    $seconds=time();
    while ($seconds%($lengthOfGame+$lengthOfBreak)!=0)
    {
       sleep(1);
       $seconds=time();
     }
    //sleep(7);
    //echo $theQuestion->getLabel();
    $datetime1 = strtotime("now");//current datetime object
    $datetime2 = mktime(7, 28, 0);//next day at midnight
    $timeUntilStop= ($datetime2 - $datetime1);
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
