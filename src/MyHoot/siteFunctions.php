<?php
class AllAnswers
{
	public $allAnswers;
	public $correctAns;

	function __construct($questionNum)
	{
		$this->allAnswers=array();
		$this->correctAns=Answer::loadCorrect($questionNum);
		global $conn;
		$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".$_SESSION["questionNumber"]."'";
		//echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			while($row = $result->fetch_assoc()){
				$lat1 = $row["lat"];
				$long1 = $row["longg"];
				$user_id = $row["user_id"];
				$qID=$row["id"];
				$points=$row["points"];
				$this->allAnswers[]=Answer::addUser($qID,new LatLong($lat1,$long1),$user_id,$this->correctAns,$points);

				//$submitTime= $row["submitTime"];
				//$miles=round(LatLong::distance($lat,$long,$lat1,$long1,"M"));
				//echo "<bR>user: ".getUserName($user_id) . " was : ".$miles ." miles away.  Submitted at time " . $submitTime ;
			}
		}

		$this->awardPoints();


		//echo "loc is ".$this->correctAns->location->lat;
	}

	public function awardPoints(){
		if (Game::findRound()!=-1){
				usort($this->allAnswers, array("Answer", "sortMiles"));
				$totalPoints=count($this->allAnswers);
				foreach ($this->allAnswers as $key=>$answer){
					$answer->updateAnswer($totalPoints--);
				}
				//Game::updateRound(-1);
		}
	}

	public function getTP(){
		usort($this->allAnswers, array("Answer", "sortMiles"));
		foreach ($this->allAnswers as $key=>$answer){
			//echo "<br>".$answer->name. " has " . User::getTP($answer->user_id) . " total Points";
				$answer->totalPoints=User::getTP($answer->user_id) ;
		}
	}

	public function getLocations(){
		$returnString="[";
		$i=0;
		foreach($this->allAnswers as $key=>$answer){
			$returnString.="['".$answer->name."', ".$answer->location->lat.", ".$answer->location->longg.", 4]";
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
	public $name;
	var $userID;
	var $qID;
	var $questionNum;
	public $distanceAway;
	var $totalMiles;
	var $totalPoints;
	var $roundPoints;
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
				return $answer;
			}
		}
	}



	public static function checkUserSubmitted($questionNum,$user_id){
		global $conn;
		$sql = "SELECT * FROM `answers` WHERE game_id ='".$_SESSION["game_id"]."' AND questionNum='".substr($questionNum,1)."' AND user_id='".$user_id."'";
		$result = $conn->query($sql);
		//   echo $sql;
		if ($result)
		if($row = $result->fetch_assoc()){
			//echo "here";
			return true;

		}


		return false;
	}

	public static function addUser($qID,$loc,$userID,$correct,$points)
	{
		$answer=new self();
		$answer->user_id = $userID;
		$answer->location=$loc;
		$answer->qID=$qID;
		$answer->distanceAway=LatLong::findDistance($correct->location,$loc);
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
		}
	}

	public function updateAnswer($points)
	{
		global $conn;
		$sql = "UPDATE `answers` SET points='".$points."' WHERE id='".$this->qID."'";
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
}
class Question
{
	var $city;
	var $country;
	function __construct(){
		$_SESSION["questionNumber"]++;
		$this->alertUsers();
		$this->getLocation();
		$this->addAnswer();
		Game::updateRound($_SESSION["questionNumber"]);
	}

	function alertUsers(){
		//SOCKET SENDING MESSAGE
		$entryData = array(
			'category' => "Game".$_SESSION["game_id"]."Status"
			, 'title'    => "Q".$_SESSION["questionNumber"]
		);
		$context = new ZMQContext();
		$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
		$socket->connect("tcp://localhost:5555");
		$socket->send(json_encode($entryData));
		//END SOCKET SENDING
	}

	function getLocation(){
		$csvData = file_get_contents("https://raw.githubusercontent.com/icyrockcom/country-capitals/master/data/country-list.csv");
		$lines = explode(PHP_EOL, $csvData);
		$array = array();
		foreach ($lines as $line) {
			$array[] = str_getcsv($line);
		}
		$randEntry=rand(1,sizeof($array));
		$this->city=urlencode($array[$randEntry][1]);//.','.$array[$randEntry][0]);
		$this->country=urlencode($array[$randEntry][0]);//.','.$array[$randEntry][0]);
	}

