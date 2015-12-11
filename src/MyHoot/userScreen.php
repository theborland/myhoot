<?php
session_start();

require 'dbsettings.php';

if (Answer::checkUserSubmitted($_GET["question"],$_SESSION["user_id"]))
   header("Location: waitingScreen.php?message=".urlencode("come on - you cant submit twice"));

/*
$sql = "SELECT * FROM `games` WHERE game_id ='".$_SESSION["game_id"]."'";
$result = $conn->query($sql);
if ($result)
{
   $row = $result->fetch_assoc();
   $round = $row["round"];
  $type=$row["type"];
}
$conn->close();
*/
?>
 <html>
 <head>
 <script>
 var conn = new ab.Session('ws://<?php echo $pusherIP ?>:8080',
        function() {
            conn.subscribe('Game<?php echo $_SESSION["game_id"] ?>Status', function(topic, data) {
                console.log('Waiting for users:"' + topic + '" : ' + data.title);
            	var container = document.getElementById("waitingDiv");
				container.innerHTML = container.innerHTML  + "<br>"+data.title;
				if (data.title.substring(0,1)=="done")
					window.location.href='waitingScreen.php';
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

 </script>
 </head>
 <body>

   <p>Round <?php echo $_GET["question"] ?>
<img src="http://www.isobudgets.com/wp-content/uploads/2014/03/latitude-longitude.jpg">
   </p>
   <form name="form1" method="post" action="submitAnswer.php">
   <input name="questionNumber" type="hidden" value="<?php echo $_GET["question"] ?>">
     <label for="lat">lat</label>
     <input type="text" name="lat" id="lat">
          <label for="long">long</label>
     <input type="text" name="long" id="long">
   submit
   <input type="submit" name="submit" id="submit" value="Submit">
   </form>
   <p>&nbsp;</p>
 </body>
 </html>
