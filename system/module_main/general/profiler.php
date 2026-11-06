<?php
class Profiler {
  private static $conf = [
    'tb_log'  => 'sys_profiler',
    'lbl_log' => 'ประวัติการทำงาน'
  ];

  public static function setLogListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    foreach ($selected_records as $key => $row) {
      // Do Each Works...
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
  | Group | ประวัติการทำงาน
  | Description | 1.ดึงข้อมูลลิส 'ประวัติการทำงาน'
  | $where | [รองรับauto_search(ตาราง sys_profiler)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectLog($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? '_log' : static::$conf['tb_log'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setLogDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...

    return $record;
  }

  /**
  | Group | ประวัติการทำงาน
  | Description | 2.ดึงข้อมูล 'ประวัติการทำงาน' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getLogByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? '_log' : static::$conf['tb_log'];
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setLogDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ประวัติการทำงาน
  | Description | 3.เพิ่ม 'ประวัติการทำงาน'
   */
  public static function addNewLog($data) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert(static::$conf['tb_log'], $data);
  }

  /**
  | Group | ประวัติการทำงาน
  | Description | 4.อัพเดท 'ประวัติการทำงาน'
   */
  public static function updateLog($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_log'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_log']);
    }

    return Amst::updateByID(static::$conf['tb_log'], $data, $id);
  }

  /**
  | Group | ประวัติการทำงาน
  | Description | 5.ลบ 'ประวัติการทำงาน'
  | $id | ไอดีรายการ
   */
  public static function deleteLog($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteLog($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_log'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteLog($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_log'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_log']);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | บันทึกประวัติการทำงาน
  | Description | 6.เริ่ม 'บันทึกประวัติการทำงาน'
   */
  public static function startProfiling() {
    $rep = [
      'result' => SC::set('do_profiling', md5(Util::getSystemTimestamp().time().'sea_salt'))
    ];

    return Amst::repSuccess($rep);
  }

  /**
  | Group | บันทึกประวัติการทำงาน
  | Description | 7.หยุด 'บันทึกประวัติการทำงาน'
   */
  public static function stopProfiling() {
    $rep = [
      'result' => SC::remove('do_profiling')
    ];

    return Amst::repSuccess($rep);
  }

  /**
  | Group | บันทึกประวัติการทำงาน
  | Description | 8.ทำอยู่หรือไม่ 'บันทึกประวัติการทำงาน'
   */
  public static function isProfiling() {
    return SC::get('do_profiling');
  }

  public static function doProfiling($caller_info) {
    $rep = [
      'caller_info' => $caller_info,
      'caller_uid'  => false
    ];

    $n_data = [
      'user_id'            => User::getCurrentUserID(),
      'start_microseconds' => microtime(true),
      'group'              => static::isProfiling()
    ];
    if (isset($caller_info['class'])) {
      $n_data['class'] = $caller_info['class'];
    }
    if (isset($caller_info['function'])) {
      $n_data['fn'] = $caller_info['function'];
    }
    // if (isset($caller_info['function'])) {
    //   $n_data['fn'] = $caller_info['function'];
    // }
    $insert_id = Amst::insert(static::$conf['tb_log'], $n_data, ['direct' => true]);
    if ($insert_id) {
      $rep['caller_uid'] = $insert_id;
      Amst::query("set profiling_history_size=100");
      Amst::query("set profiling=1");
    }

    return $rep['caller_uid'];
  }

  public static function saveProfiling($caller_uid) {
    $rep = [
      'caller_uid' => $caller_uid
    ];

    if ($caller_uid) {
      $profiles = Amst::queryFetchAll("SHOW PROFILES;");
      $model = Amst::getByID(static::$conf['tb_log'], ['id', 'start_microseconds', 'user_id', 'group'], $caller_uid);
    }
    if (isset($model['id'])) {
      // SC::set('profiling_'.time(), $profiles);
      // $profile = Amst::queryFetchAll("SHOW PROFILE ALL FOR QUERY 2;");
      $r = json_encode($profiles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      if ($r == 0) {
        $u_data = [
          'total_microseconds' => microtime(true) - ($model['start_microseconds']),
          'profile_len'        => ($profiles) ? count($profiles) : -98,
          'result'             => $r
        ];
        $rep['update_result'] = Amst::updateByID(static::$conf['tb_log'], $u_data, $model['id'], ['direct' => true]);
        if ($u_data['profile_len'] > 0) {
          foreach ($profiles as $row) {
            $u_data = [
              'user_id'            => $model['user_id'],
              'start_microseconds' => $model['start_microseconds'],
              'group'              => $model['group'],
              'total_microseconds' => microtime(true) - ($model['start_microseconds']),
              'profile_len'        => ($row) ? count($row) : -98,
              'result'             => json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ];
            $rep['update_result'] = Amst::insert(static::$conf['tb_log'], $u_data, ['direct' => true]);
          }
        }
      } else {
        $u_data = [
          'total_microseconds' => microtime(true) - ($model['start_microseconds']),
          'profile_len'        => ($profiles) ? count($profiles) : -98,
          'result'             => $r
        ];
        $rep['update_result'] = Amst::updateByID(static::$conf['tb_log'], $u_data, $model['id'], ['direct' => true]);
      }
    }

    // echo json_encode($rep);
    // die();
    // foreach ($profiles as $p) {
    //   $rep['profiles'][$p['Query_ID']] = round($p['Duration'],4) * 1000 .' ms - '.$p['Query'];
    // } 
    // rep('success', true, $result);

    return Amst::repSuccess($rep);
  }

  // Call this at each point of interest, passing a descriptive string
  private function prof_flag($str) {
    // https://stackoverflow.com/questions/21133/simplest-way-to-profile-a-php-script
    global $prof_timing, $prof_names;
    $prof_timing[] = microtime(true);
    $prof_names[] = $str;
  }

  // Call this when you're done and want to see the results
  private function prof_print() {
    global $prof_timing, $prof_names;
    $size = count($prof_timing);
    for ($i = 0; $i < $size - 1; $i++) {
      echo "<b>{$prof_names[$i]}</b><br>";
      echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i + 1] - $prof_timing[$i]);
    }
    echo "<b>{$prof_names[$size - 1]}</b><br>";
  }
}
?>
