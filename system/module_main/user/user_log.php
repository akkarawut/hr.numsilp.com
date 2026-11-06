<?php

class User_Log {

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
  | Group | USER LOG LOGIN
  | Description | 'USER LOG LOGIN'
  | $code | โค้ดโมดูล
   */
  public static function addLoginLog($data = []) {
    $data['site_name']  = isset($data['site_name']) ? $data['site_name'] : API::getSiteName();
    $data['ip_address'] = self::get_ip_address();
    if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
      $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    } else {
      $data['http_user_agent'] = 'UNKNOWN';
    }
    return static::addNewLog($data);
  }

  public static function setLogListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    foreach ($selected_records as $key => $record) {
    }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | USER LOG
  | Description | 1.ดึงข้อมูลลิส 'USER LOG'
  | $code | โค้ดโมดูล
  | $where | [user_id, input_username, input_password, ip_address, site_name, http_user_agent, type, sub_type, json]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectLog($where = [], $options = []) {
    $return    = []; // list
    $sql_where = []; // list

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_user_log', $selected_fields, $sql_where, $options);
    return static::setLogListDetail($return, $options);
  }

  public static function setLogDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | USER LOG
  | Description | 2.ดึงข้อมูล 'USER LOG' ด้วยไอดี
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getLogByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_user_log', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setLogDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | USER LOG
  | Description | 3.เพิ่ม 'USER LOG'
  | $code | โค้ดโมดูล
  | $data | [user_id, input_username, input_password, ip_address, site_name, http_user_agent, type, sub_type, json]
   */
  public static function addNewLog($data = []) {
    $rep = [
      'insert_id' => false
    ];

    $api = Amst::insert('sys_user_log', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | USER LOG
  | Description | 4.อัพเดท 'USER LOG'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
  | $data | [user_id, input_username, input_password, ip_address, site_name, http_user_agent, type, sub_type, json]
   */
  public static function updateLog($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_user_log', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'sys_user_log');
    }

    $api = Amst::updateByID('sys_user_log', $data, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | USER LOG
  | Description | 5.ลบ 'USER LOG'
  | $code | โค้ดโมดูล
  | $id | ไอดีรายการ
   */
  public static function deleteLog($id) {
    $rep = [
      'delete_result' => false
    ];

    $record = Amst::getByID('sys_user_log', '*', $id);
    if (isset($record['id'])) {
      $rep['delete_result'] = Amst::deleteByID('sys_user_log', $id);
      return Amst::repSuccess($rep);
    }
    return Amst::repError('E0000', 'Delete Log Fail');
  }
}
?>