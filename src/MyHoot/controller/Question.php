<?php
class Question
{
	var $city;
	var $country;
	var $type;
	var $answer;
	var $image;
	var $min;
	var $max;
	var $timesRepeated=0;

	function __construct($type){
		if ($type!=null)
		{
				$_SESSION["questionNumber"]++;
				$this->type=$type;
				if ($type=="geo")
					  $this->getLocation($type);
			  if ($type=="pop")
						$this->getLocation($type);
				if ($type=="weather")
						$this->getWeather();
				if ($type=="age")
						$this->getAge();
				if ($type=="time")
						$this->getTime();
				if ($type=="rand")
								$this->getRand();
				if ($type=="user")
						$this->getUserQuestion();
				$this->addAnswer();
				//echo "in here again";
				Game::updateRound($_SESSION["questionNumber"],$type);
				$this->alertUsers($_SESSION["questionNumber"],$type);
			}
	}

	public static function InQuestion($questionNum){
		//SOCKET SENDING MESSAGE
				$entryData = array(
						'category' => "InGame".$_SESSION['game_id']."-".$questionNum
					, 'title'    => "Unnec"
				);
				//print_r($entryData);
				$context = new ZMQContext();
				$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
				$socket->connect("tcp://localhost:5555");
				$socket->send(json_encode($entryData));
				//END SOCKET SENDING
	}


	public static function alertUsers($message,$type=NULL){
		//SOCKET SENDING MESSAGE
		$entryData = array(
			'category' => "Game".$_SESSION["game_id"]."Status"
			, 'title'    => "Q".$message
			, 'type'    => $type
		);
		$context = new ZMQContext();
		$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
		$socket->connect("tcp://localhost:5555");
		$socket->send(json_encode($entryData));
		//END SOCKET SENDING
	}

	public static function loadQuestion(){
		   global $conn;
			 $sql = "SELECT * FROM `questions` WHERE gameid ='".$_SESSION["game_id"]."' AND questionNum='".$_SESSION["questionNumber"]."'";
	 		 $result = $conn->query($sql);
	 		//echo $sql;
	 		if ($result)
	 		{
	 			if($row = $result->fetch_assoc()){
	 				//echo $this->location->lat;
	 				$question=new self(null);
	 				$question->type=$row["type"];
					$type=$question->type;
	 				$question->country=$row["wording"];
					$question->min=$row["lat"];
					$question->max=$row["longg"];
					if ($type=="geo" || $type=="pop" || $type=="weather"){
						   $splits=explode(",",$question->country);
					     $question->country=$splits[1];
							 $question->city=$splits[0];
					}
	 				return $question;
	 			}
	 		}
	}

	function getLabel(){
		if ($this->type=="geo" || $this->type=="weather")
			return $this->city . ", ".$this->country;

		//if ($this->type=="pop" )
	  else
			return $this->country;

	}

	function getQuestionUnits(){
		if ($this->type=="geo")
			return "Where is ";
			if ($this->type=="geo")
				return "Where is ";
		if ($this->type=="pop")
				return "";
		if ($this->type=="weather")
				return "&deg;F";
		if ($this->type=="age")
				return "";
		if ($this->type=="time")
				return "";

	}
	function getQuestionText(){
		if ($this->type=="geo")
			return "Where is ";
			if ($this->type=="geo")
				return "Where is ";
		if ($this->type=="pop")
				return "What is the population of ";
		if ($this->type=="weather")
				return "What is the normal high temp today in ";
		if ($this->type=="age")
				return "How old is ";
		if ($this->type=="time")
				return "Guess the Year: ";
		if ($this->type=="rand")
					return $this->city;
	}
	function getWeather(){
		$this->getLocation("weather");
		$latLong=LatLong::findLatLong($this->city,$this->country);
		$url='http://api.wunderground.com/api/766deb6baf5fc335/almanac/conditions/forecast/q/'.$latLong->lat.','.$latLong->longg.'.json';
		$jsonData =file_get_contents( $url);
		$phpArray = json_decode($jsonData,true);
		//echo $url;
		$this->answer=$phpArray["almanac"]["temp_high"]["normal"]["F"];
		if ($this->answer=="" || $this->answer<=0)
			$this->getWeather();

  }

