<?php 
session_start();
require 'controller/dbsettings.php';
     Question::InQuestion($_GET["question"]);
?>