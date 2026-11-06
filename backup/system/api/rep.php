<?php

/**
 * UNUSED FUNCTION - [TODO]
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @param BOOL $status True on Success
 * @param ARRAY $data Response Data
 * @return JSON Echo then die
 */
function outputLogging($data) {
  if (is_string($data)) {
    if (strlen($data) > 500) {
      $string = strip_tags($data);
      $stringCut = substr($string, 0, 500).'...';
      return '('.gettype($data).') '.$stringCut;
    } else {
      return '('.gettype($data).') '.$data;
    }
  }
  return '('.gettype($data).') '.$data;
}

/**
 * UNUSED FUNCTION - [TODO]
 */
function LogginAPICall($message) {
  $arr_logs = array_merge($_REQUEST, $_FILES);

  $result = Log::add(0, $message);
}

/**
 * General API Response.
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @param BOOL $status True on Success
 * @param ARRAY $data Response Data
 * @return JSON Echo then die
 */
function rep($message, $status = false, $data = []) {
  $output = [
    'response_status'  => $status,
    'response_message' => "{$message}"
  ];

  // Check if Already Response.
  if (isset($data['response_status'])) {
    $output['response_status'] = $data['response_status'];
    if (isset($data['response_data'])) {
      $data = $data['response_data'];
    }
  }

  $output['response_detail'] = [
    // 'message'          => "{$message}",
    // 'md5'              => md5(json_encode($data)),
    'date_time'        => date('Y-m-d H:i:s'),
    'execute_time_sec' => number_format(microtime(true) - $GLOBALS['amst_start_script_time'], 4) + 0
    // 'request'          => $_REQUEST,
    // 'trace'            => debug_backtrace()
    // 'included_files' => get_included_files(),
  ];
  $output['response_type'] = gettype($data);
  // $output['response_rows'] = is_array($data) ? count($data) : 1;
  $output['response_data'] = $data;

  echo json_encode($output);
  die();
}

/**
 * API Response in 400 Bad Request
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @return JSON Echo then die
 */
function rep400($message) {
  $output = [
    'response_status'  => false,
    'response_message' => "{$message}"
  ];

  $output['response_detail'] = [
    'date_time'        => date('Y-m-d H:i:s'),
    'execute_time_sec' => number_format(microtime(true) - $GLOBALS['amst_start_script_time'], 4) + 0
  ];
  $output['response_data'] = [];

  // HTTP
  header("HTTP/1.1 400 Bad Request");
  echo json_encode($output);
  die();
}

/**
 * API Response in 401 Unauthorized
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @return JSON Echo then die
 */
function rep401($message) {
  $output = [
    'response_status'  => false,
    'response_message' => "{$message}"
  ];

  $output['response_detail'] = [
    'date_time'        => date('Y-m-d H:i:s'),
    'execute_time_sec' => number_format(microtime(true) - $GLOBALS['amst_start_script_time'], 4) + 0
  ];
  $output['response_data'] = [];

  // HTTP
  header("HTTP/1.1 401 Unauthorized");
  echo json_encode($output);
  die();
}


/**
 * API Response in 404 Not Found
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @return JSON Echo then die
 */
function rep404($message) {
  $output = [
    'response_status'  => false,
    'response_message' => "{$message}"
  ];

  $output['response_detail'] = [
    'date_time'        => date('Y-m-d H:i:s'),
    'execute_time_sec' => number_format(microtime(true) - $GLOBALS['amst_start_script_time'], 4) + 0
  ];
  $output['response_data'] = [];

  // HTTP
  header("HTTP/1.1 404 Not Found");
  echo json_encode($output);
  die();
}

/**
 * API Response in 500 Internal Server Error
 *
 * @author TULA
 * @param STRING $message Showing Message
 * @return JSON Echo then die
 */
function rep500($message) {
  $output = [
    'response_status'  => false,
    'response_message' => "{$message}"
  ];

  $output['response_detail'] = [
    'date_time'        => date('Y-m-d H:i:s'),
    'execute_time_sec' => number_format(microtime(true) - $GLOBALS['amst_start_script_time'], 4) + 0
  ];
  $output['response_data'] = [];

  // HTTP
  header("HTTP/1.1 500 Internal Server Error");
  echo json_encode($output);
  die();
}
