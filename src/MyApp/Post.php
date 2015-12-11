<?php
    // post.php ???
    // This all was here before  ;)
    $entryData = array(
        'category' => "Game52456Status"
      , 'title'    => "fff"
      , 'article'  => "sdf"
      , 'when'     => time()
    );


    // This is our new stuff
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    $socket->send(json_encode($entryData));
    //$message = $socket->recv();

  //  echo "<p>Server said: {$message}</p>";
    ?>