	function getImage(){
		$url='http://en.wikipedia.org/w/api.php?action=query&titles='.$this->city.'&prop=pageimages&format=json&pithumbsize=1000';
		//echo $url;
		$jsonData =file_get_contents( $url);
		$phpArray = json_decode($jsonData);
		//print_r($phpArray);
		$image=findSource($phpArray);
		return $image;
	}

	function addAnswer(){
		global $conn;
		//$latLong=new LatLong();
		$latLong=LatLong::findLatLong($this->city);
		$sql = "INSERT INTO `questions` (`gameID`, `questionNum`, `wording`, `lat`, `longg`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','$this->city', '".$latLong->lat."', '".$latLong->longg."')";
		$result = $conn->query($sql);
	}
}
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
		$_SESSION["game_id"] =$game_id;
		$_SESSION["name"] =$name;
		$sql = "INSERT INTO `users` (`game_id`, `name`) VALUES ('".$_GET['game_id']."','".$_GET['name']."')";
		$result = $conn->query($sql);
		$_SESSION["user_id"] =  $conn->insert_id;
		if ($result)
		{
			echo "joined successfully";
		}
		//SOCKET SENDING MESSAGE
		$entryData = array(
			'category' => "Game".$game_id
			, 'title'    => $name
		);
		$context = new ZMQContext();
		$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
		$socket->connect("tcp://127.0.0.1:5555");
		$socket->send(json_encode($entryData));
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

}

class Game
{
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

	public static function  updateRound($val)
	{
		global $conn;
		$sql = "UPDATE `games` SET `round`='$val' WHERE `game_id` = '".$_SESSION["game_id"]."'";
		$result = $conn->query($sql);
	}

	public static function findRound()
	{
		global $conn;
		$sql = "SELECT * FROM `games` WHERE `game_id` = '".$_SESSION["game_id"]."'";
    //echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			return $row["round"];
		}
	}
}
	class LatLong
	{
		public $lat;
		public $longg;

		public function __construct($lat,$longg) {
			$this->lat=$lat;
			$this->longg=$longg;
		}


		public static function findLatLong ($address)
		{

			$prepAddr = str_replace(' ','+',$address);
			$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
			$output= json_decode($geocode);
			$instance = new self($output->results[0]->geometry->location->lat,$output->results[0]->geometry->location->lng);
			return $instance;
		}

		/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
		/*::                                                                         :*/
		/*::  This routine calculates the distance between two points (given the     :*/
		/*::  latitude/longitude of those points). It is being used to calculate     :*/
		/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
		/*::                                                                         :*/
		/*::  Definitions:                                                           :*/
		/*::    South latitudes are negative, east longitudes are positive           :*/
		/*::                                                                         :*/
		/*::  Passed to function:                                                    :*/
		/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
		/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
		/*::    unit = the unit you desire for results                               :*/
		/*::           where: 'M' is statute miles (default)                         :*/
		/*::                  'K' is kilometers                                      :*/
		/*::                  'N' is nautical miles                                  :*/
		/*::  Worldwide cities and other features databases with latitude longitude  :*/
		/*::  are available at http://www.geodatasource.com                          :*/
		/*::                                                                         :*/
		/*::  For enquiries, please contact sales@geodatasource.com                  :*/
		/*::                                                                         :*/
		/*::  Official Web site: http://www.geodatasource.com                        :*/
		/*::                                                                         :*/
		/*::         GeoDataSource.com (C) All Rights Reserved 2015		   		     :*/
		/*::                                                                         :*/
		/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
		public static function findDistance($loc1,$loc2){
			return LatLong::distance($loc1->lat,$loc1->longg,$loc2->lat,$loc2->longg,"M");
		}
		public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return round($miles);
			}
		}
	}

	function findSource ($phpArray)
	{
		foreach ($phpArray as $key => $value) {
			if (is_object($value))
			return findSource($value);
			else {
				if ($key=="source")
				return $value;
			}

		}
	}
	?>
