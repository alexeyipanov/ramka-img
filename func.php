<?php

// функция изменения размера изображения
function imgResize($dir, $file_img, $type_img, $crop_size, $min=false)
{
	$image_info = getimagesize($dir."/".$file_img);

	if ($image_info[0] > $image_info[1])
	{
		$new_width = $crop_size;
		$co = $image_info[0] / $new_width * 100;
		$new_height = round($image_info[1] / $co * 100);
	}
	else
	{
		$new_height = $crop_size;
		$co = $image_info[1] / $new_height * 100;
		$new_width = round($image_info[0] / $co * 100);
	}

	$dst = imagecreatetruecolor($new_width, $new_height);
	if ($type_img == "image/jpeg")
	{
		$src = imagecreatefromjpeg($dir.'/'.$file_img);
		ImageCopyResampled ($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $image_info[0], $image_info[1]);
		if ($min == true)
		{
			imagejpeg($dst, $dir.'/min_'.$file_img, 80);
		}
		else
		{
			imagejpeg($dst, $dir.'/'.$file_img, 80);
		}
	}
	if ($type_img == "image/png")
	{
		$src = imagecreatefrompng($dir.'/'.$file_img);
		ImageCopyResampled ($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $image_info[0], $image_info[1]);
		if ($min == true)
		{
			imagepng($dst, $dir.'/min_'.$file_img);
		}
		else
		{
			imagepng($dst, $dir.'/'.$file_img);
		}
	}
}


// упрощенная функция scandir
function myscandir($dir)
{
	$list = scandir($dir);
	unset($list[0],$list[1]);
	return array_values($list);
}

// функция очищения папки
function clear_dir($dir)
{
	$list = myscandir($dir);
	
	foreach ($list as $file)
	{
		if (is_dir($dir.$file))
		{
			clear_dir($dir.$file.'/');
			rmdir($dir.$file);
		}
		else
		{
			unlink($dir.$file);
		}
	}
}

// определения типа файла по ссылке
function getUrlMimeType($url) {
	$buffer = file_get_contents($url);
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	return $finfo->buffer($buffer);
}

?>