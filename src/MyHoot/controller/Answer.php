<?php
class AllAnswers
{
	public $allAnswers;
	public $correctAns;
  //public $wording;
	function __construct($questionNum)
	{
		$this->allAnswers=array();
		$this->correctAns=Answer::loadCorrect($questionNum);
		global $conn;
		$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".$_SESSION["questionNumber"]."' order by submitTime DESC";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			while($row = $result->fetch_assoc()){
				$lat1 = $row["lat"];
				$long1 = $row["longg"];
				$ans = $row["answer"];
				$user_id = $row["user_id"];
				$qID=$row["id"];
				$points=$row["points"];
				$color=$row["color"];
				$this->allAnswers[]=Answer::addUser($qID,new LatLong($lat1,$long1),$ans,$user_id,$this->correctAns,$points,$color);

				//$submitTime= $row["submitTime"];
				//$miles=round(LatLong::distance($lat,$long,$lat1,$long1,"M"));
				//echo "<bR>user: ".getUserName($user_id) . " was : ".$miles ." miles away.  Submitted at time " . $submitTime ;
			}
		}

		$this->awardPoints();
        Question::alertUsers(-1);

		//echo "loc is ".$this->correctAns->location->lat;
	}




	public function awardPoints(){
		if (Game::findGame()->round!=-1){
			usort($this->allAnswers, array("Answer", "sortMiles"));
			$totalPoints=count($this->allAnswers);
			foreach ($this->allAnswers as $key=>$answer){
				$answer->updateAnswer($totalPoints--);
			}
			Game::updateRound(-1);
		}
	}

	public function getTP(){
    //usort($this->allAnswers, array("Answer", "sortMiles"));
		foreach ($this->allAnswers as $key=>$answer){
			//echo "<br>".$answer->name. " has " . User::getTP($answer->user_id) . " total Points";
			$answer->totalPoints=User::getTP($answer->user_id) ;
		}
		usort($this->allAnswers, array("Answer", "sortTotalMiles"));
    
	}

	public function getMin(){
		$min=$this->correctAns->value;
		foreach ($this->allAnswers as $key=>$answer){
			if ($min>$answer->ans)
			   $min=$answer->ans;
		}
		return $min;
	}
	public function getMax(){
		$max=$this->correctAns->value;
		foreach ($this->allAnswers as $key=>$answer){
			if ($max<$answer->ans)
			   $max=$answer->ans;
		}
		return $max;
	}


	public function getLocations(){
		$returnString="[";
		$i=0;
		foreach($this->allAnswers as $key=>$answer){
			$returnString.="['".$answer->name."', ".$answer->location->lat.", ".$answer->location->longg.", '".$answer->color."']";
			if (++$i!= count($this->allAnswers))
			$returnString.= ",";
		}
		$returnString.="]";
		return $returnString;
	}
}

class Answer
{
	public $location;
	public $ans;
	public $name;
	var $userID;
	var $qID;
	var $questionNum;
	public $distanceAway;
	var $totalMiles;
	var $totalPoints;
	var $roundPoints;
	var $value;
	var $color;
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

	public static function addUser($qID,$loc,$ans,$userID,$correct,$points,$color)
	{
		$answer=new self();
		$answer->user_id = $userID;
		$answer->location=$loc;
		$answer->ans=$ans;
		$answer->qID=$qID;
		$answer->color=$color;
		if (Game::findGame()->type=="geo")
			$answer->distanceAway=LatLong::findDistance($correct->location,$loc);
		else
		  $answer->distanceAway=abs($ans-$correct->value);
		if ($ans>100000)
		   $answer->distanceAway=round($answer->distanceAway,-5);
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
