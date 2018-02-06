<?php

function mergeImage($source, $filter)
{
	print_r($filter);
	print_r($source);
	exit();
	$imgSource = imagecreatefrompng($source);
	$imgFilter = imagecreatefrompng($filter);

	$source_x = imagesx($imgSource);

	$filter_x = imagesx($imgFilter);
	$filter_y = imagesy($imgFilter);

	$dest_x = $source_x - $filter_x;

	imagecopymerge($imgSource, $imgFilter, $dest_x, 0, 0, 0, $filter_x, $filter_y, 0);

	header("Content-type: image/png");

	imagepng($im_source);

	imagedestroy($imgSource);
	imagedestroy($imgFilter);
}

?>