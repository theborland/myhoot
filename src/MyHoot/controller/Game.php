<?php

class Game
{
	var $type;
	var $round;
	public static function createGame()
	{
		global $conn;
		$game_id=rand(10000,99999);
		$_SESSION["game_id"] =$game_id;
		//$_SESSION["game_id"] =$_GET['game_id'];
		//$_SESSION["user_id"] =rand (0,111111111);
		$sql = "INSERT INTO `games` (`game_id`) VALUES ('$_SESSION[game_id]')";
		//echo $sql;

		$result = $conn->query($sql);

		$_SESSION["questionNumber"]=0;
		Game::updateRound(-1);
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
					    $colors=substr($colors,strlen($colors)-1);
					//now update games
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
}
?>
