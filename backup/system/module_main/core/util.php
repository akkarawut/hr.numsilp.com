<?php
class Util {
  const NULL                = 'SYSTEM_NULL_VALUE';
  private static $lang_dict = [];

  function urlExists($url) {
    // [20211102] not working always return true
    if (!$fp = curl_init($url)) {
      return false;
    }

    return true;
  }

  public static function assignURL($url, $params = []) {
    $parts = parse_url($url);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $query);
    } else {
      $query = [];
    }

    foreach ($params as $key => $value) {
      $query[$key] = $value;
    }

    // build query string
    $query_string = '';
    $isFirst      = true;
    foreach ($query as $key => $value) {
      if ($isFirst) {
        $query_string .= '?'.$key.'='.$value;
        $isFirst = false;
      } else {
        $query_string .= '&'.$key.'='.$value;
      }
    }
    $parts['query'] = $query_string;

    $url = $parts['scheme'].'://'.$parts['host'].$parts['path'].$parts['query'];
    return $url;
  }

  public static function queryStringToInput($url, $ignore = [], $type = 'hidden') {
    $html  = '';
    $parts = parse_url($url);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $query);
    } else {
      $query = [];
    }
    // build form input
    foreach ($query as $key => $value) {
      if (!in_array($key, $ignore)) {
        $html .= '<input type="'.$type.'" name="'.$key.'" value="'.$value.'">';
      }
    }
    return $html;
  }

  public static function arrayToInput($data = [], $type = 'hidden') {
    $html = '';
    // build form input
    foreach ($data as $key => $value) {
      $html .= '<input type="'.$type.'" name="'.$key.'" value="'.$value.'">';
    }
    return $html;
  }

  public static function formatDate($datetime, $format = 'd/m/Y') {
    $result = '';
    if ($datetime === '0000-00-00 00:00:00') {
      $result = '';
    } else {
      $date   = new DateTime($datetime);
      $result = $date->format($format);
    }

    return $result;
  }

  static function formatMonthName($month_index, $full_name = true) {
    $months       = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $month_shorts = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // convert to number
    $month_index *= 1;

    $month_list = $full_name ? $months : $month_shorts;

    return isset($month_list[$month_index]) ? $month_list[$month_index] : "";
  }

  static function formatDayShortToFull($short_name) {
    $days = [
      'mon' => 'monday',
      'tue' => 'tuesday',
      'wed' => 'wednesday',
      'thu' => 'thursday',
      'fri' => 'friday',
      'sat' => 'saturday',
      'sun' => 'sunday'
    ];

    $short_name = strtolower($short_name);

    return isset($days[$short_name]) ? $days[$short_name] : '';
  }

  // array & object
  static function entityToString($entity) {
    $description = "";

    if (is_array($entity)) {
      foreach ($entity as $key => $value) {
        if (is_array($value) || is_object($value)) {
          $description .= '<b>'.$key.'</b> : ('.gettype($value).') <span class="text-muted">'.((!is_array($value)) ? $value : '[array_data]').'</span><br />';
          foreach ($value as $valuekey1 => $valuedata1) {
            $description .= '&nbsp;&nbsp;<b>'.$valuekey1.'</b> : ('.gettype($valuedata1).') <span class="text-muted">'.((!is_array($valuedata1)) ? $valuedata1 : '[array_data]').'</span><br />';

            if (is_array($valuedata1) || is_object($valuedata1)) {
              foreach ($valuedata1 as $valuekey2 => $valuedata2) {
                $description .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$valuekey2.'</b> : ('.gettype($valuedata2).') <span class="text-muted">'.((!is_array($valuedata2)) ? $valuedata2 : '[array_data]').'</span><br />';

                if (is_array($valuedata2) || is_object($valuedata2)) {
                  foreach ($valuedata2 as $valuekey3 => $valuedata3) {
                    $description .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$valuekey3.'</b> : ('.gettype($valuedata3).') <span class="text-muted">'.((!is_array($valuedata3)) ? $valuedata3 : '[array_data]').'</span><br />';

                    if (is_array($valuedata3) || is_object($valuedata3)) {
                      foreach ($valuedata3 as $valuekey4 => $valuedata4) {
                        $description .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$valuekey4.'</b> : ('.gettype($valuedata4).') <span class="text-muted">'.((!is_array($valuedata4)) ? $valuedata4 : '[array_data]').'</span><br />';
                      }
                    }
                  }
                }
              }
            }
          }
        } else {
          $description .= '<b>'.$key.'</b> : ('.gettype($value).') <span class="text-muted">'.$value.'</span><br />';
        }
      }
    }

    return $description;
  }

  /**
  | Group | Check
  | Description | เช็คว่าเป็น Json รึเปล่า
   */
  static function isJson($anonymous_string) {
    if (!is_string($anonymous_string)) {
      return false;
    }
    return true;
  }

  /**
  | Group | Check
  | Description | ดึง IP ของผู้ใช้งานปัจจุบัน
   */
  static function getClientIP() {
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

  /**
  | Group | Sort
  | Description | sort array by column
   */
  static function arraySortByColumn(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = [];
    foreach ($arr as $key => $row) {
      $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);

    return $arr;
  }

  /**
  | Group | Helper
  | Description | Check OPTIONS array has key or in array
   */
  public static function isHasOptions($key = '', $with_option = []) {
    if (is_array($with_option)) {
      return (isset($with_option[$key]) || in_array($key, $with_option, true));
    }
    return $with_option === $key;
  }

  /**
  | Group | Helper
  | Description | Return OPTIONS value (Must be Key=>Value)
   */
  public static function getOptionsValue($key, $with_option, $default = false) {
    if (self::isHasOptions($key, $with_option)) {
      return isset($with_option[$key]) ? $with_option[$key] : $default;
    }
    // onlyKeyArray

    return $default;
  }

  /**
  | Group | Helper
  | Description | Return Full Formated DATE || False
   */
  public static function checkDate($datetime, $format = "Y-m-d") {
    if ($datetime == 'now') {
      return static::getSystemDate();
    }

    $dt = DateTime::createFromFormat($format, $datetime);
    if ($dt !== false && !array_sum($dt::getLastErrors())) {
      return $dt->format('Y-m-d');
    }

    return false;
  }

  /**
  | Group | Helper
  | Description | Return Full Formated DATETIME || False
   */
  public static function checkDateTime($datetime, $format = "Y-m-d H:i:s") {
    if ($datetime == 'now') {
      return static::getSystemTimestamp();
    }

    $datetime = str_replace('T', ' ', $datetime);

    $return = false;
    $dt     = DateTime::createFromFormat("Y-m-d H:i:s", $datetime);
    if ($dt !== false && !array_sum($dt::getLastErrors())) {
      $return = $dt->format('Y-m-d H:i:s');
    }
    if (!$return) {
      $dt = DateTime::createFromFormat("Y-m-d H:i", $datetime);
      if ($dt !== false && !array_sum($dt::getLastErrors())) {
        $return = $dt->format('Y-m-d H:i:s');
      }
    }

    return $return;
  }

  public static function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  public static function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
      return true;
    }
    return (substr($haystack, -$length) === $needle);
  }

  public static function getSystemDate($time = null) {
    if (!$time) {
      $time = time();
    }

    if (!is_int($time)) {
      $time = strtotime($time);
    }

    return date('Y-m-d', $time);
  }

  public static function getSystemTime($time = null) {
    if (!$time) {
      $time = time();
    }

    if (!is_int($time)) {
      $time = strtotime($time);
    }

    return date('H:i:s', $time);
  }

  public static function getSystemTimestamp($time = null) {
    if (!$time) {
      $time = time();
    }

    if (!is_int($time)) {
      $time = strtotime($time);
    }

    return date('Y-m-d H:i:s', $time);
  }

  public static function getRandomString($len = 5, $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $randomstring = '';
    for ($i = 1; $i <= $len; $i++) {
      $randomstring .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomstring;
  }

  public static function byteToString($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision).''.$units[$pow];
  }

  public static function stringToByte($p_sFormatted) {
    $aUnits = ['B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8];
    $sUnit  = strtoupper(trim(substr($p_sFormatted, -2)));
    if (intval($sUnit) !== 0) {
      $sUnit = 'B';
    }
    if (!in_array($sUnit, array_keys($aUnits))) {
      return false;
    }
    $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
    if (!intval($iUnits) == $iUnits) {
      return false;
    }
    return $iUnits * pow(1024, $aUnits[$sUnit]);
  }

  public static function isAssocArray(array $arr) {
    if ([] === $arr) {
      return false;
    }

    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  /**
   * GET EXACT FRIST INFO DATATYPE without special chars
   * @author TULA
   * @param STRING $info_string SQL INFO
   * @return STRING DATA TYPE
   */
  public static function trimDataTypeName($info_string) {
    $i_data_type = strtoupper($info_string);
    if (strpos($i_data_type, '(')) {
      $i_data_type = substr($i_data_type, 0, strpos($info_string, '('));
    }
    if (strpos($i_data_type, '|')) {
      $i_data_type = substr($i_data_type, 0, strpos($info_string, '|'));
    }
    return $i_data_type;
  }

  /**
   * GET FULL DATA TYPE *for comparing with db
   * @author TULA
   * @param STRING $info_string SQL INFO
   * @return STRING DATA TYPE
   */
  public static function getFullDataType($info_string) {
    $i_data_type = strtoupper($info_string);
    if (strpos($i_data_type, '|')) {
      $i_data_type = substr($i_data_type, 0, strpos($info_string, '|'));
    }
    // DEFAULT
    if ($i_data_type == 'TINYINT') {
      $i_data_type = 'TINYINT(4)';
    }
    if ($i_data_type == 'INT') {
      $i_data_type = 'INT(11)';
    }
    if ($i_data_type == 'BIGINT') {
      $i_data_type = 'BIGINT(20)';
    }
    if ($i_data_type == 'VARCHAR') {
      $i_data_type = 'VARCHAR(255)';
    }
    if ($i_data_type == 'DECIMAL') {
      $i_data_type = 'DECIMAL(14,2)';
    }
    return $i_data_type;
  }

  public static function trimRuleName($info_string) {
    $i_data_type = strtolower($info_string);
    if (strpos($i_data_type, '(')) {
      $i_data_type = substr($i_data_type, 0, strpos($info_string, '('));
    }
    if (strpos($i_data_type, '|')) {
      $i_data_type = substr($i_data_type, 0, strpos($info_string, '|'));
    }
    return $i_data_type;
  }

  public static function makeArray($input) {
    if (!$input) {
      return [];
    }

    if (!is_array($input)) {
      return [$input];
    }
    return $input;
  }

  public static function getNumberString($input) {
    return preg_replace('/\D/', '', $input);
  }

  public static function recursive_unset_arraykey(&$array, $unwanted_key) {
    unset($array[$unwanted_key]);
    foreach ($array as &$value) {
      if (is_array($value)) {
        static::recursive_unset_arraykey($value, $unwanted_key);
      }
    }
  }

  public static function isEmptyString($key) {
    // Do not !$value
    return is_null($key) || (strlen(trim($key)) == 0);
  }

  public static function compareKey($key1, $key2) {
    return trim(strtolower($key1)) === trim(strtolower($key2));
  }

  public static function site_url() {
    $url = '/';

    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) {
      $protocol = 'https';
    }
    if (($protocol == 'http') && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
      $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    }

    $url = [
      // "raw" => "https://localhost.com/system/api/call.php",
      //"protocol" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"), //"https",
      "protocol" => $protocol,
      "host"     => explode('.', $_SERVER['HTTP_HOST']),
      "path"     => explode('/', $_SERVER['REQUEST_URI'])
    ];

    return "{$protocol}://{$_SERVER['HTTP_HOST']}/";
  }

  public static function getTimezoneOffset() {
    $return = '+07:00';
    $now    = new DateTime();
    $mins   = $now->getOffset() / 60; // from date_default_timezone_set
    $sgn    = ($mins < 0 ? -1 : 1);
    $mins   = abs($mins);
    $hrs    = floor($mins / 60);
    $mins -= $hrs * 60;
    $return = sprintf('%+d:%02d', $hrs * $sgn, $mins);
    return $return;
  }
}
