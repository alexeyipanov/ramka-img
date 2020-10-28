<?php

if (count($_POST) > 0)
{
	$src = $_POST['crop-img'];
	$image_size = getimagesize($src);
	$mime_type = $image_size['mime'];

	$x = $_POST['x'];
	$y = $_POST['y'];
	$w = $_POST['w'];
	$h = $_POST['h'];

	if ($w >= $h)
	{
		$orient = "gorizontal";
	}
	else
	{
		$orient = "vertical";
	}

	$targ_w = $w;
	$targ_h = $h;

	if ($mime_type == 'image/jpeg')
	{
		$img = imagecreatefromjpeg($src);
	}
	else
	{
		$img = imagecreatefrompng($src);
	}
	
	$dst = ImageCreateTrueColor($targ_w, $targ_h);
	imagecopyresampled($dst, $img, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);

	if ($mime_type == 'image/jpeg')
	{
		ImageJPEG ($dst, $src, 80);
	}
	else
	{
		ImagePNG ($dst, $src);
	}
	imagedestroy($dst);
	
	$result = array();
	$result['image'] = $src."?".date("YmdHis");
	$result['orient'] = $orient;

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	echo json_encode($result);
}

?>
