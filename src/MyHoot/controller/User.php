<?php

class User{
	var $name;
	var $id;
	var $totalPoints;
	var $color;
	var $place;
	var $avg;
	var $tempAvg;
	var $gamesPlayed;
	var $singleStatsRound;
	var $singleStatsGame;


	function __construct($name,$id){
		$this->name=$name;
		$this->id=$id;
	}

	public static function findGameID(){
		global $conn;
			$sql = "SELECT * from `users` WHERE `ip`= '".session_id()."' ORDER by id DESC";
			$result = $conn->query($sql);
			if ($result)
			{
				$row = $result->fetch_assoc();
				if ($row){
					$_SESSION["game_id"]= $row["game_id"];
					$_SESSION["user_id"]= $row["user_id"];
					$_SESSION["name"]= $row["name"];
				}

			}
			header("index.php");
			die ();
			return null;

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
			//if ($game_id=="45920285")
			//die($sql);
			if ($result->num_rows>0 || $name=="")
			   return false;
		}
	//echo "😀";
    $color=Game::getColor();
		$sql = "UPDATE games SET numOfUsers = numOfUsers+1 WHERE game_id = '".$game_id."'";
		$result = $conn->query($sql);
    $sql = "INSERT INTO `$table` (`game_id`, `name`,`color`,`ip`) VALUES ('".$game_id."','".$name."','".$color."','".session_id() ."')";
//mb_internal_encoding("UTF-8");
//echo "😀"; INSERT INTO `MyHoot`.`users` (`user_id`, `game_id`, `name`, `round`, `score`, `color`) VALUES ('51', '51', '😀', NULL, NULL, '')

	//fdie ($sql);
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

	public static function updateUser($userID,$questionNumber,$distanceAway){
		  global $conn;

			$sql = "SELECT * from `usersSingle` WHERE `user_id`= '".$userID."'";
			$result = $conn->query($sql);
			if ($result)
			{
				$row = $result->fetch_assoc();
				$lastRound=$row["round"];
				$gamesPlayed=$row["gamesPlayed"];
				$avg=$row["avg"];
				$last5Avg=User::findLast5($userID,$questionNumber);
				//echo "avg is ".$last5Avg;
				//die();
				$tempAvg=$last5Avg;
				$gamesPlayed++;
				if (!is_numeric($last5Avg)){
	            $splits=explode("~",$last5Avg);
	            $tempAvg=-$splits[1];
				}
				$sql = "UPDATE `usersSingle` SET `tempAvg` = '".$tempAvg."',`score`='".$distanceAway."', `gamesPlayed` = '".$gamesPlayed."', `round` = '".$questionNumber."' WHERE user_id = '".$userID."'";
				$result = $conn->query($sql);
				//die ($sql);
				if (substr($last5Avg,0,1)!="~" && $last5Avg>$avg){
					$gamesPlayed++;
					//$newAvg=round(($avg*($gamesPlayed-1)+$place)/$gamesPlayed,2);
					$sql = "UPDATE `usersSingle` SET `avg` = '".$last5Avg."' WHERE user_id = '".$userID."'";
					$result = $conn->query($sql);
					//die ($sql);

				}
				return $last5Avg;
			}

		}

