<?php
class User_Workplace {
  private static $conf = [
    'description'          => 'ใช้จัดการข้อมูลการทำงาน(ที่เดียว)',
    'tb_workplace'         => 'sys_user_workplace',
    'lbl_workplace'        => 'สถานที่ทำงาน',
    'lbl_single_workplace' => 'ข้อมูลการทำงาน(ที่เดียว)'
  ];

  private static function selectWorkingShift($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_verify' : static::$conf['tb_verify'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  private static function selectDepartment($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_verify' : static::$conf['tb_verify'];
    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  /**
  | Group | ตัวเลือกข้อมูลการทำงาน
  | Description | 1.ดึงข้อมูลลิส 'ข้อมูลผู้ใช้ยืนยันตัวตน'
  | $where | [รองรับauto_search(ตาราง _verify)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectLeader($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $sql_where['user_type_id'] = 1; // [TODO]
    $table_name = 'sys_user';
    $selected_fields = ['id', 'username', 'full_name'];
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  /**
  | Group | ตัวเลือกข้อมูลการทำงาน
  | Description | 1.ดึงข้อมูลลิส 'ข้อมูลผู้ใช้ยืนยันตัวตน'
  | $where | [รองรับauto_search(ตาราง _verify)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectSubordinate($where = [], $options = []) {
    $return = []; // list

    $sql_where = Amst::mergeWhere($where); // Auto Where
    $sql_where['user_type_id'] = 2; // [TODO]
    $table_name = 'sys_user';
    $selected_fields = ['id', 'username', 'full_name'];
    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setCurrentWorkplaceDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $user_ids = [];
    $record['user_list'] = [];

    $record['leader_id'] = $record['owner_id'];
    if (isset($record['owner_id']) && $record['owner_id']) {
      $user_ids[$record['owner_id']] = $record['owner_id'];
    }

    $record['subordinate_ids'] = [];
    $selected_records = Amst::selectNoKey('sys_user', ['id', 'owner_id'], ['owner_id' => $record['user_id']]);
    foreach ($selected_records as $row) {
      $record['subordinate_ids'][] = $row['id'];
      $user_ids[$row['id']] = $row['id'];
    }

    $record['user_ids'] = $user_ids;

    if ($user_ids) {
      $api_options['image']['user_profile_image'] = [
        'code'          => 'user_profile_image_',
        'target_column' => 'id'
      ];
      $record['user_list'] = Amst::select('sys_user', ['id', 'username', 'full_name'], ['id' => array_values($user_ids)], $api_options);
    }

    return $record;
  }

  /**
  | Group | ข้อมูลการทำงาน(ที่เดียว)
  | Description | 2.ดึงข้อมูล 'ข้อมูลการทำงาน(ที่เดียว)' ด้วยไอดีผู้ใช้
  | $user_id | ไอดีผู้ใช้
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getCurrentWorkPlace($user_id, $options = []) {
    $return = null;

    $options = Amst::checkOption($options);
    $table_name = 'sys_user_workplace'; // sys_view_user_workplace
    $selected_fields = Amst::getSelectedFields($options, ['id', 'owner_id', 'user_id', 'system_delete']);

    if ($user_id && is_numeric($user_id)) {
      $where = [
        'AND' => [
          'user_id'         => $user_id,
          'is_current_work' => 1
        ]
      ];
      $model = Amst::get($table_name, $selected_fields, $where, $options);
      if (isset($model['id'])) {
        $return = static::setCurrentWorkplaceDetail($model, $options);
      } else {
        // REGET
        $api = static::_addNewCurrentWorkPlace(['user_id' => $user_id]);
        if (isset($api['response_status']) && ($api['response_status'] == true)) {
          $model = Amst::get($table_name, $selected_fields, $where, $options);
          if (isset($model['id'])) {
            $return = static::setCurrentWorkplaceDetail($model, $options);
          }
        } else {
          Log::add(3, 'Fail to INSERT sys_user_workplace data api='.json_encode($api, JSON_UNESCAPED_UNICODE), 0, 'backend');
        }
      }
    }
    return $return;
  }

  private static function _addNewCurrentWorkPlace($data) {
    $rep = [
      'insert_id' => false
    ];

    $data = Amst::checkOption($data);

    if (isset($data['user_id'])) {
      $model = User::getUserByID($data['user_id'], ['is_exists' => true]);
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'User');
    }

    // [SINGLE]
    $data['is_current_work'] = 1;
    if (isset($data['is_current_work']) && ($data['is_current_work'] == 1)) {
      $rep['update_result'] = Amst::update(static::$conf['tb_workplace'], ['is_current_work' => 0], ['user_id' => $data['user_id'], 'is_current_work' => 1]);
    }

    return Amst::insert(static::$conf['tb_workplace'], $data);
  }

  /**
  | Group | ข้อมูลการทำงาน(ที่เดียว)
  | Description | 4.อัพเดท 'ข้อมูลการทำงาน(ที่เดียว)' ด้วยไอดีผู้ใช้
  | $user_id | ไอดีผู้ใช้
  | $data | [leader_id(leader_id), subordinate_ids(subordinate_ids), department_id(department_id), position(position), company_name(company_name), default_salary(เงินเดือน), working_shift_id(working_shift_id), hours_per_month(hours_per_month), minutes_per_month(minutes_per_month), working_date_from(working_date_from), working_date_to(working_date_to)]
   */
  public static function updateCurrentWorkPlace($user_id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = static::getCurrentWorkPlace($user_id, ['selected_fields' => ['id', 'user_id', 'owner_id']]);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_single_workplace']);
    }

    if (isset($data['leader_id'])) {
      if ($data['leader_id']) {
        $leader_model = User::getUserByID($data['leader_id'], ['is_exists' => true]);
        if (isset($leader_model['id'])) {
          $rep['update_leader_result'] = Amst::updateByID('sys_user', ['owner_id' => $data['leader_id']], $model['user_id'], ['direct' => true]);
        }
      } else if ($data['leader_id'] == 0) {
        $rep['update_leader_result'] = Amst::updateByID('sys_user', ['owner_id' => 0], $model['user_id'], ['direct' => true]);
      }
    }
    if (isset($data['subordinate_ids'])) {
      static::_removeSubordinate($model['user_id'], $data);
    }

    $api = Amst::updateByID(static::$conf['tb_workplace'], $data, $model['id']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['update_result'] = $api['response_data']['update_result'];
    }

    return Amst::repSuccess($rep);
  }

