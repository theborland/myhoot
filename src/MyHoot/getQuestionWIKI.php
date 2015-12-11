<?php
session_start();

require 'dbsettings.php';


$csvData = file_get_contents("https://raw.githubusercontent.com/icyrockcom/country-capitals/master/data/country-list.csv");
$lines = explode(PHP_EOL, $csvData);
$array = array();
foreach ($lines as $line) {
    $array[] = str_getcsv($line);
}
//print_r($array);
$randEntry=rand(1,sizeof($array));

$city=urlencode($array[$randEntry][1]);//.','.$array[$randEntry][0]);
$url='http://en.wikipedia.org/w/api.php?action=query&titles='.$city.'&prop=pageimages&format=json&pithumbsize=1000';
echo $url;
$jsonData =file_get_contents( $url);
$phpArray = json_decode($jsonData);
//print_r($phpArray);
$image=findSource($phpArray);


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
What are the coordinates of <?php echo $array[$randEntry][1] ?>, <?php echo $array[$randEntry][0] ?> ?
<image src="<?php echo $image ?>"></image>
