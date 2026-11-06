<?php
class API {
  private static $_curl_data = [];
  private static $ch;
  private static $_site_name = '';

  private static function _get_client_ip() {
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

  private static function _build_post_fields($data, $existing_keys = '', &$returnArray = []) {
    if (($data instanceof CURLFile) || !(is_array($data) || is_object($data))) {
      $returnArray[$existing_keys] = $data;
      return $returnArray;
    } else {
      if (count($data) == 0 && $existing_keys != 'params') {
        $returnArray[$existing_keys] = '';
        return $returnArray;
      }
      foreach ($data as $key => $item) {
        self::_build_post_fields($item, $existing_keys ? $existing_keys.'['.$key.']' : $key, $returnArray);
      }
      return $returnArray;
    }
  }

  private static function _build_curl_file($file_obj) {
    $tmp_name = $file_obj['tmp_name'];
    $type = $file_obj['type'];
    $name = basename($file_obj['name']);

    $curl_file = curl_file_create($tmp_name, $type, $name);
    return $curl_file;
  }

  public static function insert($data = []) {
    $rep = [
      'insert_id' => false
    ];

    $found_available_key = false;
    for ($i = 1; $i <= 10; $i++) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $characters_length = strlen($characters);
      $random_string = '';
      for ($j = 0; $j <= 10; $j++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
      }
      $random_string = md5($random_string);
      $where = ['api_key' => $random_string];

      if (!Amst::has(Info::$system_table['api_key'], $where)) {
        $found_available_key = true;
        break;
      }
    }

    if (!$found_available_key) {
      return false;
    }

    $data = Amst::checkWhere($data);
    $data['api_key'] = $random_string;

    return Amst::insert(Info::$system_table['api_key'], $data);
  }

  public static function update($id, $description) {
    $data = [
      'description' => $description
    ];
    $where = ['id' => $id];
    return Amst::update(Info::$system_table['api_key'], $data, $where);
  }

  public static function delete($id) {
    $where = ['id' => $id];
    return Amst::delete(Info::$system_table['api_key'], $where);
  }

  public static function select() {
    return Amst::select(Info::$system_table['api_key']);
  }