  public static function _removeSubordinate($leader_id, $data) {
    if ($data['subordinate_ids']) {
      $subordinate_ids = Util::makeArray($data['subordinate_ids']);

      // $old_subordinate_list = Amst::select('sys_user', ['id', 'total_price'], ['owner_id' => $leader_id]);
      $rep['update_subordinate_result'][] = Amst::update('sys_user', ['owner_id' => 0], ['owner_id' => $leader_id], ['direct' => true]);
      foreach ($subordinate_ids as $i_sub_id) {
        $sub_model = User::getUserByID($i_sub_id, ['is_exists' => true]);
        if (isset($sub_model['id'])) {
          $rep['update_subordinate_result'][] = Amst::updateByID('sys_user', ['owner_id' => $leader_id], $sub_model['id'], ['direct' => true]);
        }
      }
    } else {
      $rep['update_subordinate_result'][] = Amst::update('sys_user', ['owner_id' => 0], ['owner_id' => $leader_id], ['direct' => true]);
      Log::add(0, Amst::lastQuery(), 0, 'backend');
    }
  }

  /**
  | Group | ข้อมูลการทำงาน(ที่เดียว)
  | Description | 4.เอาผู้ใต้บังคับบัญชาออก
  | $user_id | ไอดีผู้ใช้
  | $data | [leader_id(leader_id), subordinate_ids(subordinate_ids), department_id(department_id), position(position), company_name(company_name), default_salary(เงินเดือน), working_shift_id(working_shift_id), hours_per_month(hours_per_month), minutes_per_month(minutes_per_month), working_date_from(working_date_from), working_date_to(working_date_to)]
   */
  public static function removeSubordinate($user_id, $sub_id) {
    $rep = [
      'update_result' => false
    ];

    if (isset($user_id)) {
      $leader_model = User::getUserByID($user_id, ['is_exists' => true]);
    }
    if (!isset($leader_model['id'])) {
      return Amst::repError('E0005', 'User');
    }

    if (isset($sub_id)) {
      $sub_model = User::getUserByID($sub_id, ['is_exists' => true]);
    }
    if (!isset($sub_model['id'])) {
      return Amst::repError('E0005', 'User');
    }

    $rep['update_result'] = Amst::updateByID('sys_user', ['owner_id' => 0], $sub_model['id'], ['direct' => true]);

    return Amst::repSuccess($rep);
  }

