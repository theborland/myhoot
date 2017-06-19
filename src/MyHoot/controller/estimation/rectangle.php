<?php
header('Content-type:image/png');
$fullPath=dirname(dirname(dirname(__FILE__)));

$dimension=550;
$target=40;
$image = imagecreate($dimension,$dimension); // (x, y)
//$target=40;
$a=rand($target/3,$target*3);
$area=$target*$a;
$b=$target;
$toShowA=$a;
$toShowB=$target;
while ($a<150 && $b<150)
{
  $a*=2;
  $b*=2;
}
//die ($a . "  ". $b);
//circ=pi*(3(a+b)-sqrt((3a+b)(a+3b)))

//
$background = imagecolorallocate($image, 0, 0, 244);
$red = imagecolorallocate($image, 150, 15, 15);
$blue = imagecolorallocate($image, 0, 0,0);

imagefilledrectangle($image,$dimension/6,$dimension/6,$a*2,$b*2,$red);
imagelinethick($image, $dimension/6,$dimension/6,$dimension/6+$a-2,$dimension/6, $blue, 5);
//imagepng($image,$fullPath."/controller/estimation/tmp/".$randomFileName.".png");

$font =$fullPath.'/fonts/Capriola-Regular.ttf';
imagettftext($image, 25, 0, $dimension/2+$a/2,$dimension/2-10, $blue, $font, round($a));

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
