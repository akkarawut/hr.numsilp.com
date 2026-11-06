<?php
require_once F_ROOT_PHP . '/config.php';

function isLocalhost()
{
  return ($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '172.17.0.1');
}

function isIP()
{
  if (isLocalhost()) {
    return true;
  }
  $domain = $_SERVER['SERVER_NAME'];
  if (filter_var($domain, FILTER_VALIDATE_IP) !== false) {
    return true;
  } else {
    return false;
  }
}

if (isLocalhost() || !F_IS_BRIDGE) {
  // set up url
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
  $preroot  = $protocol . "://" . $_SERVER['HTTP_HOST'] . substr(dirname(dirname(__FILE__)), strlen(str_replace('private_html', 'public_html', $_SERVER['DOCUMENT_ROOT'])));
  define('CURRENT_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  $preroot = str_replace('\\', '/', $preroot);
  define('ROOT_URL', $preroot);
  define('ROOT_REDIRECT_URL', dirname($preroot));

  // set up session timeout 3600 = 1 hour
  ini_set('session.gc_maxlifetime', 18000);
  session_set_cookie_params(18000);
  session_start();

  // set up timezone
  define('TIMEZONE', 'Asia/Bangkok');
  date_default_timezone_set('Asia/Bangkok');

  ////////// INITIAL data if host is localhost
  $GLOBALS['debug_curl_speed'] = true;

  // set up display error
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // set uo trigger buffering on every page
  ob_start();

  // check & create folder /cookie
  if (!file_exists(F_ROOT_PHP . '/_framework/cookie')) {
    mkdir(F_ROOT_PHP . '/_framework/cookie', 0777, true);
  }
  if (!is_writable(F_ROOT_PHP . '/_framework/cookie')) {
    chmod(F_ROOT_PHP . '/_framework/cookie', 0777);
  }

  // turn off magic quotes
  if (in_array(strtolower(ini_get('magic_quotes_gpc')), ['1', 'on'])) {
    $_POST   = array_map('stripslashes', $_POST);
    $_GET    = array_map('stripslashes', $_GET);
    $_COOKIE = array_map('stripslashes', $_COOKIE);
  }
}
if (F_IS_BRIDGE) {
  require_once   F_ROOT_PHP . '/_framework/modules/core/bridge.php';

  if (!isLocalhost()) {
    if (file_exists(F_SYSTEM_PATH)) {
      require_once F_SYSTEM_PATH;
    } else {
      echo 'Please install system first or check system path.';
      exit();
    }
  }
}
