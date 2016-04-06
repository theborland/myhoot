<?php
include ("Question.php");
include ("Answer.php");
include ("User.php");
include ("Game.php");

class LatLong
{
	public $lat;
	public $longg;

	public function __construct($lat,$longg) {
		$this->lat=$lat;
		$this->longg=$longg;
	}


	public static function findLatLong ($address,$country)
	{
	//	$prepAddr=LatLong::removeAccents($address. " ".$country);
	//	$prepAddr = urlencode(str_replace(' ','+',$prepAddr));
		//echo "p".$prepAddr. " a ". ($address. " ".$country);
		$prepAddr=remove_accents($address. " ".$country);
  	$prepAddr = urlencode(str_replace(' ','+',$prepAddr));
		//$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	//	echo 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
		$output= json_decode($geocode);
		//print_r($output);
		$instance = new self($output->results[0]->geometry->location->lat,$output->results[0]->geometry->location->lng);
		return $instance;
	}

	public static function removeAccents($str) {
		return strtr(utf8_decode($str),
         utf8_decode(
         'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
         'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
	//return str_replace($a, $b, $str);
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
	$returnVal=array();
	foreach ($phpArray->query->pages as $key => $value) {
		//echo ("<Br><br>");
		//print_r($value);
			if (isset($value->thumbnail))
			{

		      $returnVal[]= $value->thumbnail->source;

				}
		}
		return $returnVal;
//https://en.wikipedia.org/w/api.php?format=json&action=query&generator=search&gsrnamespace=0&gsrsearch=The+Ku+Klux+Klan+was+founded.&gsrlimit=10&prop=pageimages|extracts&pilimit=max&exintro&explaintext&exsentences=1&exlimit=max
//https://en.wikipedia.org/w/api.php?action=query&generator=search&gsrnamespace=0&gsrlimit=10&gsrsearch=The+Ku+Klux+Klan+was+founded.&prop=pageimages&format=json
}

function state_abbr($name, $get = 'abbr') {
//make sure the state name has correct capitalization:
    if($get != 'name') {
    $name = strtolower($name);
    $name = ucwords($name);
    }else{
    $name = strtoupper($name);
    }
$states = array(
'Alabama'=>'AL',
'Alaska'=>'AK',
'Arizona'=>'AZ',
'Arkansas'=>'AR',
'California'=>'CA',
'Colorado'=>'CO',
'Connecticut'=>'CT',
'Delaware'=>'DE',
'Florida'=>'FL',
'Georgia'=>'GA',
'Hawaii'=>'HI',
'Idaho'=>'ID',
'Illinois'=>'IL',
'Indiana'=>'IN',
'Iowa'=>'IA',
'Kansas'=>'KS',
'Kentucky'=>'KY',
'Louisiana'=>'LA',
'Maine'=>'ME',
'Maryland'=>'MD',
'Massachusetts'=>'MA',
'Michigan'=>'MI',
'Minnesota'=>'MN',
'Mississippi'=>'MS',
'Missouri'=>'MO',
'Montana'=>'MT',
'Nebraska'=>'NE',
'Nevada'=>'NV',
'New Hampshire'=>'NH',
'New Jersey'=>'NJ',
'New Mexico'=>'NM',
'New York'=>'NY',
'North Carolina'=>'NC',
'North Dakota'=>'ND',
'Ohio'=>'OH',
'Oklahoma'=>'OK',
'Oregon'=>'OR',
'Pennsylvania'=>'PA',
'Rhode Island'=>'RI',
'South Carolina'=>'SC',
'South Dakota'=>'SD',
'Tennessee'=>'TN',
'Texas'=>'TX',
'Utah'=>'UT',
'Vermont'=>'VT',
'Virginia'=>'VA',
'Washington'=>'WA',
'West Virginia'=>'WV',
'Wisconsin'=>'WI',
'Wyoming'=>'WY'
);
    if($get == 'name') {
    // in this case $name is actually the abbreviation of the state name and you want the full name
    $states = array_flip($states);
    }

return $states[$name];
}
?>
