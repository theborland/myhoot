<?php
session_start();
require 'dbsettings.php';

Game::createGame();

 ?>
 <html>
 <head>
 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
<script>
  loadWaitingForUsers('<?php echo $pusherIP; ?>' ,<?php echo $_SESSION["game_id"]; ?>);    
</script>
 </head>
 <body>
<h1>  Quiz id <?php echo $_SESSION["game_id"] ; ?></h1>

   The number of users is <div id="numUsers">0</div>
      The names of users: <div id="nameUsers"></div>
      <a href="getQuestion.php">Start
 </body>
 </html>
