<?php
include '../required/import.php';
include 'rep.php';

// post -> request
$jsondataarr = json_decode(file_get_contents('php://input'), true);
if ($jsondataarr) {
  foreach ($jsondataarr as $key => $value) {
    if (isset($_REQUEST)) {
      $_REQUEST[$key] = $value;
    }
  }
}

// input_json -> request
// [LATER] check is using ?
if (isset($_REQUEST['input_json'])) {
  if (Util::isJson($_REQUEST['input_json'])) {
    $jsondataarr = json_decode($_REQUEST['input_json'], true);
    if ($jsondataarr) {
      foreach ($jsondataarr as $key => $value) {
        if (isset($_REQUEST)) {
          $_REQUEST[$key] = $value;
        }
      }
    }
    unset($_REQUEST['input_json']);
  }
}

// request -> log temp
// [LATER] check is using ?
foreach ($_REQUEST as $key => $value) {
  if (strpos($key, 'sender_') !== false) {
    // Log::addTEMP($key, $value);
    unset($_REQUEST[$key]);
  }
}

header('amst_version: '.Info::version_main.'.'.Info::version_sub.'.'.Info::version_hotfix);

// allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400'); // cache for 1 day
  header('Content-Type: application/json');
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  }
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  }
  exit(0);
}

//// REQUIRED PARAMETER - 1.CLASS
if (!isset($_REQUEST['class'])) {
  return rep404('API not found.');
}
$calling_class = trim($_REQUEST['class']); // [LATER] lowercase ?
if (!$calling_class) {
  return rep400('input class');
}

//// OPTIONAL PARAMETER - 3.MOBILE
// [REQUEST][request_from_mobile] => Android
$is_call_from_mobile = isset($_REQUEST['request_from_mobile']);

$allow_class = [];
foreach (Info::$system_file as $class_name => $class_data) {
  $allow_class[$class_name] = $class_name;
}

$calls = [
  'api_key',
  'code',
  'class',
  'function'
];
foreach ($calls as $call) {
  if (isset($allow_class[$calling_class])) {
    if ('code' == $call) {
      continue;
    }
  }

  if (isset($_REQUEST[$call])) {
    if ('' === $_REQUEST[$call]) {
      return rep400('Invalid Call missing param ['.$call.'] no value ['.$_REQUEST[$call].'] Class ['.$calling_class.'] need this param');
    } else if (is_null($_REQUEST[$call])) {
      return rep400('Invalid Call missing param ['.$call.'] is null ['.$_REQUEST[$call].'] Class ['.$calling_class.'] need this param');
    }
  }
}

if (!isset($_REQUEST['api_key'])) {
  return rep400('Invalid API KEY');
} else if (!API::verify($_REQUEST['api_key'])) {
  return rep400('Invalid API KEY cannot verify');
}
//// SET THIS CALL SITE_NAME by API_KEY
API::setSiteName($_REQUEST['api_key']);

$directory_path = '';

// CHECK CLASS
//// [RECHECK] NOT SYS_CLASS ? So Required CODE ?
if (!isset($allow_class[$calling_class]) && isset($_REQUEST['code'])) {
  $function = Func::getByCode(['id', 'module'], $_REQUEST['code']);
  if (!$function) {
    return rep404('Function Not Found');
  }
  if (!isset(Config_Module::$module_file[$function['module']])) {
    return rep404('Info.Path Not Found');
  }

  $directory_path = Config_Module::$module_file[$function['module']];

  // IMPORT FUNCTION FROM CLASS FOLDER
  if (file_exists('../'.$directory_path.'/class/')) {
    foreach (glob('../'.$directory_path.'/class/*.php') as $filename) {
      include $filename;
    }
  }
}

