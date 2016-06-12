<?php

class User{
	var $name;
	var $id;
	var $totalPoints;
	var $color;
	function __construct($name,$id){
		$this->name=$name;
		$this->id=$id;
	}

	public static function createUser($game_id,$name){
		global $conn;
		$_SESSION["name"] =$name;

		$sql = "SELECT * from `users` WHERE `game_id`= '".$game_id."' AND `name`='".$name."'";
		$result = $conn->query($sql);
		//die ($sql);
		if ($result->num_rows>0 || $name=="")
		   return false;
	//echo "😀";
    $color=Game::getColor();
		$sql = "UPDATE games SET numOfUsers = numOfUsers+1 WHERE game_id = '".$game_id."'";
		$result = $conn->query($sql);
    $sql = "INSERT INTO `users` (`game_id`, `name`,`color`) VALUES ('".$game_id."','".$name."','".$color."')";
//mb_internal_encoding("UTF-8");
//echo "😀"; INSERT INTO `MyHoot`.`users` (`user_id`, `game_id`, `name`, `round`, `score`, `color`) VALUES ('51', '51', '😀', NULL, NULL, '')

//	die ($sql);
//	echo mb_internal_encoding();

		$result = $conn->query($sql);
		$_SESSION["user_id"] =  $conn->insert_id;
		
	 //echo mysqli_info($conn);
	//	die ("s".$conn->error . " ". $sql);
		//SOCKET SENDING MESSAGE
		$entryData = array(
			'category' => "Game".$game_id
			, 'title'    => stripslashes($name)
				, 'color'    => $color
		);
		//print_r($entryData);
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
			//zecho $id .';'.$row['sum(`points`)']."<br>";
			if ($row['sum(`points`)']>0)
			return $row['sum(`points`)'];
			else return 0;

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
