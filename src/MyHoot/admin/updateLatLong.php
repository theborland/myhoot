<?php
//this will update question id for all those missing it
require '../controller/dbsettings.php';

$sql = "SELECT * FROM `data-geo` WHERE lat='0' LIMIT 10";
//echo $sql;
$result = $conn->query($sql);
if ($result)
{
  while($row = $result->fetch_assoc()){
    $city = $row["city"];
    $id=$row["id"];
    $country = $row["country"];
    $latLong=LatLong::findLatLong($city,$country);
    //echo $city.$latLong->lat . "<br>";
     $sql2 = "UPDATE `data-geo` SET lat='".$latLong->lat."' , longg='".$latLong->longg."' WHERE id='".$id."'";

    echo $sql2;
    $result2 = $conn->query($sql2);
  }
}

header('Refresh: 5; URL=updateLatLong.php');


?>
