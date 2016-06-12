<?php
//this will update question id for all those missing it
require '../controller/dbsettings.php';

$sql = "SELECT * FROM `answers` WHERE question_id=''";
//echo $sql;
$result = $conn->query($sql);
if ($result)
{
  while($row = $result->fetch_assoc()){
    $lat1 = $row["lat"];
    $long1 = $row["longg"];
    $color=$row["color"];
    $ans = $row["answer"];
    $game_id = $row["game_id"];
    $questionNum =$row["questionNum"];
    $loc=new LatLong($lat1,$long1);
    $points=$row["points"];
    //echo $questionNum;
    $qID=findQID($game_id,$questionNum);
    if ($ans==-999)//meaning they didnt submit
      $distanceAway=null;
    else if ($qID->type=="geo")
      $distanceAway=LatLong::findDistance($qID->location,$loc);
    else
      $distanceAway=abs($ans-$qID->answer);
    if ($ans>100000)
       $distanceAway=round($distanceAway,-5);
    //echo $qID->type." ".$qID->answer. " ". $distanceAway."<br>";
    if ($distanceAway!="")
      $sql2 = "UPDATE `answers` SET question_id='".$qID->type.$qID->id."', distanceAway='".$distanceAway."' WHERE game_id='".$game_id."' AND questionNum='".$questionNum."' AND color='".$color."'";
    else
      $sql2 = "UPDATE `answers` SET question_id='".$qID->type.$qID->id."' WHERE game_id='".$game_id."' AND questionNum='".$questionNum."' AND color='".$color."'";

    echo $sql2;
    $result2 = $conn->query($sql2);
  }
}

function findQID($game_id,$questionNum){
  global $conn;
  $sql = "SELECT * FROM `questions` WHERE gameid ='".$game_id."' AND questionNum='".$questionNum."'";
  $result = $conn->query($sql);
  echo $sql;
  if ($result)
  {
    if($row = $result->fetch_assoc()){
      //echo $this->location->lat;
      /*
      $answer=new self();
      $answer->location=new LatLong($row["lat"],$row["longg"]);
      $answer->questionNum=$questionNum;
      $answer->name=$row["wording"];
      $answer->value=$row["answer"];*/
      $word=$row["wording"];
      $type=$row["type"];
      $id=findID($type,$word);
      if ($type=="geo")
          $id->location=new LatLong($row["lat"],$row["longg"]);
      else {
          $id->answer=$row["answer"];
      }
      return $id;
      //return $type.$id;
    }
  }
}

function findID($type,$word){
  global $conn;
  $word=addslashes($word);
  if ($type=="geo" || $type=="weather" || $type=="pop"){
    $words=explode(",",$word);
    $sql = "SELECT * FROM `data-geo` WHERE city='".$words[0]."' AND  country='".$words[1]."'";
    //echo $sql . "<br>";
  }
  if ($type=="rand"){
    $words=explode(",",$word);
    $sql = "SELECT * FROM `data-rand` WHERE category='".$words[0]."' AND  wording='".$words[1]."'";
  }
  if ($type=="time"){
    $sql = "SELECT * FROM `data-time` WHERE wording='".$word."'";
  }
  if ($type=="age"){
    $sql = "SELECT * FROM `data-people` WHERE name='".$word."'";
  }
  $result = $conn->query($sql);
  //echo $sql;
  if ($result)
  {
    if($row = $result->fetch_assoc()){
      //echo $this->location->lat;
      /*
      $answer=new self();
      $answer->location=new LatLong($row["lat"],$row["longg"]);
      $answer->questionNum=$questionNum;
      $answer->name=$row["wording"];
      $answer->value=$row["answer"];
      if ($type=="time" || $type=="rand"){
          $answer=$row["answer"];
      }
      if ($type=="age"){
          $answer=$row["answer"];
      }
      return new qID($row["id"],$type,$answer);

      return $row["id"];    */
        return new qID($row["id"],$type);
    }
  }
}

class qID{
  public $id;
  public $type;
  public $answer;
  public $location;
  function __construct($a,$b){
    $this->id=$a;
    $this->type=$b;
  //  $this->answer=$c;
  }
}
?>
