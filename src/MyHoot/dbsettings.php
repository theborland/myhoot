<?php
$pusherIP='172.24.18.10';
$pusherIP='192.168.0.106';
$pusherIP='172.24.18.40';
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "myhoot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include ("siteFunctions.php");

// An array of $_POST keys that are acceptable
if (isset($whitelist))
{
    foreach($whitelist as $key) {
       if (isset($_POST[$key])) {
         $$key = $conn->real_escape_string($_POST[$key]);
       }
       else if (isset($_GET[$key])) {
         $$key = $conn->real_escape_string($_GET[$key]);
       }
       else $$key = "";
    }
}

/*
//Key:
6e3f9f15fe084fbcd2e8e690f5af2aa4

Secret:
664c95e67ad7a34f
*/

?>
<?php
/*
$api_key = '6e3f9f15fe084fbcd2e8e690f5af2aa4';

$tag = 'ghana,accra';
$perPage = 25;
$url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
$url.= '&api_key='.$api_key;
$url.= '&tags='.$tag;
$url.= '&per_page='.$perPage;
$url.= '&format=json';
$url.= '&nojsoncallback=1';
$url.='&sort=relevance';
$response = json_decode(file_get_contents($url));
$photo_array = $response->photos->photo;
*/
/*
 print ("<pre>");
 print_r($response);
 print ("</pre>");
*/
/*
foreach($photo_array as $single_photo){

$farm_id = $single_photo->farm;
$server_id = $single_photo->server;
$photo_id = $single_photo->id;
$secret_id = $single_photo->secret;
$size = 'b';

$title = $single_photo->title;

$photo_url = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';

print "<img title='".$title."' src='".$photo_url."' />";

}
*/
?>
