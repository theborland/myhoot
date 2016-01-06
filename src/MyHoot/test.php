<?php
session_start();
require 'dbsettings.php';

global $conn;
$sql = "SELECT * FROM `data-geo`";//" WHERE `id`='3'";
$result = $conn->query($sql);
if ($result)
{
  if($row = $result->fetch_assoc()){
    $url= $row["url"];
    $url=substr($url,2,strlen($url)-3);
    $splits=explode("', '",$url);
    //echo $url;
    echo $splits[rand(0,sizeof($splits))];
  }
}
echo "<br>";
$url='http://api.wunderground.com/api/766deb6baf5fc335/almanac/conditions/forecast/q/Georgia/Atlanta.json';
$jsonData =file_get_contents( $url);
$phpArray = json_decode($jsonData,true);
print_r($phpArray["almanac"]["temp_high"]["normal"]["F"]);
?>
