<?php
session_start();

define('DOMAIN', 'http://localhost/!wp2_beadando/');
define('BASE_PATH', __DIR__);
define('UPLOAD_PATH', BASE_PATH.'codes/');
define('MAX_UPLOAD_SIZE', 5);


/**
 * Adatbázis kapcsolathoz szükséges adatok
 */
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'wp2_beadando');
