<?php

class User{
	var $name;
	var $id;
	var $totalPoints;
	var $color;
	var $place;
	var $avg;
	var $gamesPlayed;
	var $singleStatsRound;
	var $singleStatsGame;


	function __construct($name,$id){
		$this->name=$name;
		$this->id=$id;
	}

	public static function createUser($game_id,$name){
		global $conn;
		$_SESSION["name"] =$name;

		$table="users";
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
			$table="usersSingle";
		else {
			$sql = "SELECT * from `users` WHERE `game_id`= '".$game_id."' AND `name`='".$name."'";
			$result = $conn->query($sql);
			//if ($game_id=="45920285")die($sql);
			if ($result->num_rows>0 || $name=="")
			   return false;
		}
	//echo "😀";
    $color=Game::getColor();
		$sql = "UPDATE games SET numOfUsers = numOfUsers+1 WHERE game_id = '".$game_id."'";
		$result = $conn->query($sql);
    $sql = "INSERT INTO `$table` (`game_id`, `name`,`color`) VALUES ('".$game_id."','".$name."','".$color."')";
//mb_internal_encoding("UTF-8");
//echo "😀"; INSERT INTO `MyHoot`.`users` (`user_id`, `game_id`, `name`, `round`, `score`, `color`) VALUES ('51', '51', '😀', NULL, NULL, '')

//	die ($sql);
//	echo mb_internal_encoding();

		$result = $conn->query($sql);
		$_SESSION["user_id"] =  $conn->insert_id;

	 //echo mysqli_info($conn);
	//	die ("s".$conn->error . " ". $sql);
		//SOCKET SENDING MESSAGE
		if (!isset($_SESSION["single"]) || $_SESSION["single"]!=true){
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
	  }
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

	public static function updateUser($userID,$questionNumber,$place){
		  global $conn;

			$sql = "SELECT * from `usersSingle` WHERE `user_id`= '".$userID."'";
			$result = $conn->query($sql);
			if ($result)
			{
				$row = $result->fetch_assoc();
				$lastRound=$row["round"];
				$gamesPlayed=$row["gamesPlayed"];
				$avg=$row["avg"];
				if ($lastRound!=$questionNumber){
					$gamesPlayed++;
					$newAvg=round(($avg*($gamesPlayed-1)+$place)/$gamesPlayed,2);
					$sql = "UPDATE `usersSingle` SET `score`='".$place."', `gamesPlayed` = '".$gamesPlayed."', `avg` = '".$newAvg."', `round` = '".$questionNumber."' WHERE user_id = '".$userID."'";
					$result = $conn->query($sql);
					//die ($sql);
					return $newAvg;
				}
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

		public static function loadUserSingle(){
			global $conn;
			$sql = "SELECT * from `usersSingle` WHERE `user_id`= '".$_SESSION['user_id']."' AND round='".abs($_SESSION["questionNumber"])."'";
			$result = $conn->query($sql);
			if ($result){
		    $row = $result->fetch_assoc();
				$user=new self($row["name"],$_SESSION['user_id']);
				$user->place=$row["score"];
				$user->avg=$row["avg"];
				$user->gamesPlayed=$row["gamesPlayed"];
				$user->singleStatsRound=new SingleStats($user->place,"score");
				$user->singleStatsGame=new SingleStats($user->avg,'avg');
				return $user;
			}
			else return new self($_SESSION["name"],$_SESSION['user_id']);;
		}

}

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function get_random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

class SingleStats{
	var $topThree;
	var $place;
	var $numOfPlayers;

	function __construct($place,$type){
		global $conn;
		 $sql = "SELECT * from `usersSingle` WHERE round='".abs($_SESSION["questionNumber"])."' ORDER BY $type DESC LIMIT 3";
		 //die ($sql);
		 $result = $conn->query($sql);
		 $this->topThree=array();
		 if ($result){
			 $i=0;
			 while ($row = $result->fetch_assoc()){
				 //die ($sql);
				 $this->topThree[$i]=new User($row["name"],$i);
				 $this->topThree[$i]->place=$row[$type];
			 }
		 }
		 //die($sql);
		 $place=$place+.01;
		 $sql = "select count(*) total, sum(case when $type > '$place' then 1 else 0 end) worse from `usersSingle` WHERE round='".abs($_SESSION["questionNumber"])."'";
		 $result = $conn->query($sql);
		 //die($sql);
		 if ($result)
		 {
				 $row = $result->fetch_assoc();
				 $this->numOfPlayers=$row ['total'];
				 $this->place=$row['worse']+1;


		 }
	}

}
 ?>
