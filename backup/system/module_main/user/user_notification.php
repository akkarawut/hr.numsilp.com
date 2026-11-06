<?php
class User_Notification {
  private static $conf = [
    'tb_notification'  => 'sys_user_notification',
    'lbl_notification' => 'การแจ้งเตือน'
  ];

  public static function setNotificationListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Colected id
    $user_ids = [];
    foreach ($selected_records as $key => $record) {
      $selected_records[$key]['from_user_full_name']   = 'SYSTEM';
      $selected_records[$key]['friend_request_status'] = 'normal';

      if ($record['from_user_id']) {
        $user_ids[$record['from_user_id']] = $record['from_user_id'];
      }
    }

    if ($user_ids) {
      $where = [
        'AND' => [
          'id' => array_values($user_ids)
        ]
      ];
      $internal_records = Amst::selectNoKey('sys_user', ['id', 'full_name'], $where);
      foreach ($internal_records as $row) {
        $from_user_list[$row['id']] = $row;
      }
    }
    foreach ($selected_records as $key => $record) {
      if (isset($from_user_list[$record['from_user_id']])) {
        $selected_records[$key]['from_user_full_name'] = $from_user_list[$record['from_user_id']]['full_name'];
      }
    }

    // img_path
    if (true) {
      $img_ids  = [];
      $def_path = File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
      foreach ($selected_records as $key => $record) {
        if (isset($record['ref_info_json'])) {
          $ref_info_json = json_decode($record['ref_info_json'], true);
          if (isset($ref_info_json['img_code'])) {
            $img_ids[$ref_info_json['img_code']] = $ref_info_json['img_code'];
          }
        }
        $selected_records[$key]['img_path'] = $def_path;
      }
      if ($img_ids) {
        $img_list = File::selectPath(array_values($img_ids));
        foreach ($selected_records as $key => $record) {
          if (isset($record['ref_info_json'])) {
            $ref_info_json = json_decode($record['ref_info_json'], true);
            if (isset($ref_info_json['img_code'])) {
              $img_id = $ref_info_json['img_code'];
              if (isset($img_list[$img_id])) {
                $selected_records[$key]['img_path'] = $img_list[$img_id];
              }
            }
          }
        }
        unset($img_list);
        unset($img_ids);
      }
    }

    // mark_as_read
    if (Util::isHasOptions('mark_as_read', $options)) {
      $noti_ids = [];
      foreach ($selected_records as $record) {
        $noti_ids[$record['id']] = $record['id'];
      }
      if ($noti_ids) {
        Amst::update('sys_user_notification', ['is_read' => true], ['id' => array_values($noti_ids)], ['direct' => true]);
      }
    }

    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | การแจ้งเตือน
  | Description | 1.ดึงข้อมูลลิส 'การแจ้งเตือน'
  | $code | โค้ดโมดูล
  | $where | [search_text(กรองข้อมูลจาก topic), user_id(ของใคร**บังคับส่ง), type(ตามประเภท), is_read(เอาที่อ่านแล้ว หรือยังไม่ได้อ่าน หรือทั้งหมด 'all'), is_show(เอาที่แสดง หรือ ซ่อน หรือทั้งหมด 'all')]
  | $options | [img_path(พร้อมรูปคนส่ง), mark_as_read(จะทำให้ลิสนี้อ่านแล้ว), not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectNotification($code, $where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['topic[~]']       = "{$where['search_text']}";
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen($where['user_id'])) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    } else {
      // **บังคับส่ง
      return Amst::selectEmptyList($options);
    }
    if (isset($where['type']) && strlen($where['type']) && ($where['type'] != 'all')) {
      $sql_where['AND']['type'] = $where['type'];
    }
    if (isset($where['is_read']) && strlen($where['is_read']) && ($where['is_read'] != 'all')) {
      $sql_where['AND']['is_read'] = $where['is_read'];
    }
    if (isset($where['is_show']) && strlen($where['is_show']) && ($where['is_show'] != 'all')) {
      $sql_where['AND']['is_show'] = $where['is_show'];
    }

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_notification' : static::$conf['tb_notification'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');
    if (Util::isHasOptions('img_path', $options)) {
      $options['image']['img_path'] = [
        'code'          => 'user_profile_image_',
        'target_column' => 'from_user_id'
      ];
    }

    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setNotificationListDetail($code, $return, $options);
  }

  public static function setNotificationDetail($code, $record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | การแจ้งเตือน
  | Description | 2.ดึงข้อมูล 'การแจ้งเตือน' ด้วยไอดี
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'img_path' => true, // พร้อมรูปคนส่ง</li>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>];</li>
  </ul>
   */
  public static function getNotificationByID($code, $id, $options = []) {
    $return = null;

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_notification' : static::$conf['tb_notification'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      if (Util::isHasOptions('img_path', $options)) {
        $options['image']['img_path'] = [
          'code'          => 'user_profile_image_',
          'target_column' => 'from_user_id'
        ];
      }
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setNotificationDetail($code, $model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | การแจ้งเตือน
  | Description | 3.เพิ่ม 'การแจ้งเตือน'
  | $code | โมดูลโค้ด ไม่ใส่ก็ได้
  | $data | [user_id(ส่งไปหาUserนี้), from_user_id(จากUser), topic(หัวข้อ), description(รายละเอียด), firebase(ส่งไฟเบสไหม))]
   */
  public static function addNewNotification($code, $data) {
    $rep = [
      'insert_id' => false
    ];
    $debug = [
      'data' => $data
    ];

    $api = Amst::insert(static::$conf['tb_notification'], $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['insert_id']   = $api['response_data']['insert_id'];
      $debug['insert_id'] = $api['response_data']['insert_id'];
      if (isset($data['firebase']) && $data['firebase']) {
        // [FIREBASE] - ALL THIS USER Mobile
        $debug['notifyByFirebase'] = User_Firebase::notifyByUserID($data['user_id'], $data['topic'], $data['description'], $data);
      }
    } else {
      Log::logBackendShouldKnow('addNewNotification FAIL', $debug);
      return $api;
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | การแจ้งเตือน
  | Description | 4.อัพเดท 'การแจ้งเตือน'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'user_id' => 'user_id',</li>
  <li>'topic' => 'topic',</li>
  <li>'description' => 'description',</li>
  <li>'code' => 'code',</li>
  <li>'module' => 'module',</li>
  <li>'type' => 'type',</li>
  <li>'ref_id' => 'ref_id',</li>
  <li>'link' => 'link',</li>
  <li>'is_read' => 'is_read',</li>
  <li>'is_show' => 'is_show',</li>
  <li>);</li>
  </ul>
   */
  public static function updateNotification($code, $id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_notification'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_notification']);
    }

    return Amst::updateByID(static::$conf['tb_notification'], $data, $id);
  }

  /**
  | Group | การแจ้งเตือน
  | Description | 5.ลบ 'การแจ้งเตือน'
  | $id | ไอดีรายการ
   */
  public static function deleteNotification($code, $id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteNotification($code, $id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_notification'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteNotification($code, $id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_notification'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_notification']);
    }

    return Amst::repSuccess($rep);
  }
}
?>