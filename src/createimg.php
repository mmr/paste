<?
// $Id: createimg.php,v 1.5 2006/03/14 16:30:39 mmr Exp $
// Creates random image for checking

function b1n_arrayRand($a){
  return $a[array_rand($a)];
}

// Paths
define('b1n_PATH_LIB',    'lib');     // Libraries
define('b1n_PATH_CONFIG', 'config');  // Configuration

// Configuration
require_once(b1n_PATH_CONFIG . '/config.php');
require_once(b1n_PATH_CONFIG . '/createimg_conf.php');

// Crypto 
require_once(b1n_PATH_LIB . '/Crypt.lib.php');

// Starting session
session_start();

// Getting Random String
$string = '';
$chars_list  = 'abcdefghijklmnpqrstuvwxyz';
$chars_list .= 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
$chars_list .= '123456789';
$chars = rand(3, 5);
$s = strlen($chars_list)-1;
for($i=0; $i<$chars; $i++){
  $string .= $chars_list[rand(0, $s)];
}

// Set the image parameters
$img = imageCreate($img_width, $img_height);
$white = imageColorAllocate($img, 255, 255, 255);
$black = imageColorAllocate($img, 0, 0, 0);

// Background
if($img_use_background){
  $r = rand($img_rgb_min, $img_rgb_max);
  $g = rand($img_rgb_min, $img_rgb_max);
  $b = rand($img_rgb_min, $img_rgb_max);
  $bg = imageColorAllocate($img, $r, $g, $b);
  imageFill($img, 0, 0, $bg);
}

// Circles
if($img_use_circles){
  for ($i=1; $i<=$img_circles; $i++){
    $cx = rand(0, $img_width-10);
    $cy = rand(0, $img_height-3);
    $w  = rand(20, 70);
    $h  = rand(20, 70);

    $r = rand($img_rgb_min, $img_rgb_max);
    $g = rand($img_rgb_min, $img_rgb_max);
    $b = rand($img_rgb_min, $img_rgb_max);
    $color = imageColorAllocate($img, $r, $g, $b);

    imageFilledEllipse($img, $cx, $cy, $w, $h, $color);
  }
}

// Grid
if($img_use_grid){
  $xline = 0; 
  $yline = 0;

  $grid_spacing = b1n_arrayRand($img_grid_spacing_values);
  $grid_x_inc = round($img_width/$grid_spacing);
  $grid_y_inc = round($img_height/$grid_spacing);

  // X Axis
  for($x=0; $x < $grid_x_inc; $x++){
    if($img_grid_random_color){
      $r = rand($txt_rgb_min, $txt_rgb_max);
      $g = rand($txt_rgb_min, $txt_rgb_max);
      $b = rand($txt_rgb_min, $txt_rgb_max);
      $color = imageColorAllocate($img, $r, $g, $b);
    }
    else {
      $color = $black;
    }
    $xline = $xline + $grid_spacing;
    imageLine($img, $xline, 0, $xline, $img_height, $color);
  }

  // Y Axis
  for($y=0; $y < $grid_y_inc; $y++){
    if($img_grid_random_color){
      $r = rand($txt_rgb_min, $txt_rgb_max);
      $g = rand($txt_rgb_min, $txt_rgb_max);
      $b = rand($txt_rgb_min, $txt_rgb_max);
      $color = imageColorAllocate($img, $r, $g, $b);
    }
    else {
      $color = $black;
    }
    $yline = $yline + $grid_spacing;
    imageLine($img, 0, $yline, $img_width, $yline, $color);
  }
}

// Border
if($img_use_border){
  imageRectangle($img, 0, 0, $img_width-1, $img_height-1, $black);
}

$string_len = strlen($string);
$position = $txt_horizontal_pos;

// Looping through each letter
for($i=0; $i < $string_len; $i++){
  $c = $string[$i];

  // Angle
  if($txt_use_angles){
    $angle = rand($txt_letter_angle_min, $txt_letter_angle_max);
  }
  else {
    $angle = 0;
  }

  // Font
  if($txt_use_random_font){
    $font = $txt_font_dir . b1n_arrayRand($txt_fonts);
    echo $font;
    if(!file_exists($font)){
      $font = $txt_font_dir . $txt_fonts[0];
    }
  }
  else {
    $font = $txt_font_dir . $txt_fonts[0];
  }

  // Color
  if($txt_use_random_color){
    $r = rand($txt_rgb_min, $txt_rgb_max);
    $g = rand($txt_rgb_min, $txt_rgb_max);
    $b = rand($txt_rgb_min, $txt_rgb_max);
    $color = imageColorAllocate($img, $r, $g, $b);
  }
  else {
    $color = $black;
  }

  imageTTFText($img, $txt_font_size, $angle, $position, $txt_vertical_pos, $color, $font, $c);
  $position += $txt_letter_spacing_inc;
}

// Saving string in session
$_SESSION['seccode'] = Crypt::encrypt(strtolower($string));

// Showing image
//header('Content-type: image/jpeg');
//imageJpeg($img);
//imageDestroy($img);
?> 
