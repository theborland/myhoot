<?php
class Question
{
	var $city;
	var $country;
	var $lat;
	var $longg;
	var $type;
	var $answer;
	var $image;
	var $qID; //this keeps track of the id of question from rand or geo or whatever
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
			  //else if ($type=="pop")
				//		$this->getLocation($type);
				else if ($type=="weather")
						$this->getWeather();
				//else if ($type=="age")
				//		$this->getAge();
				else if ($type=="entertainment")
						$this->getEntertainment();
				else if ($type=="pt")
						$this->getPPT("pt");
				else if ($type=="places")
						$this->getPPT("places");
				else if ($type=="sports")
						$this->getQuestion($type);
				else if ($type=="science")
						$this->getQuestion($type);
				else if ($type=="time")
						$this->getTime();
				else if ($type=="facts")
						$this->getFacts();
				else //if ($type=="rand")
						$this->getQuestion($type);
				//if ($type=="user")
				//		$this->getUserQuestion();
				$this->addAnswer();
				//echo "in here again";
				Game::updateRound($_SESSION["questionNumber"],$this->type);
				if ($this->type=="time" && $this->answer<1600)
					$this->alertUsers($_SESSION["questionNumber"],"WorldTime");
				else if ($this->type=="facts" && $this->max==-100)
					$this->alertUsers($_SESSION["questionNumber"],"factsPercent");
				else if ($this->type=="facts" && $this->max==100)
					$this->alertUsers($_SESSION["questionNumber"],"facts");
				else if ($this->type=="facts" && $this->max<100)
					$this->alertUsers($_SESSION["questionNumber"],"factsMax");
				else if ($this->type=="facts" && $this->max>0)
					$this->alertUsers($_SESSION["questionNumber"],"factsRand");
				else
					$this->alertUsers($_SESSION["questionNumber"],$this->type);
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
					$question->qID=$row["qID"];
					$type=$question->type;
	 				$question->country=$row["wording"];
					$question->min=$row["lat"];
					$question->max=$row["longg"];
					if ($type=="geo" || $type=="pop" || $type=="weather"|| $type=="pt"){
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
		else if ($this->type=="places" )
			return substr($this->country,6);
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

	function getQuestionTextEnd(){
			if ($this->type=="pt"){
				if ($this->city=="based" || $this->city=="started" || $this->city=="born")
					return $this->city;
				if ($this->city=="lived" || $this->city=="live" )
					return "live";
				if ($this->city=="bus")
					return " ride her famous bus";
				if ($this->city=="worked" || $this->city=="works" )
						return "work";
			}
			return "";
	}

	function getQuestionText(){
		if ($this->type=="geo" || $this->type=="places")
			return "Where is ";
		if ($this->type=="pt"){
				if ($this->city=="hometown")
					return "Where was the hometown of ";
				else if ($this->city=="started" || $this->city=="born")
					return "Where was ";
				else if ($this->city=="lived" || $this->city=="worked" || $this->city=="bus")
					return "Where did ";
				else if ($this->city=="live" || $this->city=="works")
					return "Where does ";
				else if ($this->city=="resting place")
					return "Where is the resting place of ";
				else if ($this->city=="place")
					return "Where is ";
				else return "Where is ";
		}
		if ($this->type=="pop")
				return "What is the population of ";
		if ($this->type=="weather")
				return "What is the normal high temp today in ";
		if ($this->type=="age")
				return "How old is ";
		if ($this->type=="time")
				return "Guess the Year: ";
		if ($this->type=="rand")
				return ucfirst($this->city);
	}

	function getQuestion($type){
		global $conn;
		$sql = "SELECT * FROM `data-$type`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
		//echo $sql;
		//	$sql = "SELECT * FROM `data-geo`   WHERE `id`='13'";
		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				//$this->city=$row["category"];
				$this->country=$row["wording"];
				$this->answer=$row["answer"];
				$this->min=$row["min"];
				$this->max=$row["max"];
				$url= $row["image"];
				$this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
        //$this->image="a.jpg";//Question::getRandomUrl($url);
				}
		}
		if ($this->checkForRepeats($this->country))
			$this->getQuestion($type);
	}

	function getEntertainment(){
		  //either get a cel age (75% of time )
			if (rand(0,100)>75)
					$this->getQuestion('Entertainment');
			else {
				$this->type="age";
				$this->getAge();

			}
	}
	function getWeather(){
		$this->getLocation("weather");
		//$latLong=LatLong::findLatLong($this->city,$this->country);
		$url='http://api.wunderground.com/api/766deb6baf5fc335/almanac/conditions/forecast/q/'.$this->lat.','.$this->longg.'.json';
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
				$this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
        //$this->image="a.jpg";//Question::getRandomUrl($url);
				}
		}
		if ($this->checkForRepeats($this->country))
			$this->getRand();
	}

	function getAge(){
		global $conn;
		$sql = "SELECT * FROM `data-age`  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
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
				$this->qID=$row["id"];
				}
		}
		if ($this->checkForRepeats($this->country))
			$this->getAge();
	}

	function getTime(){
		global $conn;
		$regionsSelected=$_SESSION["regionsSelected"];
		$sql = "SELECT * FROM `data-time` WHERE `imageUpdatedDate` IS NOT null AND";
		foreach ($regionsSelected as $region)
			$sql.=" `region` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);
		 $sql.=" ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
	//	die ($sql);

		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				$this->country=$row["wording"];
				$this->answer=$row["answer"];
				$url= $row["image"];
				$this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
			}
		}
		if (Question::checkForRepeats($this->country))
   		$this->getTime();
	}

	function getFacts(){
		global $conn;
		if (rand(1,10)<5){
			$this->type="pop";
			return $this->getLocation("population");
		}

		$regionsSelected=$_SESSION["regionsSelected"];
		$sql = "SELECT * FROM `data-facts` WHERE `imageUpdatedDate` IS NOT null AND";
		foreach ($regionsSelected as $region)
			$sql.=" `region` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);
		 $sql.="  ORDER BY rand() LIMIT 1";//" WHERE `id`='3'";
		//die ($sql);

		$result = $conn->query($sql);
		if ($result)
		{
			if($row = $result->fetch_assoc()){
				$this->country=$row["wording"] . " ".$row["country"] . "(".$row["year"] . ")" ;
				$this->answer=$row["answer"];
				$url= $row["image"];
				$this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
				$this->max=$row["max"];
				$this->min=0;
			}
		}
		if (Question::checkForRepeats($this->country))
			$this->getFacts();
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
		$originalUrl=$url;
		$url=trim(preg_replace('/\s\s+/', ' ', $url));
		$url=str_replace(" ","",$url);

		$url=str_replace("[ '","['",$url);
		$url=substr($url,1,-1);
		$url=str_replace("',\"","','",$url);
		$url=str_replace("\",\"","','",$url);
		$url=str_replace("\",'","','",$url);
		$url=str_replace("','","','",$url);
		$url=str_replace("','","','",$url);
  	$url=str_replace(",''","",$url);
		$splits=explode("','",$url);
 //die ($url);
		if (sizeof($splits)==0)
		{
		//	echo "going in again";
				return Question::getRandomUrl($url);
			}
		$url=$splits[rand(0,sizeof($splits)-1)];
		if (substr($url,0,1)=="'")$url=substr($url,1);
		if (substr($url,0,1)=="\"")$url=substr($url,1);
		if (substr($url,-1)=="'")$url=substr($url,0,-1);

		if (checkRemoteFile($url))
			return $url;
		else
			return Question::getRandomUrl($url);

	}

	function getPPT($type){
		global $conn;
		$regionsSelected=$_SESSION["regionsSelected"];

		//determine people, place, things
		$rand=rand(1,10);
	  if ($rand<7)
		   $db="people";
		else $db="products";

		if ($type=="places")$db="places";

		$sql = "SELECT * FROM `data-geo-$db` WHERE ";
    foreach ($regionsSelected as $region)
			$sql.=" `region` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);
		$sql.=" ORDER BY rand() LIMIT 1";
		//die ($sql);
		//" WHERE `id`='3'";
		//	$sql = "SELECT * FROM `data-geo`   WHERE `country`='Antigua and Barbuda'";
    //$sql = "SELECT * FROM `data-geo` WHERE `id`=75";
		$result = $conn->query($sql);
		if ($result)
		{
		  if($row = $result->fetch_assoc()){
				if ($db=="places"){
						$this->country=$row["place"];
						$this->city="place";
				}
				if ($db=="people"){
						$this->country=$row["name"];
						$this->city=$row["type"];
				}
				if ($db=="products"){
						$this->country=$row["product"];
						$this->city=$row["basedOrStarted"];
				}
				$this->lat=$row["lat"];
				$this->longg=$row["longg"];
				//$this->answer=$row["population"];
		    $url= $row["image"];
		    $this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
					//echo $this->image;
		  }
		}
		if ($this->checkForRepeats($this->country))
			$this->getPT($db);

}

	function getLocation($type){

		global $conn;
		$regionsSelected=$_SESSION["regionsSelected"];
		$sql = "SELECT * FROM `data-geo` WHERE ";

    foreach ($regionsSelected as $region)
			$sql.=" `location` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);

		if ($type=="pop")
			$sql.=" AND `population`>0";
		$sql.=" ORDER BY rand() LIMIT 1";
		//die ($sql);
		//" WHERE `id`='3'";
		//	$sql = "SELECT * FROM `data-geo`   WHERE `country`='Antigua and Barbuda'";
    //$sql = "SELECT * FROM `data-geo` WHERE `id`=75";
		$result = $conn->query($sql);
		if ($result)
		{
		  if($row = $result->fetch_assoc()){
				$this->country=$row["country"];
				$this->city=$row["city"];
				$this->lat=$row["lat"];
				$this->longg=$row["longg"];
				$this->answer=$row["population"];
		    $url= $row["image"];
		    $this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
					//echo $this->image;
		  }
		}
		if ($this->checkForRepeats($this->country))
			$this->getLocation($type);
		if ($type=="pop"&& $this->answer=="-1")
			$this->getLocation($type);

}

