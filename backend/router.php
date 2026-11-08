<?php

/**
 * Router for PHP's built-in web server.
 *
 * Existing files are served directly; everything else is sent through the
 * Laravel front controller. Used by the Docker image so that container-level
 * environment variables (DB_HOST, etc.) are honoured, which `artisan serve`
 * does not propagate to its subprocess.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($path !== '/' && file_exists(__DIR__.'/public'.$path)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__.'/public/index.php';
