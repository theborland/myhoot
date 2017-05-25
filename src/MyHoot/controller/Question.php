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

	function __construct($type){ $s=0;
		if ($type!=null)
		{
			  //echo $type . "d";
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
				else if ($type=="estimation")
						$this->getEstimation();
				else if ($type=="facts")
						$this->getFacts();
				else
						$this->getQuestion($type);
				//if ($type=="user")
				//		$this->getUserQuestion();

				$this->addAnswer();
				//echo "in here again";
				Game::updateRound($_SESSION["questionNumber"],$this->type);
				if ($this->type=="time")
				{
					if ($this->answer<-200)$region=0;
					else if ($this->answer<600)$region=1;
					else if ($this->answer<1200)$region=2;
					else if ($this->answer<1600)$region=3;
					else $region=4;
					$this->alertUsers($_SESSION["questionNumber"],"WorldTime".$region);
				}
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

	// public function getTopThree(){
	//
	// }

	public static function InQuestion($questionNum){
		//SOCKET SENDING MESSAGE
			  if (isset($_SESSION["single"]) && $_SESSION["single"]==true)return;

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


	public function alertUsers($message,$type=NULL){
		//SOCKET SENDING MESSAGE
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)return;
		$entryData = array(
			'category' => "Game".$_SESSION["game_id"]."Status"
			, 'title'    => "Q".$message
			, 'type'    => $type
		);
		$connect="5555";
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
		{
			$entryData["question"]=$this->getQuestionText() . "" . $this->getLabel() . " ". $this->getQuestionTextEnd(); ;
			//if ($this->type=="geo" || $this->type=="places")
			$connect="5556";
		}
		$context = new ZMQContext();
		$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
		$socket->connect("tcp://localhost:".$connect);
		$socket->send(json_encode($entryData));
		//END SOCKET SENDING
	}

	public static function loadQuestion(){
		   global $conn;
			 $table="questions";
			 if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
		 			$table="questionsSingle";
			 $sql = "SELECT * FROM `$table` WHERE gameid ='".$_SESSION["game_id"]."' ORDER BY questionNum DESC";
	 		 $result = $conn->query($sql);
			 //echo $sql;
	 		if ($result)
	 		{
	 			if($row = $result->fetch_assoc()){
	 				//echo $this->location->lat;
	 				$question=new self(null);
					$_SESSION["questionNumber"]=$row["questionNum"];
	 				$question->type=$row["type"];
					$question->qID=$row["qID"];
					$type=$question->type;
	 				$question->country=$row["wording"];
					$question->answer=$row["answer"];
					$question->min=$row["lat"];
					$question->max=$row["longg"];
					if (isset($row["active"]) && $row["active"]!=0)
							$question->answer="waiting";
					if ($type=="geo" || $type=="pop" || $type=="weather"|| $type=="pt" || $type=="estimation"){
						   $splits=explode(",",$question->country);
					     $question->country=$splits[1];
							 $question->city=$splits[0];
					}
	 				return $question;
	 			}
	 		}
	}

	function getLabel(){
		if ($this->country=="percSq")
			 return "What percent is red";
		if ($this->country=="ellipse")
			 return "What is circumference of ellipse shown";
	 if ($this->country=="colHt")
		   return "What is the height of the red bar";
	 if ($this->country=="piePerc")
		   return "What is the percent of the red region";
	 if ($this->country=="howMany")
		   return "How many dots are there";
	 if ($this->country=="mapDist")
			 return "Using the scale shown, how long is the line";
		else if ($this->type=="geo" || $this->type=="weather")
			return $this->city . ", ".$this->country;
	//	else if ($this->country=="places," )
//			return substr($this->country,0);
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

function getUnitsAway($distanceAway){
		if ($distanceAway>1000)
		  $distanceAway=number_format($distanceAway);
		if ($this->type=="pop")
		  $message= $distanceAway. " people";
		else if ($this->type=="weather")
		    $message= $distanceAway. " degrees";
		else if ($this->type=="age" || $this->type=="time")
		      $message= $distanceAway. " years";
		else if ($this->type=="geo" || $this->type=="pt"|| $this->type=="places")
		  $message= $distanceAway. " miles";
		else //if ($game->type=="rand")
		  $message= $distanceAway. "";
		//die ($this->type);
		return $message;
	}

  function getMessageAway($distanceAway)
	{
		  $units=$this->getUnitsAway($distanceAway);
			if ($this->type=="geo" || $this->type=="pt"|| $this->type=="places")
			  $message= "Distance away : ". $units;
			else
			  $message= "Off by: ". $units;
			return $message;
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
				$this->qID=$row["id"];
				if ($this->checkForRepeats($this->country))
				{
					$this->getQuestion($type);
					return;
				}
				$this->answer=$row["answer"];
				$this->min=$row["min"];
				$this->max=$row["max"];
				$url= $row["image"];
				$this->image=Question::getRandomUrl($url);

        //$this->image="a.jpg";//Question::getRandomUrl($url);
				}
		}


	}

	function read($file, $vars) {
	 ob_start();
	 extract($vars, EXTR_SKIP);
	 include($file);
	 $content = ob_get_contents();
	 ob_end_clean();
	//  $loc=strrpos($content,"link");
	//  $img=substr($content,$loc);

	 return $content;
 }

	function getEstimation(){
		  //either get a cel age (75% of time )
			$random=rand(1,6);
			if ($random==1){
				$this->country="colHt";
				$this->answer=rand(30,1000);
				$this->min=0;
				$this->max=1000;
				$height1=rand(30,1000);
				$height2=rand(30,1000);
				while (($height1<$height2*2 && $height2<$height1*2) || $height1<$height2/8 || $height2<$height1/8){
				  $height1=rand(1,1000);
				  $height2=rand(1,1000);
				}
				$this->city=$height1*$this->answer/$height2;
				$this->qID="colHt".$this->answer;
				$vars = array(  'target' => $this->answer,'scaled'=>$this->city,'showAnswer'=>'no');
				$this->image= $this->read('estimation/columnHeight.php',$vars);
			}
			else if ($random==2){
				$this->country="piePerc";
				$this->city="piePerc";
				$this->answer=rand(1,99);
				$this->min=0;
				$this->max=100;
				while ($this->answer==50 || $this->answer==25 || $this->answer==75){
					$this->answer=rand(1,99);
				}
				$this->qID="piePerc".$this->answer;
				$vars = array(  'perc' => $this->answer,'showAnswer'=>'no');
				$this->image= $this->read('estimation/piePercent.php',$vars);
			}
			else if ($random==3){
				$this->country="howMany";
				$this->city="howMany";
				$this->answer=rand(30,300);
				$this->min=0;
				$this->max=300;
				$this->qID="howMany".$this->answer;
				$vars = array(  'count' => $this->answer,'showAnswer'=>'no');
				$this->image= $this->read('estimation/howMany.php',$vars);
			}
			else if ($random==4){

				$target=rand(30,1000);

				$this->country="mapDist";
				$this->city=rand(1,1000);
				$this->answer=$target;
				$this->min=0;
				$this->max=1000;
				$this->qID="mapDist".$this->answer;
				$vars = array( 'target'=>$this->answer, 'rand' => $this->city,'showAnswer'=>'no');
				$img=$this->read('estimation/mapDistance.php',$vars);
				$this->image='<div id="chart_div" style="width: 600px; height: 600px;    margin-left: auto;
		    margin-right: auto;"><img src="controller/estimation/tmp/'.$this->city.'.png"></div>';
			}
			else if ($random==5){


				$target=rand(1,99);
				$this->country="percSq";
				$this->city=rand(1001,2000);
				$this->answer=$target;
				$this->min=0;
				$this->max=100;
				$this->qID="percSq".$this->answer;
				$vars = array( 'targetPercent'=>$this->answer, 'randomFileName' => $this->city,'showAnswer'=>'no');
				$img=$this->read('estimation/percentSquare.php',$vars);
				$this->image='<div id="chart_div" style="width: 600px; height: 600px;    margin-left: auto;
		    margin-right: auto;"><img src="controller/estimation/tmp/'.$this->city.'.png"></div>';
			}
			else if ($random==6){


				$target=rand(30,1000);
				$this->country="ellipse";
				$this->city=rand(2001,3000);
				$this->answer=$target;
				$this->min=0;
				$this->max=1000;
				$this->qID="ellipse".$this->answer;
				$vars = array( 'target'=>$this->answer, 'randomFileName' => $this->city,'showAnswer'=>'no');
				$img=$this->read('estimation/circleSize.php',$vars);
				$this->image='<div id="chart_div" style="width: 600px; height: 600px;    margin-left: auto;
		    margin-right: auto;"><img src="controller/estimation/tmp/'.$this->city.'.png"></div>';
			}

	}

	function getEntertainment(){
		  //either get a cel age (75% of time )
			if (rand(0,100)>65)
					$this->getQuestion('entertainment');
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
		{
			$this->type="geo";
			$this->getLocation($this->type);
		}

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

				$this->qID=$row["id"];
				if ($this->checkForRepeats($this->country))
				{
					$this->getAge();
					return;
				}
				$this->image=Question::getRandomUrl($url);
				}
		}

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
				$this->qID=$row["id"];

				if ($this->checkForRepeats($this->country))
				{
					$this->getTime();
					return;
				}
				$this->image=Question::getRandomUrl($url);
				//echo ("sdf");

			}
		}

	}

	function getFacts(){
		global $conn;
		if (rand(1,10)<7){
			$this->type="pop";
			return $this->getLocation("pop");
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
				$this->qID=$row["id"];
				if ($this->checkForRepeats($this->country))
				{
					$this->getFacts();
					return;
				}
				$this->image=Question::getRandomUrl($url);
				$this->max=$row["max"];
				$this->min=0;
			}
		}

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
		//echo $url;
		if (sizeof($splits)==0)
		{
		  	//echo "going in again";
				return Question::getRandomUrl($url);
			}
		$url=$splits[rand(0,sizeof($splits)-1)];
		if (substr($url,0,1)=="'")$url=substr($url,1);
		if (substr($url,0,1)=="\"")$url=substr($url,1);
		if (substr($url,-1)=="'")$url=substr($url,0,-1);

		if (checkRemoteFile($url))
			return $url;
		else if (rand(1,10)<8)
			return Question::getRandomUrl($originalUrl);
		else return "https://upload.wikimedia.org/wikipedia/en/7/72/World_Map_WSF.svg.png";

	}

	function getPPT($type){
		global $conn;
		//echo "here";
		$regionsSelected=$_SESSION["regionsSelected"];

		//determine people, place, things
		$rand=rand(1,7);
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
		//$sql.="  `id`='60'";
		//	$sql = "SELECT * FROM `data-geo`   WHERE `country`='Antigua and Barbuda'";
    //$sql = "SELECT * FROM `data-geo` WHERE `id`=75";
		//die ($sql);
		$result = $conn->query($sql);
	//	echo "a";
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
				//echo "b";
		    $this->image=Question::getRandomUrl($url);
				$this->qID=$row["id"];
				//echo "c";
					//echo $this->image;
		  }
		}
	//	if ($this->checkForRepeats($this->country))
	//		$this->getPPT($db);

}

	function getLocation($type){

		global $conn;
		$regionsSelected=$_SESSION["regionsSelected"];
		$sql = "SELECT * FROM `data-geo` WHERE ( ";

    foreach ($regionsSelected as $region)
			$sql.=" `location` = '" . $region ."' OR";
		$sql=substr($sql,0,strlen($sql)-3);
		$sql.=" ) ";
		if ($type=="pop" || $type=="population" || $this->type=="pop")
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
				$this->qID=$row["id"];
				if ($this->checkForRepeats($this->country)){
					$this->getLocation($type);
					return;
				}
				$this->image=Question::getRandomUrl($url);
					//echo $this->image;
		  }
		}


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
	if ($this->type=="estimation"){
		if ($this->country=="colHt"){
			$vars = array(  'target' => $this->answer,'scaled'=>$this->city,'showAnswer'=>'yes');
			return $this->read('estimation/columnHeight.php',$vars);
		}
		if ($this->country=="piePerc"){
			$vars = array(  'perc' => $this->answer,'scaled'=>$this->city,'showAnswer'=>'yes');
			return $this->read('estimation/piePercent.php',$vars);
		}
		if ($this->country=="howMany"){
			$vars = array(  'count' => $this->answer,'scaled'=>$this->city,'showAnswer'=>'yes');
			return $this->read('estimation/howMany.php',$vars);
		}
		if ($this->country=="mapDist" || $this->country=="percSq" || $this->country=="ellipse"){
			return '<div id="chart_div" style="width: 600px; height: 600px;    margin-left: auto;
			margin-right: auto;"><img src="controller/estimation/tmp/'.$this->city.'.png"></div>';
		}

	}
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
	//return false;
	//echo "here";
	global $conn;
	$this->timesRepeated++;
	if ($this->timesRepeated>=8 && $this->type!="geo"){
		$this->type="geo";
		$this->timesRepeated=0;
		$this->getLocation("geo");
		return false;
	}
	if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
	{
		$sql = "select count(*) total FROM `answers` WHERE `question_id`='".$this->type.$this->qID."'";
		$result = $conn->query($sql);
		//echo($sql);
		if ($result)
		{
				$row = $result->fetch_assoc();
				if ($row ['total']>10){
					//echo ($sql);
					return false;
				}
		}
		 return true;
	}


		 $sql = "SELECT * FROM `questions` WHERE gameID='".$_SESSION["game_id"]."' AND wording LIKE '%$country%'";
	 	$result = $conn->query($sql);
		//echo $sql;

		if ($result!=null && $result->num_rows>0)
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
  $table="questions";
	if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
		$table="questionsSingle";
	if ($this->city!="" && $this->type!="rand" && $this->type!="places")
		$cityName=$this->city . ",".$this->country;
  else
		$cityName=$this->country;
	$cityName=$conn->real_escape_string($cityName);
	if ($this->type=="geo" || $this->type=="weather" || $this->type=="pop"|| $this->type=="places"|| $this->type=="pt")
		$sql = "INSERT INTO `$table` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '".$this->lat."', '".$this->longg."', '".$this->answer."')";
	else if ($this->type!="age" && $this->type!="time")
		$sql = "INSERT INTO `$table` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '".$this->min."', '".$this->max."', '".$this->answer."')";
  else
		$sql = "INSERT INTO `$table` (`gameID`, `questionNum`,`type`,`qID` ,`wording`, `lat`, `longg`,`answer`) VALUES ('".$_SESSION["game_id"]."', '".$_SESSION["questionNumber"]."','".$this->type."','".$this->qID."','$cityName', '0', '0', '".$this->answer."')";

	$result = $conn->query($sql);
	//echo $sql;
	//die ($sql);
}

