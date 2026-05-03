<?php
define('NO_PREPARED_ARR', 1);

set_time_limit(3);

/* скрипт на который поизводит перевод htaccess если не найдена картинка */
include ("engine/kernel.php");

function public_img_request_value($key='', $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

function public_img_safe_relative_path($path='')
	{
	$path = str_replace(["\\", "\0"], '/', (string)$path);
	$path = ltrim($path, '/');
	if ($path==='' || preg_match('~(^|/)\.\.(/|$)~', $path) || preg_match('~^[A-Za-z]:/~', $path))
		{
		return NULL;
		}
	return $path;
	}

function public_img_send_not_found()
	{
	if (!headers_sent())
		{
		http_response_code(404);
		}
	return NULL;
	}

$img_url = public_img_safe_relative_path(public_img_request_value('img_url'));
if (is_null($img_url) || !defined('PICTURE_ROOT'))
	{
	return public_img_send_not_found();
	}

$clear_name = clear_picture_tech($img_url);
$tech_type = get_picture_tech_type($img_url);
$image_path = PICTURE_ROOT.$img_url;

if (!file_exists($image_path))
	{
	$generated = basename(get_avatar($clear_name, $tech_type));
	$image_path = PICTURE_ROOT.$generated;
	}

if (!file_exists($image_path))
	{
	return public_img_send_not_found();
	}

show_image($image_path);
?>
