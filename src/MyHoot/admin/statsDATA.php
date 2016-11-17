<?php
$whitelist = array('users');

require '../controller/dbsettings.php';

$yest= date('Y-m-d');
$last=findLastGame();
$sql = "SELECT DATE((time)) AS time, COUNT(*) AS NumPosts, GROUP_CONCAT(DISTINCT `game_id` SEPARATOR ',') AS ids FROM games WHERE time>'$last 23:59' GROUP BY DATE((time))  ORDER BY time";
$result = $conn->query($sql);
//echo $sql;
if ($result)
{
    $i=0;
    $numResults = $result->num_rows;
    while ($row = $result->fetch_assoc()){
      //die ($sql);
      $time=$row["time"];
      $numPosts=$row["NumPosts"];
      if ($users<1)
      insertEntry($time,$numPosts,"games");
      if ($users==1){
        $numPosts=findTotalUsers($row["ids"]);
        insertEntry($time,$numPosts,"users");
      }
      if ($users==2){
        $numPosts=findTotalAnswers($row["ids"]);
        insertEntry($time,$numPosts,"answers");
      }
      //echo  '{"c":[{"v":"'.$time.'","f":null},{"v":'.$numPosts.',"f":null}]}';
      $i++;
      //if ($i!=$numResults)
        //echo ',';
    }
}


?>
{
  "cols": [
        {"id":"","label":"Time","pattern":"","type":"string"},
        {"id":"","label":"Users","pattern":"","type":"number"}
      ],
  "rows": [
  <?php
  if ($users<1)
  $type="games";

  if ($users==1){
    $type="users";
  }
  if ($users==2){
    $type="answers";
  }

  $sql = "SELECT * FROM stats WHERE `type`='$type' ORDER BY date ASC";
  $result = $conn->query($sql);
  echo $sql;
  if ($result)
  {
      $i=0;
      while ($row = $result->fetch_assoc()){
        //die ($sql);
        $time=$row["date"];
        $numPosts=$row["value"];
        echo  '{"c":[{"v":"'.$time.'","f":null},{"v":'.$numPosts.',"f":null}]}';
        $i++;
        if ($i!=$numResults)
          echo ',';
  //  {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
        }
   }
   ?>


      ]

<?php
$sql = "DELETE FROM `stats` WHERE date=$yest ;";
$result = $conn->query($sql);

function findLastGame(){
  global $conn;

      $sql = "SELECT date FROM stats ORDER by date DESC limit 1";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      return $row["date"];
  }
function insertEntry($date,$count,$type){
  global $conn;
  $sql = "INSERT INTO `stats` (`id`, `date`, `type`, `value`) VALUES (NULL, '$date', '$type', '$count');";
  $result = $conn->query($sql);
}
function findTotalUsers($ids){
  global $conn;
  $count=0;
  $allID=explode(",",$ids);
  foreach ($allID as $key => $value) {
      $sql = "SELECT COUNT(*) AS num FROM users WHERE game_id=$value";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $count+=$row["num"];
  }


  return $count;
}

function findTotalAnswers($ids){
  global $conn;
  $count=0;
  $allID=explode(",",$ids);
  foreach ($allID as $key => $value) {
      $sql = "SELECT COUNT(*) AS num FROM answers WHERE game_id=$value";
      $result = $conn->query($sql);
      //echo $sql;
      $row = $result->fetch_assoc();
      $count+=$row["num"];
  }


  return $count;
}
?>
