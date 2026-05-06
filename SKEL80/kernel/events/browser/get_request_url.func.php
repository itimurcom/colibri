<?php
// возвращает адрес страницы с которой производился вызов
function get_request_url()
	{
	$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
	$scheme = $https ? 'https' : 'http';
	$host = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '' ? $_SERVER['HTTP_HOST'] : 'localhost';
	$uri = isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '' ? $_SERVER['REQUEST_URI'] : '/';
	return "{$scheme}://{$host}{$uri}";
	}
?>