<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// CONFIG
$cfg['min_size'] = 8;
$cfg['max_size'] = 512;
$cfg['def_size'] = 40;

// Size Option
if(isset($_GET['size'])) {
	if(is_numeric($_GET['size'])) {
		if($_GET['size'] >= $cfg['min_size'] && $_GET['size'] <= $cfg['max_size']) {
			$size = $_GET['size'];
		} else {
			$size = $cfg['def_size'];
		}
	} else {
		$size = $cfg['def_size'];
	}
} else {
	$size = $cfg['def_size'];
}

// Binary Data
$binaryData = (isset($_GET['data']) ? $_GET['data'] : "");
if(strlen($binaryData) != 64) {
	$binaryData = bin2hex($binaryData);
}

// Pixel Formula
$pixelSize = $size/8;
$hex = $binaryData;

// Decode Colors
function color($mark) {
	$mark = (is_numeric($mark) ? $mark : strtoupper($mark));
	if(strcmp($mark, "0") == 0) return "#111111";
	if(strcmp($mark, "1") == 0) return "#000000";
	if(strcmp($mark, "2") == 0) return "#808080";
	if(strcmp($mark, "3") == 0) return "#ffffff";
	if(strcmp($mark, "4") == 0) return "#fe0000";
	if(strcmp($mark, "5") == 0) return "#ff7f00";
	if(strcmp($mark, "6") == 0) return "#ffff00";
	if(strcmp($mark, "7") == 0) return "#80ff00";
	if(strcmp($mark, "8") == 0) return "#00ff01";
	if(strcmp($mark, "9") == 0) return "#00fe81";
	if(strcmp($mark, "A") == 0) return "#00ffff";
	if(strcmp($mark, "B") == 0) return "#0080ff";
	if(strcmp($mark, "C") == 0) return "#0000fe";
	if(strcmp($mark, "D") == 0) return "#7f00ff";
	if(strcmp($mark, "E") == 0) return "#ff00fe";
	if(strcmp($mark, "F") == 0) return "#ff0080";
}

for($y=0; $y<8; $y++) {
	for($x=0; $x<8; $x++) {
		$offset = ($y*8)+$x;
		$Cuadrilla8x8[$y][$x] = substr($hex, $offset, 1);
	}
}

$SuperCuadrilla = array();

for($y=1; $y<=8; $y++) {
	for($x=1; $x<=8; $x++) {
		$bit = $Cuadrilla8x8[$y-1][$x-1];
		for($repiteY=0; $repiteY<$pixelSize; $repiteY++) {
			for($repite=0; $repite<$pixelSize; $repite++) {
				$translatedY = ((($y-1)*$pixelSize)+$repiteY);
				$translatedX = ((($x-1)*$pixelSize)+$repite);
				$SuperCuadrilla[$translatedY][$translatedX] = $bit;
			}
		}
	}
}

$img = ImageCreate($size, $size);

for($y=0; $y<$size; $y++) {
	for($x=0; $x<$size; $x++) {
		$bit = $SuperCuadrilla[$y][$x];
		$color = substr(color($bit), 1);
		$r = substr($color, 0, 2);
		$g = substr($color, 2, 2);
		$b = substr($color, 4, 2);
		$superPixel = ImageCreate(1, 1);
		$cl = imageColorAllocateAlpha($superPixel, hexdec($r), hexdec($g), hexdec($b), 0);
		ImageFilledRectangle($superPixel, 0, 0, 1, 1, $cl);
		ImageCopy($img, $superPixel, $x, $y, 0, 0, 1, 1);
		ImageDestroy($superPixel);
	}
}

header("Content-type: image/gif");
ImageRectangle($img, 0, 0, $size-1, $size-1, ImageColorAllocate($img, 0, 0, 0));
imagecolortransparent($img, imagecolorexact($img, 17, 17, 17));
ImageGif($img);