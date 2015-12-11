<?php
session_start();

require 'dbsettings.php';


$sql = "SELECT COUNT(*)  FROM `users` WHERE game_id ='".$_SESSION["game_id"]."'";
$result = $conn->query($sql);
if ($result)
{
  $row = $result->fetch_row();
    echo $row[0];
}
else echo "0";
$conn->close();
?>
