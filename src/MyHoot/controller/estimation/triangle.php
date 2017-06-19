<?php
header('Content-type:image/png');
$fullPath=dirname(dirname(dirname(__FILE__)));

$dimension=550;
$target=40;
$image = imagecreate($dimension,$dimension); // (x, y)
//$target=40;
//$a=rand($target/5,$target-2);
$angle1=rand(25,160);
while ($angle1>80 && $angle1<100)
  $angle1=rand(25,160);
$angle2=rand(20,180-20-$angle1);
while ($angle2>80 && $angle2<100)
  $angle2=rand(20,180-20-$angle1);
$angle3=180-$angle1-$angle2;
$b=$target;
while ($b<100)
   $b*=2;

   $x1=200;
   $y1=450;
   $x2=$x1+$b;
   $y2=$y1;

$goalSide1=sin(deg2rad($angle2))*$b/sin(deg2rad($angle3));
$goalSide2=sin(deg2rad($angle1))*$b/sin(deg2rad($angle3));
$distance=$distance2=10;
// echo $angle1 . " " . $angle2 . " ". $goalSide1 . " ". $goalSide2 . " ".$b;
//die();
$x3=0;$y3=0;
while ($distance>3 && $distance2>3)
{
     $x3++;
     if ($x3>=$dimension){
       $y3++;
       $x3=0;
     }
     $distance=abs($goalSide1-sqrt(pow($x3-$x1,2)+pow($y3-$y1,2)));
     $distance2=abs(sqrt(pow($x3-$x2,2)+pow($y3-$y2,2)));

}
$points=array($x1,$y1,$x2,$y2,$x3,$y3);

$distance=$distance*$target/$b;
$distance2=$distance2*$target/$b;
//echo "<br>".$x3. " " . $y3;
// die();
// $area=$target*$a;
// $b=$target;
// $toShowA=$a;
// $toShowB=$target;
// while ($a<150 && $b<150)
// {
//   $a*=2;
//   $b*=2;
// }
//die ($a . "  ". $b);
//circ=pi*(3(a+b)-sqrt((3a+b)(a+3b)))

//
$background = imagecolorallocate($image, 0, 0, 244);
$red = imagecolorallocate($image, 150, 15, 15);
$blue = imagecolorallocate($image, 0, 0,0);

imagefilledpolygon($image,$points,3,$red);
//imagelinethick($image, $dimension/6,$dimension/6,$dimension/6+$a-2,$dimension/6, $blue, 5);
//imagepng($image,$fullPath."/controller/estimation/tmp/".$randomFileName.".png");

$font =$fullPath.'/fonts/Capriola-Regular.ttf';
imagettftext($image, 25, 0, ($x1+$x3)/2,($y1+$y3)/2, $blue, $font, round($b,1));

//imagepng($image,$fullPath."/controller/estimation/tmp/".$randomFileName.".png");

imagepng($image);
function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
    /* this way it works well only for orthogonal lines
    imagesetthickness($image, $thick);
    return imageline($image, $x1, $y1, $x2, $y2, $color);
    */
    if ($thick == 1) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
}

?>
