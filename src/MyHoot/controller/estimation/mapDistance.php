<?php
//header('Content-type:image/png');
$fullPath=dirname(dirname(dirname(__FILE__)));

$dimension=550;
//$target=325;

$width=rand(5,10);
$image = imagecreate($dimension,$dimension); // (x, y)
$scaleX=150;

$lineHeight=35;
$scaleY=535;
//imagepng($image);

$distance=0;
$scaleXdist=200;
$dimension=550;
while ($distance<200){
  $x1=rand(20,100);
  $y1=rand(50,100);
  $x2=rand(400,($dimension-20));
  $y2=rand(50,($dimension-200));
  $distance=sqrt(pow($x1-$x2,2)+pow($y1-$y2,2));
}
$units=1;
while($units*($distance/($scaleXdist/2))<$target)
{
  // echo $units;
  if ($scaleXdist>130)
     $scaleXdist-=10;
  else{
     $scaleXdist=400;
     if ($units==1 || $units==10 || $units==100 || $units==5 || $units==50 || $units==500)$units*=2;
     else $units*=2.5;
    //  echo $units;
  }
}

$hostName = $_SERVER['HTTP_HOST'];
//die ($_SERVER['PHP_SELF']);

 $image = imagecreatetruecolor($dimension,$dimension);
 $map = imagecreatefromjpeg($fullPath.'/img/map.jpg');
//
// // sets background to red
 $white = imagecolorallocate($image, 255,255,255);
imagefill($image, 0, 0, $white);


// Merge the red image onto the PNG image
imagecopymerge($image,$map,  0, 0, 0, 0, $dimension, $dimension, 75);
//
// $distance=0;
// while ($distance<200){
//   $x1=rand(20,100);
//   $y1=rand(50,100);
//   $x2=rand(400,($dimension-20));
//   $y2=rand(50,($dimension-200));
//   $distance=sqrt(pow($x1-$x2,2)+pow($y1-$y2,2));
// }
// $units=1;
// while($units*($distance/($scaleXdist/2))<$target)
// {
//   // echo $units;
//   if ($scaleXdist>130)
//      $scaleXdist-=10;
//   else{
//      $scaleXdist=400;
//      if ($units==1 || $units==10 || $units==100 || $units==5 || $units==50 || $units==500)$units*=2;
//      else $units*=2.5;
//     //  echo $units;
//   }
// }
//echo $distance." ".$scaleXdist ." ";
//echo $target . " " . $units;


$pin = imagecreatefrompng($fullPath.'/img/map-pin2.png');
//$scale = imagecreatefrompng('img/map-scale.png');

$black = imagecolorallocate($pin, 0,0,0);
//imagecolortransparent($pin, $black);
imagecopymerge_alpha($image, $pin, $x1-15,$y1-35, 0, 0, 20 ,35,100);
imagecopymerge_alpha($image, $pin, $x2-5,$y2-35, 0, 0, 20 ,35,100);

$white = imagecolorallocate($image, 255,255,255);

imagefilledrectangle($image, 0, 450, $dimension,$dimension, $white);

//imagecopymerge_alpha($image, $scale, $dimension-300,$dimension-50, 0, 0, 251 ,43,100);

// Set Path to Font File
$font =$fullPath.'/fonts/Capriola-Regular.ttf';





imagelinethick($image,$x1,$y1,$x2,$y2,$black,5);


imagelinethick($image,$scaleX,$scaleY,$scaleX+$scaleXdist,$scaleY,$black,4);
imagelinethick($image,$scaleX,$scaleY,$scaleX,$scaleY-$lineHeight,$black,4);
imagelinethick($image,$scaleX+$scaleXdist,$scaleY,$scaleX+$scaleXdist,$scaleY-$lineHeight,$black,4);
imagelinethick($image,$scaleX+$scaleXdist/2,$scaleY,$scaleX+$scaleXdist/2,$scaleY-$lineHeight,$black,4);
imagelinethick($image,$scaleX+$scaleXdist/4,$scaleY,$scaleX+$scaleXdist/4,$scaleY-$lineHeight/2,$black,2);
imagelinethick($image,$scaleX+$scaleXdist/4*3,$scaleY,$scaleX+$scaleXdist/4*3,$scaleY-$lineHeight/2,$black,2);

imagettftext($image, 25, 0, $scaleX-10, $scaleY-$lineHeight-5, $black, $font, "0");
imagettftext($image, 25, 0, $scaleX+$scaleXdist/2-8, $scaleY-$lineHeight-5, $black, $font, $units);
imagettftext($image, 25, 0, $scaleX+$scaleXdist-10, $scaleY-$lineHeight-5, $black, $font, $units*2);
// imagettftext($image, 25, 0, 20, $scaleY-$lineHeight-5, $black, $font, $target);
// imagettftext($image, 25, 0, 20, $scaleY-$lineHeight-50, $black, $font, $distance);
// imagettftext($image, 25, 0, 20, $scaleY-$lineHeight-100, $black, $font, $scaleXdist);

//imagepng($image,$fullPath."/controller/estimation/tmp/2.png");
 imagepng($image,$fullPath."/controller/estimation/tmp/".$rand.".png");
// imagedestroy($image);
// imagedestroy($redimg);

//die(dirname(__FILE__));

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

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    if(!isset($pct)){
        return false;
    }
    $pct /= 100;
    // Get image width and height
    $w = imagesx( $src_im );
    $h = imagesy( $src_im );
    // Turn alpha blending off
    imagealphablending( $src_im, false );
    // Find the most opaque pixel in the image (the one with the smallest alpha value)
    $minalpha = 127;
    for( $x = 0; $x < $w; $x++ )
    for( $y = 0; $y < $h; $y++ ){
        $alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF;
        if( $alpha < $minalpha ){
            $minalpha = $alpha;
        }
    }
    //loop through image pixels and modify alpha for each
    for( $x = 0; $x < $w; $x++ ){
        for( $y = 0; $y < $h; $y++ ){
            //get current alpha value (represents the TANSPARENCY!)
            $colorxy = imagecolorat( $src_im, $x, $y );
            $alpha = ( $colorxy >> 24 ) & 0xFF;
            //calculate new alpha
            if( $minalpha !== 127 ){
                $alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha );
            } else {
                $alpha += 127 * $pct;
            }
            //get the color index with new alpha
            $alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha );
            //set pixel with the new color + opacity
            if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){
                return false;
            }
        }
    }
    // The image copy
    imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
}

?>
