<?php
header('Content-type:image/png');
$dimension=600;
$targetPercent=rand(1,99);
$width=rand(5,10);
$target=round($dimension/$width*$dimension/$width*$targetPercent/100);
$image = imagecreate($dimension,$dimension); // (x, y)

$squares=array();
$colored=array();
for ($r=0;$r<$dimension/$width;$r++)
  for ($c=0;$c<$dimension/$width;$c++)
     $squares[]=new Square($r,$c);
for ($i=0;$i<$target;$i++){
   $rand=array_rand($squares);
   $colored[]=$squares[$rand];
   unset($squares[$rand]);
}
//
$background = imagecolorallocate($image, 0, 0, 244);
$black = imagecolorallocate($image, 150, 15, 15);

foreach($colored as $key=>$var){
  $x=$var->x*$width;
  $y=$var->y*$width;
  imagefilledrectangle($image, $x, $y, $x+$width, $y+$width, $black);

}
imagepng($image);

class Square{
  var $x;
  var $y;
  function __construct($x,$y){
    $this->x=$x;
    $this->y=$y;
  }
}
?>
