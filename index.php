<?php
// General settings
ini_set('display_errors',1);
error_reporting(E_ALL);
define('ROOT', dirname(__FILE__));
define('HOME', dirname($_SERVER['HTTP_HOST']));
session_set_cookie_params(1440,'/','',false,true);
session_start();

// Autoloading core classes
function coreClassLoader($classname) {
    $filename = ROOT . '/app/core/' . $classname .".php";
    include_once($filename);
}
spl_autoload_register('coreClassLoader');

// Start variables to tests
$start_variables = require_once(ROOT . '/config/start.php');
extract($start_variables);

$auth = new Auth();

if ($user = $auth->logged_user) {
    $action_link = 'logout';
    $action_link_text = 'Logout';
    // Checking the user group for setting user interface in different layouts
    if ($user['user_group'] == 'administrators') {
        $layout = 'admin_panel';
        $interface_header = 'Admin panel';
        $interface_description = 'Do something amazing today!';
    } elseif ($user['user_group'] == 'managers') {
        $layout = 'manager_dashboard';
        $interface_header = 'Manager dashboard';
        $interface_description = 'Have a good day!';
    } elseif ($user['user_group'] == 'registered_users') {
        $layout = 'user_profile';
        $interface_header = 'User profile';
        $interface_description = 'Make yourself at home!';
    }
    $template = 'main';
    $heading_class = 'info';
}

// Simple error handling. Processing autorization errors:
if ($errors = $auth->errors) {
    extract($errors);
    if (isset($error)) {
        $message_heading = $error['message_heading'];
        $message_description = $error['message_description'];
        ob_start();
        include (ROOT . '/app/views/templates/error.php');
        $errors = ob_get_contents();
        ob_end_clean();
    }
}

ob_start();
include (ROOT . '/app/views/index.php');
$page_template = ob_get_contents();
ob_end_flush();