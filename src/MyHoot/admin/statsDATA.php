<?php
$whitelist = array('users');

require '../controller/dbsettings.php';

?>
{
  "cols": [
        {"id":"","label":"Time","pattern":"","type":"string"},
        {"id":"","label":"Users","pattern":"","type":"number"}
      ],
  "rows": [
  <?php

  $sql = "SELECT DATE((time)) AS time, COUNT(*) AS NumPosts, GROUP_CONCAT(DISTINCT `game_id` SEPARATOR ',') AS ids FROM games GROUP BY DATE((time)) ORDER BY time";
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
        if ($users==1)
          $numPosts=findTotalUsers($row["ids"]);
        if ($users==2)
          $numPosts=findTotalAnswers($row["ids"]);
        echo  '{"c":[{"v":"'.$time.'","f":null},{"v":'.$numPosts.',"f":null}]}';
        $i++;
        if ($i!=$numResults)
          echo ',';
      }
  }
  //  {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
   ?>


      ]
}
<?php
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
      $row = $result->fetch_assoc();
      $count+=$row["num"];
  }


  return $count;
}
?>
