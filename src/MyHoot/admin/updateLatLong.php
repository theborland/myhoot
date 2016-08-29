<?php
//this will update question id for all those missing it
require '../controller/dbsettings.php';
$db='data-geo-places';
$sql = "SELECT * FROM `$db` WHERE lat='0' ORDER BY RAND() LIMIT 10 ";
//echo $sql;
$result = $conn->query($sql);
if ($result)
{
  while($row = $result->fetch_assoc()){
    $id=$row["id"];
  /*  $city = $row["city"];

    $country = $row["country"];
*/
//now for products
/*
 $citycountry = $row["city"];
 $splits=explode(',',$citycountry);
 $city=$splits[0];
 $country=$splits[1];
 */
 //now for places
 $city=$row["place"];
 $country=$row["country"];

    $latLong=LatLong::findLatLong($city,$country);
    //echo $city.$latLong->lat . "<br>";
     $sql2 = "UPDATE `$db` SET lat='".$latLong->lat."' , longg='".$latLong->longg."' WHERE id='".$id."'";

    echo $sql2;
    $result2 = $conn->query($sql2);
  }
}

header('Refresh: 5; URL=updateLatLong.php');


?>