  /**
  | Group | สถานที่ทำงาน
  | Description | 1.ดึงข้อมูลลิส 'สถานที่ทำงาน'
  | $code | โค้ดโมดูล
  | $where | [search_text(กรองตามตำแหน่งและชื่อบริษัท), user_id(ไอดีผู้ใช้)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectWorkplace($where = [], $options = []) {
    $return = []; // list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['position[~]'] = "{$where['search_text']}";
      $sql_where['OR #search_text']['company_name[~]'] = "{$where['search_text']}";
    }
    if (isset($where['user_id']) && strlen($where['user_id'])) {
      $sql_where['AND']['user_id'] = $where['user_id'];
    } else {
      return Amst::selectEmptyList($options);
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_workplace' : static::$conf['tb_workplace'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function setWorkplaceDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    return $record;
  }

  /**
  | Group | สถานที่ทำงาน
  | Description | 2.ดึงข้อมูล 'สถานที่ทำงาน' ด้วยไอดี
  <hr>
  <h5>$options = [ // วิธีการดึงข้อมูลเพิ่มเติม</h5>
  <ul>
  <li>'not_view' => true, // เอาแค่ข้อมูลหลักไป edit *เร็ว</li>
  <li>'selected_fields' => '*', // คอลัมภ์ที่ต้องการ</li>
  <li>];</li>
  </ul>
   */
  public static function getWorkplaceByID($id, $options = []) {
    $return = null;

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_workplace' : static::$conf['tb_workplace'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    if ($id) {
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setWorkplaceDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | สถานที่ทำงาน
  | Description | 3.เพิ่ม 'สถานที่ทำงาน'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'user_id' => 'user_id',</li>
  <li>'position' => 'position',</li>
  <li>'company_name' => 'company_name',</li>
  <li>'working_date_from' => 'working_date_from',</li>
  <li>'working_date_to' => 'working_date_to',</li>
  <li>'is_current_work' => 'is_current_work',</li>
  <li>);</li>
  </ul>
   */
  public static function addNewWorkplace($data) {
    $rep = [
      'insert_id' => false
    ];

    if (!isset($data['user_id']) || !$data['user_id']) {
      return Amst::repError('E0001', 'User ID');
    }

    // [SINGLE]
    // if (isset($data['is_current_work']) && ($data['is_current_work'] == 1)) {
    //   $rep['update_result'] = Amst::update(static::$conf['tb_workplace'], ['is_current_work' => 0], ['user_id' => $data['user_id'], 'is_current_work' => 1]);
    // }

    return Amst::insert(static::$conf['tb_workplace'], $data);
  }

  /**
  | Group | สถานที่ทำงาน
  | Description | 4.อัพเดท 'สถานที่ทำงาน'
  <hr>
  <h5>$data = [ // กรอกข้อมูลเท่าที่ใช้</h5>
  <ul>
  <li>'position' => 'position',</li>
  <li>'company_name' => 'company_name',</li>
  <li>'working_date_from' => 'working_date_from',</li>
  <li>'working_date_to' => 'working_date_to',</li>
  <li>'is_current_work' => 'is_current_work',</li>
  <li>);</li>
  </ul>
   */
  public static function updateWorkplace($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_workplace'], ['id', 'user_id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_workplace']);
    }

    // [SINGLE]
    // if (isset($data['is_current_work']) && ($data['is_current_work'] == 1)) {
    //   $rep['update_result'] = Amst::update(static::$conf['tb_workplace'], ['is_current_work' => 0], ['user_id' => $model['user_id'], 'is_current_work' => 1]);
    // }

    return Amst::updateByID(static::$conf['tb_workplace'], $data, $id);
  }

  /**
  | Group | สถานที่ทำงาน
  | Description | 5.ลบ 'สถานที่ทำงาน'
  | $id | ไอดีรายการ
   */
  public static function deleteWorkplace($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteWorkplace($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_workplace'], $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteWorkplace($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_workplace'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_workplace']);
    }

    return Amst::repSuccess($rep);
  }
}
?>