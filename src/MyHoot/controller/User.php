<?php

class User{
	var $name;
	var $id;
	var $totalPoints;
	function __construct($name,$id){
		$this->name=$name;
		$this->id=$id;
	}
	public static function createUser($game_id,$name){
		global $conn;
		$_SESSION["name"] =$name;
		$sql = "SELECT * from `users` WHERE `game_id`= '".$_GET['game_id']."' AND `name`='".$_GET['name']."'";
		$result = $conn->query($sql);
		//die ($sql);
		if ($result->num_rows>0 || $name=="")
		   return false;
    $color=Game::getColor();
    $sql = "INSERT INTO `users` (`game_id`, `name`,`color`) VALUES ('".$_GET['game_id']."','".$_GET['name']."','".$color."')";
		//die ($sql);
		$result = $conn->query($sql);
		$_SESSION["user_id"] =  $conn->insert_id;
		//SOCKET SENDING MESSAGE
		$entryData = array(
			'category' => "Game".$game_id
			, 'title'    => $name
		);
		$context = new ZMQContext();
		$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
		$socket->connect("tcp://127.0.0.1:5555");
		$socket->send(json_encode($entryData));
		return true;
		//END SOCKET SENDING
	}

	public function getTotalPoints(){
		global $conn;
		$sql = "	SELECT sum(`points`) FROM `answers` WHERE `user_id`=".$this->id."'";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			$this->totalPoints = $row['sum(points)'];
		}
	}

	public static function getTP($id){
		global $conn;
		$sql = "	SELECT sum(`points`) FROM `answers` WHERE `user_id`='".$id."'";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			return $row['sum(`points`)'];

		}
	}

  public static function getColor(){
		global $conn;
		$sql = "SELECT * from `users` WHERE `game_id`= '".$_SESSION['game_id']."' AND `name`='".$_SESSION['name']."'";
		$result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['color'];

	  }
}

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function get_random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
 ?>