	function getRand(){
		global $conn;
		$sql = "SELECT * FROM `data-rand`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
		//echo $sql;
		//	$sql = "SELECT * FROM `data-geo`   WHERE `id`='13'";
		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				$this->city=$row["category"];
				$this->country=$row["wording"];
				$this->answer=$row["answer"];
				$this->min=$row["min"];
				$this->max=$row["max"];
				$url= $row["image"];
        $this->image="a.jpg";//Question::getRandomUrl($url);
				}
		}
		if ($this->checkForRepeats($this->country))
			$this->getRand();
	}

	function getAge(){
		global $conn;
		$sql = "SELECT * FROM `data-people`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
	//		$sql = "SELECT * FROM `data-people`  WHERE `id`='169'";
		//echo $sql;
		//	$sql = "SELECT * FROM `data-geo`   WHERE `id`='13'";
		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				$this->country=$row["name"];
				//$this->city=$row["city"];
				//echo $this->country;
				date_default_timezone_set('America/Los_Angeles');
				$now = new DateTime("now");
				$bday = date_create($row["birthday"]);
				$age=date_diff($now, $bday);
				$this->answer=$age->format('%y');
				$url= $row["image"];
        $this->image=Question::getRandomUrl($url);
				}
		}
		if ($this->checkForRepeats($this->country))
			$this->getAge();
	}

	function getTime(){
		global $conn;
		$sql = "SELECT * FROM `data-time` ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";

		$sql = "SELECT * FROM `data-time` WHERE `imageUpdatedDate` IS NOT null  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
		//die ($sql);
		//	$sql = "SELECT * FROM `data-time`   WHERE `id`='109'";
		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				$this->country=$row["wording"];
				$this->answer=$row["answer"];
				$url= $row["image"];
				$this->image=Question::getRandomUrl($url);
			}
		}
		if (Question::checkForRepeats($this->country))
   		$this->getTime();
	}

	function getUserQuestion(){
			$csvData = file_get_contents("https://docs.google.com/spreadsheets/d/1fco39wut_t7dFqCQ8ZuNe7VGCupndG8UPpinlohpbXc/pub?output=csv");

		$lines = explode(PHP_EOL, $csvData);

		$array = array();
		$randEntry=rand(0,sizeof($lines)-1);
		$city=explode(",",$lines[$randEntry]);
		//echo "city ".$city[1] . "sdf";
		if (sizeof($city)>1){
		//	$this->city=preg_replace( "/\r|\n/", "", ($city[0]));
			$this->country=preg_replace( "/\r|\n/", "", ($city[0]));
			$this->answer=preg_replace( "/\r|\n/", "", ($city[1]));
		}
		if ($this->checkForRepeats($this->country))
			$this->getUserQuestion();

}

  public static function getRandomUrl($url){
	  //echo $url;
		$url=trim(preg_replace('/\s\s+/', ' ', $url));
		$url=str_replace(" ","",$url);
		$url=str_replace("[ '","['",$url);
		$url=substr($url,1,-1);
		$url=str_replace("',\"","','",$url);
		$url=str_replace("\",\"","','",$url);
		$url=str_replace("\",'","','",$url);
		$url=str_replace("','","','",$url);
		$url=str_replace("','","','",$url);

		$splits=explode("','",$url);

		if (sizeof($splits)==0)
		{
		//	echo "going in again";
				return $this->getLocation();
			}
		$url=$splits[rand(0,sizeof($splits)-1)];
		if (substr($url,0,1)=="'")$url=substr($url,1);
		if (substr($url,-1)=="'")$url=substr($url,0,-1);
		return $url;
	}

	function getLocation($type){

		global $conn;
		$regionsSelected=$_SESSION["regionsSelected"];
		$sql = "SELECT * FROM `data-geo` WHERE ";

    foreach ($regionsSelected as $region)
			$sql.=" `location` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);
		$sql.=" ORDER BY rand() LIMIT 1";
		//die ($sql);
		//" WHERE `id`='3'";
		//	$sql = "SELECT * FROM `data-geo`   WHERE `country`='Antigua and Barbuda'";
		$result = $conn->query($sql);
		if ($result)
		{
		  if($row = $result->fetch_assoc()){
				$this->country=$row["country"];
				$this->city=$row["city"];
				$this->answer=$row["population"];
		    $url= $row["image"];
		    $this->image=Question::getRandomUrl($url);
					//echo $this->image;
		  }
		}
		if ($this->checkForRepeats($this->country))
			$this->getLocation($type);
		if ($type=="pop"&& $this->answer=="-1")
			$this->getLocation($type);

}

