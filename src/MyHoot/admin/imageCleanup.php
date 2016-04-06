<?php
require '../dbsettings.php';


if (isset($_POST['Submit']) && $_POST['Submit']=="Submit"){
  updateSQL($_POST['id'],$_POST['url']);
}

  $source = getURL();
//echo "dsf";
	function createImageTag ($url, $highlight) {
		return ("<img src = $url alt = '$highlight' onclick = 'check(this)' class = 'imgButton' height = '200' width = '250'>");
	};
	preg_match ('/^(.+), (.+)\n\[(.+)\]$/', $source, $nameCapture);
	preg_match_all ('/\'(\S+)\'/', $nameCapture [3], $imageCapture); //'/([^,]+)/' captures those ,'s inside the links -.-
	$outputSource =  "<html>\n\t<style>\n\t\t.imgButton{\n\t\t\tcursor: pointer;\n\t\t\talign: center;";
	$outputSource .= "\n\t\t}\n\t\t.imgButton:hover{\n\t\t\tborder: 2px solid #ccc\n\t\t}\n\t\t";
	$outputSource .= ".cell{\n\t\t\tmin-width: 250;\n\t\t\tmin-height: 200;\n\t\t\talign: center;\n\t\t\tmax-width: 265;";
	$outputSource .= "\n\t\t\tmax-height: 215;\n\t\t}\n\t</style>\n\t<script>\n\t\tvar source = \"";
	$outputSource .= str_replace("\"","\\\"",(str_replace("\n", "\\n", $source))). "\";\n\t\tvar id = \"" . $id . "\"\n\t\tfunction check(element){ console.log(element.src);   \n\t\t\t";
	$outputSource .= "document.getElementById('url').value=document.getElementById('url').value.replace(element.src,'');  \n";
  $outputSource .= "document.getElementById('url').value=document.getElementById('url').value.replace(', \'\'','');  document.getElementById('url').value=document.getElementById('url').value.replace('\'\',',''); console.log (document.getElementById('url').value); ";
  $outputSource .= "source = (source.replace (\"'\" + element.src + \"'\", \"\\\\0\").replace (\"\\\\0, \", \"\\\\0\").replace (\"\\\\0\", \"\"));";
	$outputSource .= "\n\t\t\tremoveElement (element.parentNode)\n\t\t};\n\t\tfunction handleResponse(response){\n\t\t\talert(response);\n\t\t};\n\t\t";
  $outputSource .= "function add(element){ console.log(element.src);   \n\t\t\t";
  $outputSource .= "document.getElementById('url').value=document.getElementById('url').value.replace(']',',\''+element.src+'\']'); } \n";

	$outputSource .= "function removeElement(element){\n\t\t\t";
$outputSource .= "element.parentNode.removeChild(element);\n\t\t};\n\t\tfunction postLeftover(){";
	$outputSource .= "\n\t\t\tvar xhttp = new XMLHttpRequest();\n\t\t\txhttp.onreadystatechange = function(){\n\t\t\t\tif(xhttp.readyState === XMLHttpRequest.DONE)";
	$outputSource .= "\n\t\t\t\t\thandleResponse(this.reponseText);\n\t\t\t};\n\t\t\txhttp.open(\"POST\", \"Images.php\", true);\n\t\t\txhttp.setRequestHeader";
	$outputSource .= "(\"Content-type\", \"application/x-www-form-urlencoded\");\n\t\t\txhttp.send(\"?newSource=\" + btoa(source) + \"&id=\" + id);\n\t\t};";
	$outputSource .= "\n\t</script>\n\t<body>\n\t\t<p>" . $nameCapture [1] . ", " . $nameCapture[2]. " ".$id;
	$outputSource .= "</p>\n\t\t<form method='POST'><input type='hidden' id='id' name='id' value=\"".$id."\"><input type='hidden' id='url' name='url' value=\"".
  str_replace("\"","$",substr($source,strpos($source,"[")-1)).
  "\"><table border = '1' width = '100%' table-layout = 'fixed'>\n\t\t\t<tr table-layout = 'fixed'>";
	foreach ($imageCapture [0] as $index => $url) {
		if ($index % 5 == 0)
			$outputSource .= "\n\t\t\t</tr>\n\t\t\t<tr table-layout = 'fixed'>";
		$outputSource .= "\n\t\t\t\t<td class = 'cell' width = '260' height = '210'>\n\t\t\t\t\t" . createImageTag ($url, 'Click to remove this image') . "\n\t\t\t\t</td>";
	};
  $outputSource .= "\n\t\t\t\t<td class = 'cell' width = '260' height = '210'>\n\t\t\t\t\t" . getImage($nameCapture [1]) . "\n\t\t\t\t</td>";

	echo ($outputSource . "\n\t\t\t</tr>\n\t\t</table>\n\t\t<input type=\"submit\" value=\"Submit\" name=\"Submit\">>Done</submit>\n\t</form></body>\n</html>");

function getURL()
{
  	global $conn;
    global $id;

  date_default_timezone_set('America/Los_Angeles');
    $myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
		$sql = "SELECT * FROM `data-geo` WHERE `imageUpdatedDate` IS null OR `imageUpdatedDate`<'".$myDate."' ORDER BY rand() LIMIT 1";
    //echo $sql;
		$result = $conn->query($sql);
		if ($result)
		{
		  if($row = $result->fetch_assoc()){
        $id=$row ['id'];
        //echo $row ['city'] . ', ' . $row ['country'] . "\n" . $row ['image'];
				return $row ['city'] . ', ' . $row ['country'] . "\n" . $row ['image'];
		  }
		}
}
function updateSQL($id,$url)
{
  date_default_timezone_set('America/Los_Angeles');
  	global $conn;
    $url=addslashes(str_replace("$","\"",$url));
    $url=trim($url);
    $sql = "UPDATE `data-geo` SET image='".$url."' , imageUpdatedDate='".date('Y-m-d')."' WHERE id='".$id."'";
//die ($sql);
  	$result = $conn->query($sql);

}

function getImage($name){

	$url='http://en.wikipedia.org/w/api.php?action=query&titles='.urlencode($name).'&prop=pageimages&format=json&pithumbsize=1000';
	$jsonData =file_get_contents( $url);
  //echo "u".$url;

	$phpArray = json_decode($jsonData);
//	print_r($phpArray);
	$image=findSource($phpArray);
  return ("Click to add:<img src = '$image' alt = '' onclick = 'add(this)' class = 'imgButton' height = '200' width = '250'>");

//	return $image;

}
?>
