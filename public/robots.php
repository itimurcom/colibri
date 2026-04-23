<?php
$https = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');

$scheme = $https ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '' ? $_SERVER['HTTP_HOST'] : 'localhost';
$baseUrl = $scheme . '://' . $host;

header('Content-Type: text/plain; charset=UTF-8');

echo "User-agent: *\n";
echo "Disallow: /engine/\n";
echo "Disallow: /languages/\n";
echo "Disallow: /mvc/\n";
echo "Disallow: /themes/\n\n";
echo "User-agent: *\n";
echo "Allow: /\n\n";
echo 'Host: ' . $host . "\n";
echo 'Sitemap: ' . $baseUrl . "/sitemap.xml\n";
