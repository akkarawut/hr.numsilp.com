<?php
class User_Log {
  private static $conf = [
    'tb_login_log'  => 'sys_user_login_log',
    'lbl_login_log' => 'ประวัติการเข้าใช้งานระบบ'
  ];

  private static function get_ip_address() {
    // Check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && static::validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    }

    // Check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // Check if multiple IP addresses exist in var
      $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      foreach ($iplist as $ip) {
        if (static::validate_ip($ip)) {
          return $ip;
        }
      }
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED']) && (static::validate_ip($_SERVER['HTTP_X_FORWARDED']))) {
      return $_SERVER['HTTP_X_FORWARDED'];
    }

    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && static::validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
      return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && static::validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_FORWARDED_FOR'];
    }

    if (!empty($_SERVER['HTTP_FORWARDED']) && static::validate_ip($_SERVER['HTTP_FORWARDED'])) {
      return $_SERVER['HTTP_FORWARDED'];
    }

    // Return unreliable IP address since all else failed
    return $_SERVER['REMOTE_ADDR'];
  }

  private static function validate_ip($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
      return false;
    }
    return true;
  }

  /**
  | Group | ประวัติการเข้าใช้งานระบบ
  | Description | 1.ดึงข้อมูลลิส 'ประวัติการเข้าใช้งานระบบ'
  <hr>
  <h5>$where = [ //กรองข้อมูลตาม ส่งอันไหนก็จะกรองเฉพาะอันนั้น</h5>
  <ul>
  <li>'search_text' => $search_text, // "กรองข้อมูลจาก ชื่อผู้จัดส่ง",</li>
  <li>'user_id' => $user_id, // ไอดีผู้ใช้</li>
  <li>];</li>
  </ul>
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'total_count' => true, // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)</li>
  <li>'page_no' => 0-N, // เลขหน้า *=0 คือส่งทั้งหมด</li>
  <li>'page_size' => 1-N, // จำนวนข้อมูลต่อหน้า</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>'sort' => ['column_name' => 'ASC/DESC'], // เรียงคอลัมภ์</li>
  <li>];</li>
  </ul>
   */
  public static function selectLoginLog($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['name[~]']        = "{$where['search_text']}";
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen($where['user_id'])) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    }

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_login_log' : static::$conf['tb_login_log'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setLoginLogDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | ประวัติการเข้าใช้งานระบบ
  | Description | 2.ดึงข้อมูล 'ประวัติการเข้าใช้งานระบบ' ด้วยไอดี
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>];</li>
  </ul>
   */
  public static function getLoginLogByID($id, $options = []) {
    $return = null;

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_login_log' : static::$conf['tb_login_log'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setLoginLogDetail($model, $options);
      }
    }
    return $return;
  }

  public static function getOnlineData($user_id, $options = []) {
    $return = [];

    if ($user_id) {
      $options = [ // วิธีการดึงข้อมูลเพิ่มเติม
        'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว
        'page_no' => 1, // เลขหน้า *=0 คือส่งทั้งหมด
        'page_size' => 1, // จำนวนข้อมูลต่อหน้า
        'selected_fields' => '*', // คอลัมภ์ที่ต้องการ
        'sort' => ['insert_date_time' => 'DESC'] // เรียงคอลัมภ์
      ];
      $selected_records = static::selectLoginLog(['user_id' => $user_id], $options);
      foreach ($selected_records as $row) {
        $from_time                      = strtotime($row['insert_date_time']);
        $return['online_minutes']       = round(abs(time() - $from_time) / 60, 0);
        $return['online_hours']         = round(abs(time() - $from_time) / (60 * 60), 2);
        $return['last_visit_date_time'] = $row['insert_date_time'];
      }
    }
    return $return;
  }

  /**
  | Group | ประวัติการเข้าใช้งานระบบ
  | Description | 3.เพิ่ม 'ประวัติการเข้าใช้งานระบบ'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'user_id' => 'user_id',</li>
  <li>'ip_address' => 'ip_address',</li>
  <li>'http_user_agent' => 'http_user_agent',</li>
  <li>'history_type' => 'history_type',</li>
  <li>'device_name' => 'device_name',</li>
  <li>'location' => 'location',</li>
  <li>'country' => 'country',</li>
  <li>'browser' => 'browser',</li>
  <li>'remark' => 'remark',</li>
  <li>);</li>
  </ul>
   */
  public static function addNewLoginLog($data) {
    $rep = [
      'insert_id' => false
    ];

    // [20220603] ไม่ต้องดักเพราะไม่รู้ว่าใครในตอนแรกๆ
    // if (!isset($data['user_id']) || !$data['user_id']) {
    //   return Amst::repError('E0001', 'User ID');
    // }

    $data['site_name']  = isset($data['site_name']) ? $data['site_name'] : API::getSiteName();
    $data['ip_address'] = self::get_ip_address();

    if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
      $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    } else {
      $data['http_user_agent'] = 'UNKNOWN';
    }
    return Amst::insert(static::$conf['tb_login_log'], $data);
  }

  /**
  | Group | ประวัติการเข้าใช้งานระบบ
  | Description | 4.อัพเดท 'ประวัติการเข้าใช้งานระบบ'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'user_id' => 'user_id',</li>
  <li>'ip_address' => 'ip_address',</li>
  <li>'http_user_agent' => 'http_user_agent',</li>
  <li>'history_type' => 'history_type',</li>
  <li>'device_name' => 'device_name',</li>
  <li>'location' => 'location',</li>
  <li>'country' => 'country',</li>
  <li>'browser' => 'browser',</li>
  <li>'remark' => 'remark',</li>
  <li>);</li>
  </ul>
   */
  private static function updateLoginLog($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_login_log'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_login_log']);
    }

    return Amst::updateByID(static::$conf['tb_login_log'], $data, $id);
  }

  /**
  | Group | ประวัติการเข้าใช้งานระบบ
  | Description | 5.ลบ 'ประวัติการเข้าใช้งานระบบ'
  | $id | ไอดีรายการ
   */
  private static function deleteLoginLog($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteLoginLog($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_login_log'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteLoginLog($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_login_log'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_login_log']);
    }

    return Amst::repSuccess($rep);
  }
}
?>