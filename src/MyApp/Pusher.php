<?php
namespace MyApp;
require __DIR__ . '/vendor/autoload.php';
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    protected $clients;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    	$this->clients->attach($conn);
    }
    public function onClose(ConnectionInterface $conn) {
        echo "closing connection\n";
      $this->clients->detach($conn);
      $conn->close();
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    protected $subscribedTopics = array();

public function onSubscribe(ConnectionInterface $conn, $topic) {
    $topic->autoDelete = true;
    $this->subscribedTopics[$topic->getId()] = $topic;
    echo "subscribing to : ".$topic . "\n";

}

/**
 * @param string JSON'ified string we'll receive from ZeroMQ
 */
public function onBlogEntry($entry) {
    echo "sending out \n";
    $entryData = json_decode($entry, true);
    // if (isset($entryData['type']) && $entryData['type']=="end")
    // {
    //   var_dump(gc_collect_cycles()); // # of elements cleaned up
    // }
  	//print_r($entryData);
    // If the lookup topic object isn't set there is no one to publish to
    if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
        return;
    }

    $topic = $this->subscribedTopics[$entryData['category']];
    print_r($entryData);
    // re-send the data to all the clients subscribed to that category
    $topic->broadcast($entryData);
}
}
?>