  /**
  | Group | Operation
  | Description | 1.นับจำนวนข้อมูลลิส 'API Key'
  | $where | [search_text(กรองข้อมูลจากคำอธิบาย)]
   */
  public static function countApiKey($where = []) {
    $return = 0; // COUNT

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }
    return Amst::count(Info::$system_table['api_key'], $sql_where);
  }

  public static function setApiKeyListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // [GROUPED]
    $grouped_by = false;
    $category_list = [];
    if (Util::getOptionsValue('grouped_by', $options, false)) {
      $grouped_by = true;
      $selected_records2 = [
        [
          'id'   => 1,
          'name' => 'frontend'
        ],
        [
          'id'   => 2,
          'name' => 'backend'
        ],
        [
          'id'   => 3,
          'name' => 'iOS'
        ],
        [
          'id'   => 4,
          'name' => 'android'
        ]
      ];
      foreach ($selected_records2 as $row) {
        $category_list[$row['name']] = [
          'id'          => $row['id'],
          'name'        => $row['name'],
          'total_count' => 0,
          'list'        => []
        ];
      }
    }

    foreach ($selected_records as $key => $row) {
      if ($grouped_by && isset($row['type'])) {
        if (isset($category_list[$row['type']])) {
          $category_list[$row['type']]['total_count']++;
          $category_list[$row['type']]['list'][] = $selected_records[$key];
        }
      }
    }

    // remove Empty Group
    foreach ($category_list as $key => $row) {
      if ($category_list[$key]['total_count'] == 0) {
        unset($category_list[$key]);
      }
    }

    if (isset($return['list'])) {
      if ($grouped_by) {
        $return['list'] = array_values($category_list);
      } else {
        $return['list'] = $selected_records;
      }
    } else {
      if ($grouped_by) {
        $return = array_values($category_list);
      } else {
        $return = $selected_records;
      }
    }

    return $return;
  }

  /**
  | Group | Operation
  | Description | 1.ดึงข้อมูลลิส 'API Key'
  | $where | [search_text(กรองข้อมูลจากคำอธิบาย)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectApiKey($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }

    $table_name = Info::$system_table['api_key'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setApiKeyListDetail($return, $options);
  }

  public static function get($id) {
    $where = ['id' => $id];
    return Amst::get(Info::$system_table['api_key'], '*', $where);
  }

  /**
  | Group | Operation
  | Description | เช็คข้อมูล ว่ามีอยู่ในระบบมั้ย (อาจจะต้องทำต่อให้สามารถเชื่อมได้ว่า api_key นี้ ไปทำอะไรกับระบบบ้าง)
  | $api_key | คีย์ 32หลักในฐานข้อมูล
   */
  public static function verify($api_key) {
    $return = false;
    // In MySQL comparing zero to a string 0 = "any string" is always true! 
    // Of course this is usually not what you want to use for selecting your data as this might cause some expensive database operations.
    // No indexes can be used and a full table scan will always be triggered.
    if ($api_key) {
      $api_key = trim("{$api_key}");
    }
    if ($api_key) {
      $where = ['api_key' => $api_key];
      $return = Amst::has(Info::$system_table['api_key'], $where);
    }
    return $return;
  }

  public static function setSiteName($api_key) {
    // Set Curent CALL site_name by API_KEY
    if ($api_key) {
      $api_key = trim("{$api_key}");
    }
    if ($api_key) {
      $where = ['api_key' => $api_key];
      $model = Amst::get(Info::$system_table['api_key'], ['id', 'site_name'], $where);
      if (isset($model['id'])) {
        static::$_site_name = $model['site_name'];
      }
    }
    return true;
  }

  /**
  | Group | Site
  | Description | 1.getSiteName
   */
  public static function getSiteName() {
    $return = '';
    // เอา F_CONFIG ก่อน
    // 'F_BRIDGE_API_KEY' => defined('F_BRIDGE_API_KEY') ? F_BRIDGE_API_KEY : 'not-defined',
    // 'F_SITE_NAME'      => defined('F_SITE_NAME') ? F_SITE_NAME : 'not-defined'
    if (defined('F_SITE_NAME')) {
      return F_SITE_NAME;
    }
    return static::$_site_name;
  }

  /**
  | Group | Call_Api
  | Description | 1.ยิงหาระบบแม่
   */
  public static function call($url_to_call, $api_key, $code, $class, $function, $params = [], $options = []) {
    // initial data
    $class_dont_need_code = [];
    foreach (Info::$system_file as $class_name => $class_data) {
      array_push($class_dont_need_code, $class_name);
    }

    // Extract code
    $code = '';
    if (!in_array($class, $class_dont_need_code)) {
      if (isset($params[0])) {
        $code = $params[0];
      }
    }

    // manage params if file exists
    foreach ($params as $key => $param) {
      if (isset($param['tmp_name']) && is_uploaded_file($param['tmp_name'])) {
        $params[$key] = self::_build_curl_file($param);
      }
    }

    $send_params = ['params' => []];
    $send_params['api_key'] = $api_key;
    $send_params['code'] = $code;
    $send_params['class'] = $class;
    $send_params['function'] = $function;
    $send_params['sender_user_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'NO_HTTP_USER_AGENT';
    $send_params['sender_ip_address'] = self::_get_client_ip();
    $send_params['sender_current_url'] = $url_to_call;
    $send_params['params'] = $params;
    $send_params = self::_build_post_fields($send_params);

    if (!self::$ch) {
      self::$ch = curl_init();
    }

    curl_setopt(self::$ch, CURLOPT_URL, $url_to_call);
    curl_setopt(self::$ch, CURLOPT_POST, true);
    curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt(self::$ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $send_params);
    curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(self::$ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt(self::$ch, CURLOPT_AUTOREFERER, true);
    if (isset($options['time_out'])) {
      curl_setopt(self::$ch, CURLOPT_TIMEOUT, $options['time_out']);
      curl_setopt(self::$ch, CURLOPT_CONNECTTIMEOUT, $options['time_out']);
    }

    $raw_output = curl_exec(self::$ch);

    $server_output = json_decode($raw_output, true);

    $curl_array = [
      'current_url' => CURRENT_URL,
      'code'        => $code,
      'class'       => $class,
      'function'    => $function,
      'params'      => $send_params
    ];

    array_push(self::$_curl_data, $curl_array);

    return $raw_output;
  }

  /**
  | Group | Call_Api
  | Description | 2.ใช้เรียก API ด้วย PHP *ค่อยเทียบอีกทีว่าจะใช้อันไหน
   */
  public static function callExternalApi($method, $url, $data = []) {
    $curl = curl_init();

    switch ($method) {
    case 'POST':
      curl_setopt($curl, CURLOPT_POST, true);
      if ($data) {
        //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
      }
      break;
    case 'PUT':
      curl_setopt($curl, CURLOPT_PUT, 1);
      break;
    default:
      if ($data) {
        $url = sprintf('%s?%s', $url, http_build_query($data));
      }
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, 'username:password');

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
    // [20200820] PREVENT SSL ERROR :  'curl_error': 'SSL: no alternative certificate subject name matches target host name 'www.localhost.com''
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($curl);
    if (curl_errno($curl)) {
      $result = json_encode([
        'url'        => $url,
        'curl_error' => curl_error($curl)
      ]);
    }
    curl_close($curl);

    if (!$result) {
      $result = json_encode(['url' => $url]);
    }

    return $result;
  }
}
