<?php
session_start();
require 'dbsettings.php';

global $conn;
$sql = "SELECT * FROM `data-geo` WHERE `id`='3'";
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
?>