public static function findQuestion()
{
	global $conn;
	$table="questions";
	if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
		$table="questionsSingle";
	$sql = "SELECT * FROM `$table` WHERE `gameid` = '".$_SESSION["game_id"]."' order by questionNum DESC LIMIT 1";
	//echo ($sql);


	$result = $conn->query($sql);
	if ($result)
	{
		$row = $result->fetch_assoc();
		if ($row){
			if (isset($row["active"]) && $row["active"]>=1){
				usleep(500000);
				return Question::findQuestion();
			}
			else {
				$game=new self(null);
				$game->qID= $row["questionNum"];
				$game->type=$row["type"];
				$game->answer=$row["answer"];
				if (isset($row["active"]) && $row["active"]!=0)
						$game->answer="waiting";
				//$game->max=$row["max"];


				return $game;
			}
		}

	}
	return null;


}

	public static function findQID($questionNum)
	{
		global $conn;
		$table="questions";
		if (isset($_SESSION["single"]) && $_SESSION["single"]==true)
			$table="questionsSingle";
		$sql = "SELECT * FROM `$table` WHERE `gameid` = '".$_SESSION["game_id"]."' AND questionNum='".$questionNum."'";
		//echo ($sql);
		$result = $conn->query($sql);
		if ($result)
		{
			$row = $result->fetch_assoc();
			if ($row){
				//echo $row["qID"];
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
