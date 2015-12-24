<?php 
session_start();
require 'dbsettings.php';
     Question::InQuestion($_GET["question"]);
?>