<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Legacy templates may still generate URLs with a "/public" prefix.
// When using `php artisan serve`, the document root is already /public.
// Normalize "/public/*" -> "/*" so old links continue to work.
$normalizedUri = str_starts_with($uri, '/public/')
    ? substr($uri, 7)
    : $uri;

if ($uri !== $normalizedUri && $normalizedUri !== '/' && is_file(__DIR__.'/public'.$normalizedUri)) {
    $file = __DIR__.'/public'.$normalizedUri;
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: '.$mime);
    readfile($file);
    return true;
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($normalizedUri !== '/' && file_exists(__DIR__.'/public'.$normalizedUri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
