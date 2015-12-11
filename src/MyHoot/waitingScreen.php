<?php
session_start();

require 'dbsettings.php';

// An array of $_POST keys that are acceptable
$whitelist = array('message');

foreach($whitelist as $key) {
   if (isset($_POST[$key])) {
     $$key = $conn->real_escape_string($_POST[$key]);
   }
   else if (isset($_GET[$key])) {
     $$key = $conn->real_escape_string($_GET[$key]);
   }
   else $$key = "";
}

if (isset($_GET['submit'])&& $_GET['submit']=="Join")
{
  $_SESSION["game_id"] =$_GET['game_id'];
  //$_SESSION["user_id"] =rand (0,111111111);
  $sql = "INSERT INTO `users` (`game_id`, `name`,`score`) VALUES ('".$_GET['game_id']."','".$_GET['name']."', '0')";

  //echo $sql;

  $result = $conn->query($sql);
  $_SESSION["user_id"] =  $conn->insert_id;
  if ($result)
  {
      echo "joined successfully";
  }

    //SOCKET SENDING MESSAGE
    $entryData = array(
        'category' => "Game".$_GET['game_id']
      , 'title'    => $_GET['name']
    );
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://127.0.0.1:5555");
    $socket->send(json_encode($entryData));
    //END SOCKET SENDING

}


 ?>
 <html>
 <head>
 <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
 <script>

        var conn = new ab.Session('ws://<?php echo $pusherIP ?>:8080',
        function() {
            conn.subscribe('Game<?php echo $_SESSION["game_id"] ?>Status', function(topic, data) {
                console.log('Waiting for users:"' + topic + '" : ' + data.title);
            	var container = document.getElementById("waitingDiv");
				container.innerHTML = container.innerHTML  + "<br>"+data.title;
				if (data.title.substring(0,1)=="Q")
					window.location.href='userScreen.php?question='+data.title;
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
</script>
 </head>
 <body><?php echo $message . "<br>"; ?>
   <div id="waitingDiv">We are waiting</div>
 </body>
 </html>
