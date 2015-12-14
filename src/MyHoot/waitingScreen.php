<?php
session_start();
$whitelist = array('message','submit','name','game_id');
require 'dbsettings.php';

if ($submit=="Join")
     User::createUser($game_id,$name);

 ?>
 <html>
 <head>
 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script src="socketScripts.js"></script>
 <script>
   loadWaitingForQuestion('<?php echo $pusherIP; ?>' ,'<?php echo $_SESSION["game_id"]; ?>');
 </script>

</script>
 </head>
 <body><?php echo $message . "<br>"; ?>
   <div id="waitingDiv">We are waiting</div>
   <a href="checkQuestion.php">Everybody else playing - try joining here</a>
 </body>
 </html>
