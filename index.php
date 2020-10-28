<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Загрузка изображения и наложение рамки</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<div class="container">

		<div class="form">
			<h3>Загрузите изображение иконы</h3>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="form-item">
					<small>с компьютера</small>
					<input type="file" name="upfile" id="UpFileImage">
				</div>
				<div class="form-item">
					<small>или по ссылке из интернета</small>
					<input type="text" name="url_file" id="URLFileImage" placeholder="Вставьте URL изображения">
				</div>
				<div class="form-item">
					<input type="submit" value="Загрузить" name="send" class="btn">
				</div>
			</form>
		</div>

	<?php

	require_once('func.php');

	if (isset($_POST['send']))
	{
		clear_dir('upload/'); // очищаем папку
		$typesImages = array('image/jpeg', 'image/png');

		if ($_POST['url_file'] == '')
		{
			$upFile = $_FILES['upfile'];
			if ($upFile['error'] != 4 AND $_POST['url_file'] == '')
			{
				if (in_array($upFile['type'], $typesImages))
				{
					if ($upFile['type'] == "image/jpeg")
					{
						$ras = "jpg";
					}
					else
					{
						$ras = "png";
					}
					$upFileName = "ikona_".date('YmdHis').".".$ras;

					copy($upFile['tmp_name'], 'upload/'.$upFileName); // сохраняем
					imgResize('upload/', $upFileName, $upFile['type'], 500); // ресайзим
				}
				else
				{
					$error = "Неверный формат изображения";
				}
			}
			else
			{
				$error = "Не выбран файл изображения для загрузки";
			}
		}
		else
		{
			$upFile = trim($_POST['url_file']);
			if ($_POST['url_file'] != '')
			{
				//error_reporting('ALL');
				if (file_get_contents($upFile) == TRUE)
				{
					$mime_type = getUrlMimeType($upFile); // определяем тип файла

					if (in_array($mime_type, $typesImages))
					{
						$val_6_file = mb_substr($upFile,-6);
						$ras_file = explode(".",$val_6_file);
						
						if ($mime_type == "image/jpeg")
						{
							$ras = "jpg";
						}
						else
						{
							$ras = "png";
						}

						$upFileName = "ikona_".date('YmdHis').".".$ras;

						copy($upFile, 'upload/'.$upFileName); // сохраняем
						imgResize('upload/', $upFileName, $mime_type, 500); // ресайзим
					}
					else
					{
						$error = "Неверный формат изображения";
					}
				}
				else
				{
					$error = "Неверно указана ссылка на изображение";
				}
			}
			else
			{
				$error = "Не введён адрес ссылки";
			}
		}


		
		// вывод ошибок
		if ($error != "")
		{
			echo '<p class="error">'.$error.'</p>';
		}

		// вывод изображения после загрузки
		if ($error == "")
		{
			$upFileImage = 'upload/'.$upFileName;
			$size = getimagesize($upFileImage);

			$img_w = $size[0];
			$img_h = $size[1];

			if ($img_w > $img_h)
			{
				$img_orient = '';
			}
			else
			{
				$img_orient = ' img-vertical';
			}

			echo '
			<div class="img'.$img_orient.'">
				<img src="'.$upFileImage.'" alt="img" id="target">

				<form id="checkCoords">
					<input type="hidden" name="crop-img" value="'.$upFileImage.'">
					<input type="hidden" id="x" name="x">
					<input type="hidden" id="y" name="y">
					<input type="hidden" id="w" name="w">
					<input type="hidden" id="h" name="h">
				</form>

				<button type="button" class="btn-crop">Обрезать</button>
			</div>';

			
			// вывод рамок для выбора
			echo '<div class="ramki hidden">';
			echo "<p>Выберите рамку для иконы</p>";
			for ($i = 1; $i <= 6; $i++)
			{
				echo '<img src="ramki/ramka_'.$i.'.png" alt="рамка № '.$i.'" class="ramka" data-ramka="'.$i.'">';
			}
			echo '</div>';
		}

	}
	?>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 

	<link rel="stylesheet" href="Jcrop/css/jquery.Jcrop.min.css">
	<script src="Jcrop/js/jquery.Jcrop.min.js"></script>

	<script src="main.js"></script>

</body>
</html>