		public static function addSkip($userID){
			  global $conn;
				$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND user_id='".$userID."' ORDER by questionNum DESC";
				$result = $conn->query($sql);
				//echo $sql;
				if ($result && $row = $result->fetch_assoc())
				{
						if ($row["avg"]!=0)
				    {
								$sql = "UPDATE `answers` SET avg=0 WHERE game_id ='".$_SESSION["game_id"]."' AND user_id='".$userID."' AND questionNum=".$row["questionNum"];
								$result = $conn->query($sql);
						}
				}

		}
/*
		public static function findTop5Users(){
				global $conn;
				$sql = "SELECT * from `usersSingle` ORDER by avg DESC limit 5";
				$result = $conn->query($sql);
				$top5=array();
				$place=1;
				while ($row = $result->fetch_assoc())
				{
					$user=new self($row["name"],$place);
					$user->avg=$row["avg"];
					$top5[$place]=$user;
					$place++;
				}

				return $top5;
		}
*/
		public static function findLast5($userID,$questionNumber){
				 $last5Avg=0;
				 global $conn;
				 for ($i=$questionNumber;$i>$questionNumber-5;$i--)
				 {
					 $sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".$i."' AND user_id='".$userID."'";
					 $result = $conn->query($sql);
					 //echo $sql;
					 if ($result && $row = $result->fetch_assoc())
					    if ($row["avg"]!=0){
								  echo "sss";
					  			$last5Avg+=$row["avg"];
								}
					 		else {
								 echo "in";
								 $numPlayed=$questionNumber-$i;
								 return "~".$numPlayed."~".($last5Avg/$numPlayed);
							}
					 else
					 {
							echo "in";
							$numPlayed=$questionNumber-$i;
							if ($numPlayed==0)return "~0~0";
							else return "~".$numPlayed."~".($last5Avg/$numPlayed);
					 }
				 }
				 return $last5Avg/5;



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
			$sql = "SELECT * from `usersSingle` WHERE `user_id`= '".$_SESSION['user_id']."'";
			$result = $conn->query($sql);
			if ($result){
		    $row = $result->fetch_assoc();
				$user=new self($row["name"],$_SESSION['user_id']);
				$user->place=$row["score"];
				$user->avg=$row["avg"];
				$user->tempAvg=$row["tempAvg"];
				//print_r($sql);
				//die();
				if ($row["tempAvg"]<0)
					$user->gamesPlayed=-1*$row["tempAvg"];
				$user->singleStatsRound=new SingleStats($user->place,"score",0);
				$user->singleStatsGame=new SingleStats($user->avg,'avg',$user->tempAvg);
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
	var $topFive;
	var $place;
	var $numOfPlayers;
	var $tempPlace;

	function __construct($score,$type,$tempAvg){
		global $conn;
		if ($type=="avg"){
		 $sql = "SELECT * from `usersSingle` ORDER BY `avg` DESC LIMIT 5";
		 $sql2= "select count(*) total, sum(case when `avg` > '$score' then 1 else 0 end) worse from `usersSingle` WHERE `avg`!=0";

	 }
		else {
				$sql = "SELECT * from `usersSingle` WHERE round='".abs($_SESSION["questionNumber"])."' ORDER BY `score` ASC LIMIT 3";
				$sql2= "select count(*) total, sum(case when $type < '$score' then 1 else 0 end) worse from `usersSingle` WHERE round='".abs($_SESSION["questionNumber"])."'";
			}
		//	echo $sql;
		 $result = $conn->query($sql);
		 $this->topFive=array();
		 //echo $sql;
		 if ($result){
			 $i=1;
			 while ($row = $result->fetch_assoc()){
				 //die ($sql);
				 $this->topFive[$i]=new User($row["name"],$i);

				 $this->topFive[$i]->avg=$row[$type];
				 $i++;
				 //if ($type=="avg")echo $sql;
			 }
		 }
		 //die($sql);

		 $result = $conn->query($sql2);
		 //die($sql);
		 //echo $sql2;
		 if ($result)
		 {
				 $row = $result->fetch_assoc();
				 $this->numOfPlayers=$row ['total'];
				 $this->place=$row['worse']+1;


		 }

		 if ($tempAvg>0 && $score!=$tempAvg)
		 {
			 $sql2= "select count(*) total, sum(case when $type < '$tempAvg' then 1 else 0 end) worse from `usersSingle` ";
				$result = $conn->query($sql2);
				$row = $result->fetch_assoc();
				//die ($sql2);
			 $this->tempPlace=$row['worse']+1;
		 }
	}

}
 ?>
