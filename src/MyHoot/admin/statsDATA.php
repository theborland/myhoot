<?php
require '../controller/dbsettings.php';

?>
{
  "cols": [
        {"id":"","label":"Time","pattern":"","type":"string"},
        {"id":"","label":"Users","pattern":"","type":"number"}
      ],
  "rows": [
  <?php

  $sql = "SELECT DATE((time)) AS time, COUNT(*) AS NumPosts FROM games GROUP BY DATE((time)) ORDER BY time";
  $result = $conn->query($sql);
  if ($result)
  {
      $i=0;
      $numResults = $result->num_rows;
      while ($row = $result->fetch_assoc()){
        //die ($sql);
        $time=$row["time"];
        $numPosts=$row["NumPosts"];
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