//// REQUIRED PARAMETER - 1.FUNCTION
$calling_fn = '';
if (isset($_REQUEST['function'])) {
  $calling_fn = trim($_REQUEST['function']); // [LATER] tolowercase
  if (!method_exists($calling_class, $calling_fn)) {
    return rep404('Function Not Found');
  }
} else {
  return rep400('Invalid Function');
}

$params = [];
$max_param_key = false;
if (isset($_REQUEST['params'])) {
  if (Util::isJson($_REQUEST['params'])) {
    $param_json = json_decode($_REQUEST['params'], true);
    if (is_array($param_json)) {
      $i = 0; // [LATER] json must be param[0]
      foreach ($param_json as $param_key => $i_param) {
        $i_key = $i++;
        $max_param_key = (($i_key > $max_param_key) ? $i_key : $max_param_key);
        $params[$i_key] = $i_param;
      }
    } else {
      $params = [];
    }
  } else {
    foreach ($_REQUEST['params'] as $param_key => $i_param) {
      if (is_int($param_key)) {
        $i_key = intval($param_key);
        $max_param_key = (($i_key > $max_param_key) ? $i_key : $max_param_key);
        $params[$i_key] = $i_param;
      }
    }
  }
}

if (isset($_FILES['params'])) {
  // (
  //     [name] => ExfpcYUXAAEJIQ8.png
  //     [type] => image/png
  //     [tmp_name] => /tmp/phpLNqnjQ
  //     [error] => 0
  //     [size] => 241664
  // )
  // UPLOAD_ERR_OK
  // Value: 0; There is no error, the file uploaded with success.
  // UPLOAD_ERR_INI_SIZE
  // Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
  // UPLOAD_ERR_FORM_SIZE
  // Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
  // UPLOAD_ERR_PARTIAL
  // Value: 3; The uploaded file was only partially uploaded.
  // UPLOAD_ERR_NO_FILE
  // Value: 4; No file was uploaded.
  // UPLOAD_ERR_NO_TMP_DIR
  // Value: 6; Missing a temporary folder.
  // UPLOAD_ERR_CANT_WRITE
  // Value: 7; Failed to write file to disk.
  // UPLOAD_ERR_EXTENSION
  // Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.
  foreach ($_FILES['params'] as $file_topic => $file_obj) {
    foreach ($file_obj as $file_key => $file_value) {
      if (is_int($file_key)) {
        $i_key = intval($file_key);
        $max_param_key = (($i_key > $max_param_key) ? $i_key : $max_param_key);
        $params[$i_key] = [
          'name'     => $_FILES['params']['name'][$file_key],
          'type'     => $_FILES['params']['type'][$file_key],
          'tmp_name' => $_FILES['params']['tmp_name'][$file_key],
          'error'    => $_FILES['params']['error'][$file_key],
          'size'     => $_FILES['params']['size'][$file_key]
        ];
      }
    }
  }
}

// SORT&FILL missing PARAMS
$sorted_params = [];
if ($max_param_key !== false) {
  for ($i = 0; $i <= $max_param_key; $i++) {
    if (isset($params[$i])) {
      $sorted_params[$i] = $params[$i];
    } else {
      $sorted_params[$i] = null; // LATER BUG = null is not fn.default
    }
  }
  $params = $sorted_params;
  $sorted_params = []; // unset
}

// Default header("HTTP/1.1 200 OK");
try {
  $result = forward_static_call_array([$calling_class, $calling_fn], $params);

  if ($is_call_from_mobile) {
    if (!isset($result['response_status'])) {
      echo json_encode(Amst::repSuccess($result));
    } else {
      echo json_encode($result);
    }
  } else {
    echo json_encode($result);
  }
} catch (Throwable $e) {
  $error_num = 169;
  Log::add($error_num, $e->getMessage(), 0);
  rep500('error code ['.$error_num.'] : '.$e->getMessage());
} catch (Exception $e) {
  $error_num = 168;
  Log::add($error_num, $e->getMessage(), 0);
  rep500('error code ['.$error_num.'] : '.$e->getMessage());
}
