<?php
class W_Bridge
{
  protected static $ch;

  // CALL ALL STATIC METHOD
  public static function __callStatic($function, $params = [])
  {
    $class_dont_need_code = [
      'Info',                                      // info
      'Config',                                    // config
      'Amst', 'WLoves', 'DB', 'DBInit', 'DBQuery', // core
      'File', 'Excel', 'ImageManagement',          // file
      'Firebase', 'Email', 'SMS',                  // connection
      'Doc', 'Util', 'Test', 'Func', 'Log', 'SC'  // general
    ];

    // Extract code
    $code = '';
    if (!in_array(get_called_class(), $class_dont_need_code)) {
      if (isset($params[0])) {
        $code = $params[0];
      }
    }

    // Manage params if file exists
    foreach ($params as $key => $param) {
      if (isset($param['tmp_name']) && is_uploaded_file($param['tmp_name'])) {
        $params[$key] = self::buildCurlFile($param);
      }
    }

    return self::callAPI($code, get_called_class(), $function, $params);
  }

  public static $curl_data = [];

  public static function callAPI($code, $class, $function, $params = [])
  {
    $send_params                       = ['params' => []];
    $send_params['api_key']            = F_BRIDGE_API_KEY;
    $send_params['code']               = $code;
    $send_params['class']              = $class;
    $send_params['function']           = $function;
    $send_params['sender_user_agent']  = $_SERVER['HTTP_USER_AGENT'];
    $send_params['sender_ip_address']  = self::getClientIP();
    $send_params['sender_current_url'] = CURRENT_URL;
    $send_params['params']             = $params;
    $send_params                       = self::buildPostFields($send_params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, F_BRIDGE_API_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $send_params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, F_ROOT_PHP . "/_framework/cookie/cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, F_ROOT_PHP . "/_framework/cookie/cookies.txt");
    $raw_output = curl_exec($ch);

    curl_close($ch);

    $server_output = json_decode($raw_output, true);

    array_push(W_Main::$debug['data'], [
      'call'     => [
        'class'    => $class,
        'function' => $function,
        'params'   => $params
      ],
      'response' => $server_output
    ]);

    $curl_array = [
      'current_url' => CURRENT_URL,
      'code'        => $code,
      'class'       => $class,
      'function'    => $function,
      'params'      => $send_params
    ];

    array_push(self::$curl_data, $curl_array);

    return $server_output;
  }

  public static function getClientIP()
  {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
      $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
  }

  private static function buildPostFields($data, $existing_keys = '', &$returnArray = [])
  {
    if (($data instanceof CURLFile) || !(is_array($data) || is_object($data))) {
      $returnArray[$existing_keys] = $data;
      return $returnArray;
    } else {
      if (count($data) == 0 && $existing_keys != 'params') {
        $returnArray[$existing_keys] = '';
        return $returnArray;
      }
      foreach ($data as $key => $item) {
        self::buildPostFields($item, $existing_keys ? $existing_keys . '[' . $key . ']' : $key, $returnArray);
      }
      return $returnArray;
    }
  }

  private static function buildCurlFile($file_obj)
  {
    $tmp_name = $file_obj['tmp_name'];
    $type     = $file_obj['type'];
    // $name = basename($file_obj['name']); //เอาออกเพราะถ้าเป็นชื่อภาษาไทยชื่อหาย
    $name     = $file_obj['name'];

    $curl_file = curl_file_create($tmp_name, $type, $name);
    return $curl_file;
  }
}
