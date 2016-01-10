<?php
class Answer
{
	public $location;
	public $name;
	var $userID;
	var $qID;
	var $questionNum;
	public $distanceAway;
	var $totalMiles;
	var $totalPoints;
	var $roundPoints;
	var $value;
	public static function loadCorrect($questionNum){

		global $conn;
		$sql = "SELECT * FROM `questions` WHERE gameid ='".$_SESSION["game_id"]."' AND questionNum='".$questionNum."'";
		$result = $conn->query($sql);
		//echo $sql;
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				//echo $this->location->lat;
				$answer=new self();
				$answer->location=new LatLong($row["lat"],$row["longg"]);
				$answer->questionNum=$questionNum;
				$answer->name=$row["wording"];
				$answer->value=$row["answer"];
				return $answer;
			}
		}
	}



	public static function checkUserSubmitted($questionNum,$user_id){
		global $conn;
		$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".substr($questionNum,0)."' AND user_id='".$user_id."'";
		$result = $conn->query($sql);
		//echo $sql;
		if ($result)
		if($row = $result->fetch_assoc()){
			//echo "here";
			return true;

		}


		return false;
	}

	public static function addUser($qID,$loc,$ans,$userID,$correct,$points)
	{
		$answer=new self();
		$answer->user_id = $userID;
		$answer->location=$loc;
		$answer->qID=$qID;
		if (Game::findGame()->type=="geo")
			$answer->distanceAway=LatLong::findDistance($correct->location,$loc);
		else
		  $answer->distanceAway=abs($ans-$correct->value);
		$answer->getUserInfo();
		$answer->updateUser();
		$answer->roundPoints=$points;
		return $answer;
	}

	public function getUserInfo(){
		global $conn;
		$sql = "SELECT * FROM `users` WHERE user_id ='".$this->user_id."'";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			$this->name= $row["name"];
			$this->totalMiles= $row["score"]+$this->distanceAway;
	  	//$this->totalPoints= $row["totalPoints"];
		}
	}

	public function updateAnswer($points)
	{
		global $conn;
		$sql = "UPDATE `answers` SET points='".$points."' WHERE id='".$this->qID."'";
		$this->roundPoints=$points;
		$result = $conn->query($sql);
	}

	public function updateUser()
	{
		global $conn;
		$sql = "UPDATE `users` SET score='".$this->totalMiles."' WHERE user_id='".$this->user_id."'";
		$result = $conn->query($sql);
	}

	static function sortMiles($a,$b){
		return $a->distanceAway-$b->distanceAway;
	}
	static function sortTotalMiles($a,$b){
		return $b->totalPoints-$a->totalPoints;
	}
}
?>
