<?php
define('NO_PREPARED_ARR', 1);
set_time_limit(3);

/* скрипт на который поизводит перевод htaccess если не найдена картинка */
include ("engine/kernel.php");

if (isset($_REQUEST['img_url']))
	{
	$clear_name = clear_picture_tech($_REQUEST['img_url']);
	$tech_type = get_picture_tech_type($_REQUEST['img_url']);
	if (!file_exists(PICTURE_ROOT.$_REQUEST['img_url']))
		{
		$_REQUEST['img_url'] = basename(get_avatar($clear_name,$tech_type));
		}
	show_image(PICTURE_ROOT.$_REQUEST['img_url']);
	}

?>