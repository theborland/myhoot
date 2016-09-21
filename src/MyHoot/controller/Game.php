<?php

class Game
{
	var $type;
	var $round;
	public static function createGame($replay,$game_idSet=null)
	{
		global $conn;
		$game_id=rand(10000,99999);
		if ($game_idSet!=null)
			$game_id=55555;
		if ($replay=="yes")$lastGame_ID=$_SESSION["game_id"];
		date_default_timezone_set('America/New_York');
		//die (date("z"));
		$_SESSION["game_id"]=$game_id.(	str_pad(date("z"), 3, "0", STR_PAD_LEFT));
		//if ($single==true)
		//   $_SESSION["game_id"]*=00;//, $single=false
		//$_SESSION["game_id"] =$_GET['game_id'];
		//$_SESSION["user_id"] =rand (0,111111111);
		$sql = "INSERT INTO `games` (`game_id`) VALUES ('$_SESSION[game_id]')";
		//echo $sql;
		$result = $conn->query($sql);

		$_SESSION["questionNumber"]=0;
		Game::updateRound(-1);

		if ($replay=="yes"){
			//SOCKET SENDING MESSAGE
			$entryData = array(
				'category' => "Game".$lastGame_ID."NextGame"
				, 'title'    => substr($_SESSION["game_id"],0,5)
			);
			$context = new ZMQContext();
			$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
			$socket->connect("tcp://127.0.0.1:5555");
			$socket->send(json_encode($entryData));
			$entryData = array(
				'category' => "Game".$lastGame_ID."Status"
				, 'title'    => substr($_SESSION["game_id"],0,5)
				, 'type'    => "NextGame"
			);
			$socket->send(json_encode($entryData));
		}

	}

	public static function  updateRound($val, $type=NULL)
	{
		global $conn;
		if ($type==NULL)
			$sql = "UPDATE `games` SET `round`='$val' WHERE `game_id` = '".$_SESSION["game_id"]."'";
		else
			$sql = "UPDATE `games` SET `round`='$val', `type`='$type' WHERE `game_id` = '".$_SESSION["game_id"]."'";
		$result = $conn->query($sql);
	}

	public static function getColor()
	{
		global $conn;
		$sql = "SELECT * FROM `games` WHERE `game_id` = '".$_SESSION["game_id"]."' AND CHAR_LENGTH(`colors`)>3";
		$result = $conn->query($sql);
		if ($result)
		{
				$row = $result->fetch_assoc();
				if ($row){
					//die ($sql);
					$colors=$row["colors"];
					$allColors=explode(",",$colors);
					$theColor=$allColors[rand(0,sizeof($allColors)-1)];
					$colors=str_replace($theColor,"",$colors);
					$colors=str_replace(",,",",",$colors);
					if (substr($colors,strlen($colors)-1)==",")
					    $colors=substr($colors,0,strlen($colors)-1);
					if (substr($colors,0,1)==",")
							$colors=substr($colors,1);
					//now update games
					if (strlen($theColor)<2)
						$theColor=get_random_color();
					$sql = "UPDATE `games` SET `colors`='$colors' WHERE `game_id` = '".$_SESSION["game_id"]."'";
				  $result = $conn->query($sql);
					//die ($sql);
					return $theColor;
				}

		}
		return get_random_color();

	}

	public static function findGame()
	{
		global $conn;
		$sql = "SELECT * FROM `games` WHERE `game_id` = '".$_SESSION["game_id"]."'";

		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			if ($row){
				$game=new self();
				$game->round= $row["round"];
				$game->type=$row["type"];
				return $game;
			}
			else {
				 return null;
			}
		}

	}
	public static function questionStatusRedirect(){
		    $game=Game::findGame();
		    $questionNumber=$game->round;
		    if ($questionNumber==null)
		      header( 'Location: joinQuiz.php');
		    else if ($questionNumber==-1)
		      header( 'Location: waitingScreen.php?message='."Sorry, there is no question in progress" ) ;
				else return $questionNumber;
	 }

	 public static function rejectUser($userName){
			global $conn;
			$sql = "DELETE FROM `users` WHERE game_id ='".$_SESSION["game_id"]."'  AND name='".$userName."'";
			$result = $conn->query($sql);
			$entryData = array(
				'category' => "Game".$_SESSION["game_id"]."Status"
				, 'title'    => "Reject"
				, 'type'    => "Reject"
			);
			$context = new ZMQContext();
			$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
			$socket->connect("tcp://localhost:5555");
			$socket->send(json_encode($entryData));
		}

	 public static function getNumberUsers(){
		 	$game=Game::findGame();
			global $conn;
		 	$lastQuestionNumber=$game->round-1;
			if ($lastQuestionNumber>0){
					$sql = "SELECT COUNT(*) as total FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='$lastQuestionNumber' AND points>=1";
					//echo $sql;
					$result = $conn->query($sql);
		  		if ($result){
		          $row = $result->fetch_assoc();
		  				return $row ['total'];
		  		}
			}
			else{
					$sql = "SELECT COUNT(*) as total FROM `users` WHERE game_id ='".$_SESSION["game_id"]."'";
					$result = $conn->query($sql);
					if ($result){
							$row = $result->fetch_assoc();
							return $row ['total'];
					}
			}
			return 999;

	 }
}
?>
