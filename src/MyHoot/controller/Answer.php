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
				if ($points==null)$points=0;
				$color=$row["color"];
				$avg=$row["avg"];
				$this->allAnswers[$user_id]=Answer::addUser($qID,new LatLong($lat1,$long1),$ans,$user_id,$this->correctAns,$points,$color,$avg);

				//$submitTime= $row["submitTime"];
				//$miles=round(LatLong::distance($lat,$long,$lat1,$long1,"M"));
				//echo "<bR>user: ".getUserName($user_id) . " was : ".$miles ." miles away.  Submitted at time " . $submitTime ;
			}
		}

		$this->awardPoints();
    //Question::alertUsers("-1");

		//echo "loc is ".$this->correctAns->location->lat;
	}

	//this will find the zoom level for the map when shown
	public function zoomLevel(){
		  $maxAway=0;
			foreach($this->allAnswers as $key=>$answer){
				if ($answer->distanceAway>$maxAway){
					//$maxAway++;
					$maxAway=$answer->distanceAway;
				}
			}
			//return $maxAway;
			if ($maxAway<40)return 8;
			else if ($maxAway<150)return 7;
			else if ($maxAway<300)return 6;
			else if ($maxAway<900)return 5;
			else if ($maxAway<2500)return 4;
			else if ($maxAway<6000)return 3;
			else return 2;
	}


	public function awardPoints(){
		if (Game::findGame()->round>0){
			if (!isset($_SESSION["single"]) || $_SESSION["single"]!=true)
					$this->fillMissingAnswers();
			usort($this->allAnswers, array("Answer", "sortMiles"));
			$totalPoints=count($this->allAnswers);
			foreach ($this->allAnswers as $key=>$answer){
				$answer->updateAnswer($totalPoints--);
			}
			Game::updateRound(-1*$_SESSION["questionNumber"]);
		}
	}

	public function findPlace($id){
		$place=1;
		foreach ($this->allAnswers as $key=>$answer)
		{
			//echo "id is ".$answer->user_id ." and ". $answer->distanceAway;
			if ($answer->user_id==$id)
			   return $place;
			else $place++;
		}
	}

	//in case a user does not submit on time
	public function fillMissingAnswers()
	{
			 global $conn;
		   $allUsers=array();
			 $sql = "SELECT * from `users` WHERE `game_id`= '".$_SESSION['game_id']."'";
	 		 $result = $conn->query($sql);
	     //$row = $result->fetch_assoc();
			 //echo $sql. " rea".$result;
			 //print_r($this->allAnswers);
			 while($row = $result->fetch_assoc()){
				// echo $sql;
				 $name = $row["name"];
				 $user_id = $row["user_id"];
				 $color=$row["color"];
				 $ans=-999;
				 if (!array_key_exists($user_id,$this->allAnswers))
				 {
					  Answer::addAnswer($user_id,$_SESSION["questionNumber"],-99,-99,$ans,-1,$color,"no");
				 		$this->allAnswers[]=Answer::addUser($_SESSION["questionNumber"],new LatLong(-99,-99),$ans,$user_id,0,0,$color);
				 }
			 }

	}

	public function getTP(){
    //usort($this->allAnswers, array("Answer", "sortMiles"));
		foreach ($this->allAnswers as $key=>$answer){
			//echo "<br>".$answer->name. " has " . User::getTP($answer->user_id) . " total Points";
			$answer->totalPoints=User::getTP($answer->user_id) ;
			if ($answer->totalPoints==0 || $answer->name=="" )
			   unset($this->allAnswers[$key]);
		}
		usort($this->allAnswers, array("Answer", "sortTotalMiles"));

	}

	public function getMin(){
		$min=$this->correctAns->value;
		foreach ($this->allAnswers as $key=>$answer){
			if ($min>$answer->ans && $answer->ans!=-999)
			   $min=$answer->ans;
		}
		return $min;
	}
	public function getMax(){
		$max=$this->correctAns->value;
		foreach ($this->allAnswers as $key=>$answer){
			if ($max<$answer->ans  && $answer->ans!=-999)
			   $max=$answer->ans;
		}
		return $max;
	}


	public function getLocations(){
		$returnString="[";
		$i=0;
		foreach($this->allAnswers as $key=>$answer){
			$returnString.="['".addslashes($answer->name)."', ".$answer->location->lat.", ".$answer->location->longg.", '".$answer->color."']";
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
	var $user_id;
	var $qID;
	var $questionNum;
	public $distanceAway;
	var $totalMiles;
	var $totalPoints;
	var $roundPoints;
	var $value;
	var $color;
	var $avg;

	public static function addAnswer($userID,$questionNumber,$lat,$long,$answer,$distanceAway,$color,$type)
	{
			global $conn;
			$sql=" Select 1 from `answers` WHERE `game_id`='$_SESSION[game_id]' AND `user_id`='$userID' AND `questionNum`='$questionNumber'";
			//echo $sql;
			//die();
			$result = $conn->query($sql);
			if ($result->num_rows == 0)
			{
				$question_id=$type.Question::findQID($questionNumber);
			  $sql = "INSERT INTO `answers` (`game_id`,`user_id`,`questionNum`,`lat`,`longg`,`answer`,`distanceAway`,`color`,`question_id`) VALUES
			   ('$_SESSION[game_id]' ,'$userID','$questionNumber','$lat','$long','$answer','$distanceAway','$color','$question_id')";
			  //echo $sql;
			  //die();
			  $result = $conn->query($sql);

				//now find place
				$sql = "select question_id, count(*) total, sum(case when distanceAway >= '$distanceAway' then 1 else 0 end) worse from `answers` WHERE question_id='$question_id' AND distanceAway>=0";

				$result = $conn->query($sql);
				//die($sql);
	  		if ($result)
	  		{
	          $row = $result->fetch_assoc();

						if ($row ['total']>5){
							$place=round($row['worse']/$row['total']*100,1);
							$sql = "UPDATE `answers` SET `avg`='$place' WHERE `game_id`='$_SESSION[game_id]' AND `user_id`='$userID' AND `questionNum`='$questionNumber'";
						  $result = $conn->query($sql);
	  					return $place;
						}
						else return 0;

	  		}


			}
			else return -1;

	}

	public static function loadCorrect($questionNum){

		global $conn;
		$table="questions";
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
			$table="questionsSingle";
		$sql = "SELECT * FROM `$table` WHERE gameid ='".$_SESSION["game_id"]."' AND questionNum='".$questionNum."'";
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

	public static function addUser($qID,$loc,$ans,$userID,$correct,$points,$color,$avg=null)
	{
		$answer=new self();
		$answer->user_id = $userID;
		$answer->avg=$avg;
		$answer->location=$loc;
		$answer->ans=$ans;
		$answer->qID=$qID;
		$answer->color=$color;
		if ($ans==-999)//meaning they didnt submit
			$answer->distanceAway=-999.99;
		else if (!is_object($correct))//meaning end of game
			$answer->distanceAway=-999.99;
		else if (Game::findGame()->type=="geo" || Game::findGame()->type=="pt" || Game::findGame()->type=="places")
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
		$table="users";
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
			 $table="usersSingle";
		$sql = "SELECT * FROM `$table` WHERE user_id ='".$this->user_id."'";
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
		if ($this->ans==-999)$points=0;//meaning they didnt submit
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
