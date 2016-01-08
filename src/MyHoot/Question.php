<?php
class Question
{
	var $city;
	var $country;
	var $type;
	var $answer;
	var $image;

	function __construct($type){
		$_SESSION["questionNumber"]++;
		$this->alertUsers($_SESSION["questionNumber"],$type);
		$this->type=$type;
		if ($type=="geo")
			  $this->getLocation();
	  if ($type=="pop")
				$this->getLocation();
		if ($type=="weather")
				$this->getWeather();
		if ($type=="age")
				$this->getAge();
		if ($type=="user")
				$this->getUserQuestion();
		$this->addAnswer();
		//echo "in here again";
		Game::updateRound($_SESSION["questionNumber"],$type);
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

	function getLabel(){
		if ($this->type=="geo" || $this->type=="weather")
			return $this->city . ", ".$this->country;
		//if ($this->type=="pop" )
	  else
			return $this->country;

	}

	function getQuestionText(){
		if ($this->type=="geo")
			return "Where is ";
		if ($this->type=="pop")
				return "What is the population of ";
		if ($this->type=="weather")
				return "What is the normal high temp today in ";
		if ($this->type=="age")
				return "How old is ";
	}
	function getWeather(){
		$this->getLocation();
		$latLong=LatLong::findLatLong($this->city,$this->country);
		$url='http://api.wunderground.com/api/766deb6baf5fc335/almanac/conditions/forecast/q/'.$latLong->lat.','.$latLong->longg.'.json';
		$jsonData =file_get_contents( $url);
		$phpArray = json_decode($jsonData,true);
		//echo $url;
		$this->answer=$phpArray["almanac"]["temp_high"]["normal"]["F"];
		if ($this->answer=="" || $this->answer==0)
			$this->getWeather();

  }
	function getAge(){
		global $conn;
		$sql = "SELECT * FROM `data-people`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
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
				$this->image="http://thumbs.dreamstime.com/z/ages-woman-editable-vector-silhouettes-different-stages-womans-life-32780321.jpg";
			}
		}
		if (Question::checkForRepeats($this->country))
			$this->getAge();
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
		if (Question::checkForRepeats($this->country))
			$this->getUserQuestion();

}

	function getLocation(){

		global $conn;
		$sql = "SELECT * FROM `data-geo`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
		//	$sql = "SELECT * FROM `data-geo`   WHERE `id`='13'";
		$result = $conn->query($sql);
		if ($result)
		{
		  if($row = $result->fetch_assoc()){
				$this->country=$row["country"];
				$this->city=$row["city"];
				$this->answer=$row["population"];
		    $url= $row["url"];
		    $url=substr($url,2,strlen($url)-3);
		    $splits=explode("', '",$url);
		    //echo $url;
			//echo sizeof($splits). "<br>";
			//	print_r($splits);
				if (sizeof($splits)==0)
				{
				//	echo "going in again";
				    return $this->getLocation();
					}
		    $this->image=$splits[rand(0,sizeof($splits)-1)];
		  }
		}
		if (Question::checkForRepeats($this->country))
			$this->getLocation();
		//	$csvData = file_get_contents("https://raw.githubusercontent.com/icyrockcom/country-capitals/master/data/country-list.csv");
/*
		$csvData = file_get_contents("http://myonlinegrades.com/stats/la.csv");
		$lines = explode(PHP_EOL, $csvData);
		//	$my=str_getcsv($lines);
		//print_r($lines);
		$array = array();

	//print_r($array);
	//echo "lines " . sizeof($lines);
	$randEntry=rand(1,sizeof($lines)-1);
	//	$randEntry=rand(1,5);
//	$randEntry=245;
	//echo "rand is ".$randEntry;
	//$randEntry=168;
	$city=explode(",",$lines[$randEntry]);
	//echo "city ".$city[1] . "sdf";
	if (sizeof($city)>1){
		$this->city=preg_replace( "/\r|\n/", "", ($city[0]));//.','.$array[$randEntry][0]);
		$this->country=preg_replace( "/\r|\n/", "", ($city[1]));
		if (strlen($this->country)==2)
				$this->country=state_abbr($this->country,"name");
	}
	else if (sizeof($city)>0){
		$this->country=preg_replace( "/\r|\n/", "", ($city[0]));//.','.$array[$randEntry][0]);
		$this->city="";//.','.$array[$randEntry][0]);
	}
	else
		$this->getLocation();
		*/
	//if (Question::checkForRepeats($this->country))
	//	$this->getLocation();
}

public static function checkForRepeats($country){
	   global $conn;
		 $sql = "SELECT * FROM `questions` WHERE gameID='".$_SESSION["game_id"]."' AND wording LIKE '%$country%'";
	 	$result = $conn->query($sql);
		//echo $sql;
		if ($result->num_rows>0)
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
  else
		  $latLong=new LatLong(0,0);
	if ($this->city!="")
		$cityName=$this->city . ",".$this->country;
  else
		$cityName=$this->country;
	$sql = "INSERT INTO `questions` (`gameID`, `questionNum`,`type`, `wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','$cityName', '".$latLong->lat."', '".$latLong->longg."', '".$this->answer."')";
	$result = $conn->query($sql);
}
}

?>
