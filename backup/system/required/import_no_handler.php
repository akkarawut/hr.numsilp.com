<?php
// ===== SET UP alway display error =====
error_reporting(E_ALL);
ini_set('display_errors', 1);
// IF=PRODUCTION error_reporting(0);
// IF=PRODUCTION ini_set('display_errors', 0);

// ===== SET UP memory limit =====
ini_set('memory_limit', '32M');
// DEBUG $memory_limit = ini_get('memory_limit');

// ===== SET UP variable =====
$GLOBALS['amst_start_script_time'] = microtime(true); // for count script time

// ===== SET UP buffering =====
ob_start();

// ===== DEFINE PATH =====
define('ROOT_PHP', dirname(dirname(__FILE__)));

// SERVER_VARIABLE
// [20210701] found aliClound Cant get $_SERVER['HTTP_HOST'] from Load Balanace. 
// $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '...';
$protocol = 'http';
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) {
  $protocol = 'https';
}
if (($protocol == 'http') && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
  $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
}
$preroot = $protocol."://".$http_host.substr(dirname(dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT']));
$preroot = str_replace('\\', '/', $preroot);
define('ROOT_URL', $preroot);
define('CURRENT_URL', (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$http_host.$_SERVER['REQUEST_URI']);

// ===== CONFIG - INFO =====
if (!class_exists('Info')) {
  include ROOT_PHP.'/required/info.php';
}
// ===== CONFIG - MAIN =====
$config_file_path = ROOT_PHP.'/config/'.Info::$system_file['Config']['path'].'.php';
if (file_exists($config_file_path)) {
  include ROOT_PHP.'/config/'.Info::$system_file['Config']['path'].'.php'; // ? Dup ?
}
$config_module_file_path = ROOT_PHP.'/config/'.'config_module.php';
if (file_exists($config_module_file_path)) {
  include $config_module_file_path;
}
// ===== CONFIG - RECHECK VERSION =====
if (defined('Config::version_main') && (Config::version_main)) {
  if (defined('Info::version_main') && (Info::version_main)) {
    if (Info::version_main !== Config::version_main) {
      echo 'Config and Info version are not compatible';
      exit;
    }
  } else {
    echo 'Info version not set';
    exit;
  }
} else {
  echo 'Config version not set';
  exit;
}

// SET UP TIMEZONE
if (defined('Config::time_zone') && (Config::time_zone)) {
  if (in_array(Config::time_zone, timezone_identifiers_list())) {
    define('TIMEZONE', Config::time_zone);
    date_default_timezone_set(Config::time_zone);
  } else {
    echo "Invalid Timezone Setting : ".Config::time_zone;
    exit;
  }
} else {
  define('TIMEZONE', 'Asia/Bangkok');
  date_default_timezone_set('Asia/Bangkok');
}

// SET UP turn off magic quotes for every GET POST and COOKIE
if (in_array(strtolower(ini_get('magic_quotes_gpc')), ['1', 'on'])) {
  $_POST = array_map('stripslashes', $_POST);
  $_GET = array_map('stripslashes', $_GET);
  $_REQUEST = array_map('stripslashes', $_REQUEST);
  $_COOKIE = array_map('stripslashes', $_COOKIE);
}

// AUTO LOAD
$amst_file_path = ROOT_PHP.'/'.Info::$system_file['Amst']['path'].'.php';
if (file_exists($amst_file_path)) {
  include ROOT_PHP.'/'.Info::$system_file['Amst']['path'].'.php';

  spl_autoload_register('Amst::autoLoad');

  // [SESSION]
  SC::session_start();

  // Set user-defined error handler function
  if (("dbadminer" != basename($_SERVER['PHP_SELF'], '.php')) && ("fpdfmy" != basename($_SERVER['PHP_SELF'], '.php')) && ("tinyfilemanager" != basename($_SERVER['PHP_SELF'], '.php'))) {
    // set_error_handler("Log::handleError", E_ALL);
    // register_shutdown_function("Log::handleFatalError");
  }
} else {
  // ALLOW NO CONFIG
  session_start();
}