public static function loadImageOld($wording,$type){

	global $conn;
	$wording=$conn->escape_string ($wording);
	//die ($type);
	if ($type=="geo" || $type=="pop" || $type=="weather")
	{
		//die ($wording);
		$words=explode(",",$wording);
		$city=$words[0];
		$country=$words[1];
		$sql = "SELECT * FROM `data-geo` WHERE `country`='$country' AND `city`='$city' LIMIT 1";//" WHERE `id`='3'";
	}
	else if ($type=="age")
		$sql = "SELECT * FROM `data-age` WHERE `name`='$wording'  LIMIT 1";//" WHERE `id`='3'";
	else if ($type=="time")
		$sql = "SELECT * FROM `data-time` WHERE `wording`='$wording' LIMIT 1";//" WHERE `id`='3'";
		else if ($type=="places")
			$sql = "SELECT * FROM `data-geo-places` WHERE `wording`='$wording' LIMIT 1";//" WHERE `id`='3'";
		else
			$sql = "SELECT * FROM `data-$db` WHERE `wording`='$wording' LIMIT 1";//" WHERE `id`='3'";
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

public function loadImage(){

	global $conn;
	if ($this->type=="pop" || $this->type=="pop" || $this->type=="weather")
		return $this->loadImageOld($this->city.",".$this->country, $this->type);
	if ($this->type=="places" || $this->type=="things" || $this->type=="people")
	{
		$sql = "SELECT * FROM `data-geo-$this->type` WHERE `id`='$this->qID' LIMIT 1";//" WHERE `id`='3'";
	}
	else
		$sql = "SELECT * FROM `data-$this->type` WHERE `id`='$this->qID' LIMIT 1";//" WHERE `id`='3'";

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
	//if ($this->type=="geo" || $this->type=="weather" || $this->type=="pop")
	//    $latLong=LatLong::findLatLong($this->city,$this->country);
//  if ($this->type=="rand")  //we save min max of random as a lat just for ease
//	    $latLong=new LatLong($this->min,$this->max);
//  else
//		  $min=new LatLong(0,0);
	if ($this->city!="" && $this->type!="rand")
		$cityName=$this->city . ",".$this->country;
  else
		$cityName=$this->country;
	$cityName=$conn->real_escape_string($cityName);
	if ($this->type=="geo" || $this->type=="weather" || $this->type=="pop"|| $this->type=="places"|| $this->type=="pt")
		$sql = "INSERT INTO `questions` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '".$this->lat."', '".$this->longg."', '".$this->answer."')";
	else if ($this->type!="age" && $this->type!="time")
		$sql = "INSERT INTO `questions` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '".$this->min."', '".$this->max."', '".$this->answer."')";
  else
		$sql = "INSERT INTO `questions` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '0', '0', '".$this->answer."')";

	$result = $conn->query($sql);
	//echo $sql;
	//die ($sql);
}

	public static function findQID($questionNum)
	{
		global $conn;
		$sql = "SELECT * FROM `questions` WHERE `gameid` = '".$_SESSION["game_id"]."' AND questionNum='".$questionNum."'";
		//die ($sql);
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			if ($row){

				return $row["qID"];
			}

		}
		return -1;

	}
}

function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>