public static function loadImage($wording,$type){

	global $conn;
	if ($type=="geo" || $type=="pop" || $type=="weather")
	{
		$words=explode(",",$wording);
		$city=$words[0];
		$country=$words[1];
		$sql = "SELECT * FROM `data-geo` WHERE `country`='$country' AND `city`='$city' LIMIT 1";//" WHERE `id`='3'";
	}
	if ($type=="age")
		$sql = "SELECT * FROM `data-people` WHERE `name`='$wording'  LIMIT 1";//" WHERE `id`='3'";
	if ($type=="time")
		$sql = "SELECT * FROM `data-time` WHERE `wording`='$wording' LIMIT 1";//" WHERE `id`='3'";

	$result = $conn->query($sql);
	//die($sql);
	if ($result)
	{
		if($row = $result->fetch_assoc()){
			$url= $row["image"];
			return Question::getRandomUrl($url);
		}
	}
}

function checkForRepeats($country){
	   global $conn;
		 $sql = "SELECT * FROM `questions` WHERE gameID='".$_SESSION["game_id"]."' AND wording LIKE '%$country%'";
	 	$result = $conn->query($sql);
		//echo $sql;
		$this->timesRepeated++;
		if ($result!=null && $result->num_rows>0 && $this->timesRepeated<5)
		   return true;
	  return false;

}

function getImage(){
	/*
  if ($this->city==$this->country)
	    $name=$this->city;
	else
	    $name=$this->city.', '.$this->country;
	$url='http://en.wikipedia.org/w/api.php?action=query&titles='.urlencode($name).'&prop=pageimages&format=json&pithumbsize=1000';
	$jsonData =file_get_contents( $url);
  //echo "u".$url;
	if (strlen($jsonData)<150)
	{
		$url='http://en.wikipedia.org/w/api.php?action=query&titles='.$this->city.'&prop=pageimages&format=json&pithumbsize=1000';
		$jsonData =file_get_contents( $url);
    //echo "u".$url."jjj".strpos($jsonData,"missing");
	}
	if (strlen($jsonData)<150 || strlen($jsonData)>3000)
	{
		$url='http://en.wikipedia.org/w/api.php?action=query&titles=earth&prop=pageimages&format=json&pithumbsize=1000';
		$jsonData =file_get_contents( $url);

	}
	$phpArray = json_decode($jsonData);
	//print_r($phpArray);
	$image=findSource($phpArray);
	return $image;
	*/
	return $this->image;
}

function addAnswer(){
	global $conn;
	//$latLong=new LatLong();
	if ($this->type=="geo" || $this->type=="weather" || $this->type=="pop")
	    $latLong=LatLong::findLatLong($this->city,$this->country);
	else if ($this->type=="rand")  //we save min max of random as a lat just for ease
	    $latLong=new LatLong($this->min,$this->max);
  else
		  $latLong=new LatLong(0,0);
	if ($this->city!="")
		$cityName=$this->city . ",".$this->country;
  else
		$cityName=$this->country;
	$cityName=$conn->real_escape_string($cityName);
	$sql = "INSERT INTO `questions` (`gameID`, `questionNum`,`type`, `wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','$cityName', '".$latLong->lat."', '".$latLong->longg."', '".$this->answer."')";
	$result = $conn->query($sql);
	//echo $sql;
}
}

?>
