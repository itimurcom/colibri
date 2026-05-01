<?php
$scheme = 'http';
if (
    (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') ||
    (!empty($_SERVER['REQUEST_SCHEME']) && strtolower((string)$_SERVER['REQUEST_SCHEME']) === 'https') ||
    (!empty($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')
) {
    $scheme = 'https';
}

$httpHost = !empty($_SERVER['HTTP_HOST']) ? (string)$_SERVER['HTTP_HOST'] : 'localhost';
$hostNoPort = preg_replace('/:\\d+$/', '', $httpHost);
$baseUrl = $scheme.'://'.$httpHost;

header('Content-Type: text/plain; charset=utf-8');

echo "User-agent: *\n";
echo "Disallow: /engine/\n";
echo "Disallow: /languages/\n";
echo "Disallow: /mvc/\n";
echo "Disallow: /themes/\n\n";
echo "User-agent: *\n";
echo "Allow: /\n\n";
echo "Host: {$hostNoPort}\n";
echo "Sitemap: {$baseUrl}/sitemap.xml\n";
