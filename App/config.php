<?php
// General settings
define('ROOT', __DIR__ . '/../');
error_reporting(E_ALL | E_STRICT);
define('HOME', $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']));
define('SHOW_ERRORS', 1);
define('LOG_FILE_PATH', ROOT . 'logs/errors.log');
define('LOG_DATE', '[' . date("Y-m-d H:i:s 'e'") . ']');
ini_set('log_errors', 'On');
ini_set('display_errors', 1);
date_default_timezone_set("Europe/Kiev");
session_set_cookie_params(1440, '/', '', true, true);
ini_set('error_log', LOG_FILE_PATH);
session_start();