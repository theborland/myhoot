<?php
session_start();
$whitelist = array('name');
require 'controller/dbsettings.php';
Game::rejectUser($name);
?>
