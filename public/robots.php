<?php
$https = false;
if (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') {
    $https = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
    $https = true;
} elseif (!empty($_SERVER['REQUEST_SCHEME']) && strtolower((string)$_SERVER['REQUEST_SCHEME']) === 'https') {
    $https = true;
}

$scheme = $https ? 'https' : 'http';
$host = !empty($_SERVER['HTTP_HOST']) ? (string)$_SERVER['HTTP_HOST'] : 'localhost';
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
