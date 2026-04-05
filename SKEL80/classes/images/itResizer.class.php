<?php
// ================ CRC ================
// version: 1.15.04
// hash: 1386c1786bba838479ff59aa13fec24641191fc06fd8e980b6b2423da3d36c45
// date: 29 March 2021  0:44
// ================ CRC ================
/* 
Скрипт дл работы с изображеними - Magic Resizer
*/

//..............................................................................
// itResizer : класс изменения размеров изображений для сайта
//..............................................................................
class itResizer
	{
	// закрытые переменные описывающие параметры доступа к базе данных
	public $input_image_name, $output_image_name, $new_x, $new_y, $crop, $logo_name, $quality, $logo_place;

	//..............................................................................
	// конструктор класса - соединяется с базой данных при создании класса
	//..............................................................................
	public function __construct($input_image_name, $output_image_name, $new_x, $new_y, $crop=false, $logo_name='', $quality = 100, $logo_place=NULL)
		{
		$this->input_image_name 	= $input_image_name;
		$this->output_image_name	= $output_image_name;
		$this->new_x		= $new_x;
		$this->new_y		= $new_y;
		$this->crop		= $crop;
		$this->logo_name	= $logo_name;
		$this->quality		= $quality;
		$this->logo_place	= $logo_place;
		}

	//..............................................................................
	// функция производит ресайзинг изображения с добавлением логотипа 
	//..............................................................................
	public function compile()
		{
		if (is_dir($this->input_image_name) OR !file_exists($this->input_image_name)) return;
//		if (!file_exists($this->input_image_name)) return;

		if ($this->logo_place==NULL) $this->logo_place = LOGO_POSITION;

		//получим даныне об изображении
		
		if (!is_array(@$image_info = getimagesize($this->input_image_name)))
				{
				echo "resize reading error {$this->input_image_name}";
				debug_print_backtrace();
				}
		@$exif_info = exif_read_data($this->input_image_name);

		// определим тип изображения
		$image_type 	= $image_info[2];

		// определим размеры входного изображения
		$input_x 	= $image_info[0];
		$input_y 	= $image_info[1];
	
		// создадим изображение того типа, которому соотсветстует файл изображения
		switch($image_type)
			{
			case IMAGETYPE_JPEG : {
	         		$img = imagecreatefromjpeg($this->input_image_name);
	         		break;
				}

			case IMAGETYPE_GIF : {
	         		$img = imagecreatefromgif($this->input_image_name);
	         		break;
				}

			case IMAGETYPE_PNG : {
	         		$img = imagecreatefrompng($this->input_image_name);
	         		break;
				}
			}

		// повернем изображение если есть данные
		if(!empty($exif_info['Orientation']))
			{
	                switch($exif_info['Orientation'])
				{
		                case 8:
					{
					$img = imagerotate($img,90,0);
					$tmp = $input_x;
			                $input_x = $input_y;
					$input_y = $tmp;
					break;
					}
				case 3:
					{
					$img = imagerotate($img,180,0);
					break;
					}
				case 6:
					{
					$img = imagerotate($img,-90,0);
					$tmp = $input_x;
			                $input_x = $input_y;
					$input_y = $tmp;
					break;
					}
		                } 
			}

		//включим установки прозрачности
		imageAlphaBlending($img, false);
		imageSaveAlpha ($img, true);

	
		// соотношение сторон входного изображения
		$ratio 		= $input_x / $input_y;		

		// соотношение сторон выходного изображения (рамки)
		$new_ratio 	= $this->new_x / $this->new_y;      	

		// абсолютное соотношение сторон между входным и выходным изображением (рамок)
		$abs_ratio 	= ($ratio > $new_ratio)*1;	
		
		if ($this->crop)
			{
			// вырезание изображение делается по другим законам
			// сначала нужно преобразовать изображение так, чтобы новые размеры лежали внутри изображения

			if ($abs_ratio)
				{
				// вписываем рамки в горизотальное изображение 
				$width_y = $this->new_y;
				$width_x = round ($this->new_y * $ratio);


				// установим верхнюю левую точку для crop
				$x = round ( ($width_x - $this->new_x) / 2 );
				$y = 0;
				} else 	{
					// вписываем рамки в вертикальное изображение
					$width_x = $this->new_x;
					$width_y = round ($this->new_x / $ratio);

					// установим верхнюю левую точку для crop
					$x = 0;
					$y = round ( ($width_y - $this->new_y) / 2 );
					}


			// меняем размеры изображения так, чтобы получилось, что рамки crop попадают внутрь изображения
			$img_res = imagecreatetruecolor($width_x, $width_y);
			imageAlphaBlending($img_res, false);
			imageSaveAlpha ($img_res, true);

			imagecopyresampled($img_res, $img, 0, 0, 0, 0, $width_x, $width_y, $input_x, $input_y);

			// вырезаем crop из изображения
			$img_out = imagecreatetruecolor($this->new_x, $this->new_y);
			imageAlphaBlending($img_out, false);
			imageSaveAlpha ($img_out, true);

			imagecopy($img_out, $img_res, 0, 0, $x, $y, $this->new_x, $this->new_y);

			// удаляем промежуточное изображение
			imagedestroy($img_res);

			// конец кода для crop
			} else 	{
				// вписываем картинку в заданную рамку, увеличивая или уменьшая оригинал
				if ($abs_ratio)
					{
					// вписываем горизотальное изображение в вертикальные рамки
					$width_x = $this->new_x;
					$width_y = round ($width_x / $ratio);
					} else 	{
						// вписываем горизотальное изображение в горизонтальные рамки
						$width_y = $this->new_y;
						$width_x = round ($width_y * $ratio);
						}

				// производим ресемплирование выходного изображения
				$img_out = imagecreatetruecolor($width_x, $width_y);
				imageAlphaBlending($img_out, false);
				imageSaveAlpha ($img_out, true);

				imagecopyresampled($img_out, $img, 0, 0, 0, 0, $width_x, $width_y, $input_x, $input_y);
				}
		// ------------------------------------------------------------------	
		// накладываем логотип, если указан файл логотипа и файл приустствует
		// ------------------------------------------------------------------
		if ( ($this->logo_name !='') and file_exists($_SERVER['DOCUMENT_ROOT'].$this->logo_name) )
			{
			$img_logo = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].$this->logo_name);
                
			// ширина и высота изображения логотипа
			$logo_sx = imagesx($img_logo);
			$logo_sy = imagesy($img_logo);

			$img_sx = imagesx($img_out);
			$img_sy = imagesy($img_out);

			// проверим есть логотип больше изображения по ширине
			if (($logo_sx>$img_sx) or ($logo_sy>$img_sy))
				{
				$dev_ratio = $img_sx / $logo_sx;
				$new_logo_sx = $img_sx;
				$new_logo_sy = $logo_sy * $dev_ratio;

				if ($new_logo_sy>$img_sy)
					{
	       				$dev_ratio = $img_sy / $new_logo_sy;
					$new_logo_sx = $img_sx * $dev_ratio;
					$new_logo_sy = $img_sy;
					}


				$new_logo = imagecreatetruecolor($logo_sx, $logo_sy);
				imageAlphaBlending($new_logo, false);
				imageSaveAlpha ($new_logo, true);

				imagecopyresampled($new_logo, $img_logo, 0, 0, 0, 0, $new_logo_sx, $new_logo_sy, $logo_sx, $logo_sy );
				imagedestroy($img_logo);
				$img_logo = $new_logo;
                	        $logo_sx = $new_logo_sx; 
                        	$logo_sy = $new_logo_sy;
	
				imageAlphaBlending($img_logo, false);
				imageSaveAlpha ($img_logo, true);
				}

			// ----------------------------------------------------------------------------------
			// место расположения логотипа :TOP, TOP_LEFT, TOP_RIGHT, DOWN, DOWN_LEFT, DOWN_RIGHT
			// ----------------------------------------------------------------------------------
			
			if (strpos($this->logo_place,'RANDOM_')!==false)
				{
				switch ($this->logo_place)
					{
					case 'RANDOM_TOP': {
						switch (rand(1,3))
							{
							case 1	: { $position = 'TOP';
								break;
								}
							case 2	: { $position = 'TOP_LEFT';
								break;
								}
							case 3	: { $position = 'TOP_RIGHT';
								break;
								}
							}
						break;
						}
					case 'RANDOM_BOTTOM': {
						switch (rand(1,3))
							{
							case 1	: { $position = 'BOTTOM';
								break;
								}
							case 2	: { $position = 'BOTTOM_LEFT';
								break;
								}
							case 3	: { $position = 'BOTTOM_RIGHT';
								break;
								}
							}
						break;
						}
					default:
					case 'RANDOM_ALL': {
						switch (rand(1,6))
							{
							case 1	: { $position = 'TOP';
								break;
								}
							case 2	: { $position = 'TOP_LEFT';
								break;
								}
							case 3	: { $position = 'TOP_RIGHT';
								break;
								}
							case 4	: { $position = 'BOTTOM';
								break;
								}
							case 5	: { $position = 'BOTTOM_LEFT';
								break;
								}
							case 6	: { $position = 'BOTTOM_RIGHT';
								break;
								}
							}
						break;
						}
					} // switch
				} else 	{
					$position = $this->logo_place;

					switch ($position)
						{
						case 'TOP' : {
							$logo_image_x = ($img_sx - $logo_sx) /2;// центрируем
							$logo_image_y = 0;			// размещает по верхнему краю
							break;
							}

						case 'TOP_LEFT' : {
							$logo_image_x = 0; 			// слева
							$logo_image_y = 0;  			// размещает по верхнему краю
							break;
							}

						case 'TOP_RIGHT' : {
							$logo_image_x = $img_sx - $logo_sx; 	// справа
							$logo_image_y = 0;			// размещает по верхнему краю
							break;
							}

						case 'BOTTOM': {
							$logo_image_x = ($img_sx - $logo_sx) /2;// центрируем
							$logo_image_y = $img_sy - $logo_sy; 	// размещает по нижнему краю
							break;
							}

						case 'BOTTOM_LEFT' : {
							$logo_image_x = 0; 			// слева
							$logo_image_y = $img_sy - $logo_sy; 	// размещает по нижнему краю
							break;
							}

						case 'BOTTOM_RIGHT' : {
							$logo_image_x = $img_sx - $logo_sx; 	// справа
							$logo_image_y = $img_sy - $logo_sy; 	// размещает по нижнему краю
							break;
							}

						case 'CENTER' :	{
							$logo_image_x = ($img_sx - $logo_sx) /2;// центрируем
							$logo_image_y = ($img_sy - $logo_sy) /2;// центрируем
							break;
							}

						} // switch
					} //else

                        // включаем альфа-каналы
			imageAlphaBlending($img_out, true);
			imageSaveAlpha ($img_out, true);



	//		imagecopy($img_out, $img_logo, $logo_image_x, $logo_image_y, 0, 0, $logo_sx, $logo_sy);
			imagecopyresized($img_out, $img_logo, $logo_image_x, $logo_image_y, 0, 0, $logo_sx, $logo_sy, $logo_sx, $logo_sy);
			imagedestroy($img_logo);
			} // ----- if ---------

		// выводим изоражение в файл заданного качества (для jpeg изображений) и того же типа, что и входное изображение
		if ( $image_type == IMAGETYPE_JPEG )
			{
			imagejpeg($img_out, $this->output_image_name, $this->quality);
	         	}
		elseif ( $image_type == IMAGETYPE_GIF )
			{
			imagegif($img_out, $this->output_image_name);
			}
		elseif ( $image_type == IMAGETYPE_PNG )
			{
			imagepng($img_out, $this->output_image_name);
			}

		// удаляем исходные и выходные данные изображения экономим память
		imagedestroy($img);
		imagedestroy($img_out);	
		}

	} // class

?>