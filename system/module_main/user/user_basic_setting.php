<?php
class User_Basic_Setting {
  /**
  | Group | เมนู
  | Description | 1.ดึงข้อมูล เมนู User Basic Setting
   */
  public static function selectMenu($where = [], $options = []) {
    $return = []; // list

    // $selected_records = User_type::selectUserType();
    $selected_records = [];
    foreach ($selected_records as $row) {
      $setting_list = [];
      if (isset($row['is_has_team']) && $row['is_has_team'] == 1) {
        $setting_list['has_team'] = Amst::count('sys_setting_user_team', ['user_type_id' => $row['id']]);
      }

      // IF HAS ANY
      if ($setting_list) {
        $return[] = [
          'user_type_id' => $row['id'],
          'name'         => $row['name'],
          'setting_list' => $setting_list
        ];
      }
    }
    unset($selected_records);

    return $return;
  }

  public static function setPermissionTemplateListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    foreach ($selected_records as $key => $row) {
      // Do Each Works...
      // $selected_records[$key]['som_feild'] = 'some_val';
    }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  public static function setPermissionTemplateDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    if (isset($record['update_user_id'])) {
      $record['update_user_username'] = '';
      $model                          = Amst::getByID('sys_user', ['id', 'username'], $record['update_user_id']);
      if (isset($model['id'])) {
        $record['update_user_username'] = $model['username'];
      }
    }

    return $record;
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 1.ดึงข้อมูลลิส 'ตั้งค่าทีม'
  | $where | [user_type_id(ไอดีประเภทผู้ใช้), name(ชื่อ)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectTeam($where = [], $options = []) {
    $return = []; // list

    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey('sys_setting_user_team', $selected_fields, $where, $options);
  }

  public static function setTeamDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    if (isset($record['update_user_id'])) {
      $record['update_user_username'] = '';
      $model                          = Amst::getByID('sys_user', ['id', 'username'], $record['update_user_id']);
      if (isset($model['id'])) {
        $record['update_user_username'] = $model['username'];
      }
    }

    return $record;
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 2.ดึงข้อมูล 'ตั้งค่าทีม' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getTeamByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    if ($id) {
      $model = Amst::getByID('sys_setting_user_team', $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setTeamDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 3.เพิ่ม 'ตั้งค่าทีม'
  | $data | [user_type_id(ไอดีประเภทผู้ใช้*บังคับส่ง), name(ชื่อทีม), description(คำอธิบาย)]
   */
  public static function addNewTeam($data) {
    $rep = [
      'insert_id' => false
    ];

    if (!isset($data['user_type_id'])) {
      return Amst::repError('E0001', 'User type ID');
    }

    return Amst::insert('sys_setting_user_team', $data);
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 4.อัพเดท 'ตั้งค่าทีม'
  | $id | ไอดีรายการ
  | $data | [name(ชื่อทีม), description(คำอธิบาย)]
   */
  public static function updateTeam($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_setting_user_team', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tb_setting_team');
    }

    return Amst::updateByID('sys_setting_user_team', $data, $id);
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 5.ลบ 'ตั้งค่าทีม'
  | $id | ไอดีรายการ
   */
  public static function deleteTeam($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteTeam($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_setting_user_team', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteTeam($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_setting_user_team', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tb_setting_team');
    }

    // USING_DATA
    $where = [
      'AND' => [
        'team_id'       => $id,
        'system_delete' => 0
      ]
    ];
    $model = Amst::get('sys_user', ['id'], $where);
    if (isset($model['id'])) {
      return Amst::repError('E0000', 'Can not delete using data');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ตั้งค่าทีม
  | Description | 1.ดึงข้อมูลลิส 'ผู้ใช้ที่อยู่ทีมนี้'
  | $where | [team_id(ไอดีทีม*บังคับส่ง)]
  | $options | [img_path(รูปผู้ใช้), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectUserInTeam($where = [], $options = []) {
    $return = []; // list

    if (!isset($where['team_id']) || !($where['team_id'])) {
      return Amst::selectEmptyList($options, 'Input Team ID');
    }

    $selected_fields = Amst::getSelectedFields($options);
    if (Util::isHasOptions('img_path', $options)) {
      $options['image']['img_path'] = [
        'code'          => 'user_profile_image_',
        'target_column' => 'id'
      ];
    }
    return Amst::selectNoKey('sys_user', $selected_fields, $where, $options);
  }

  public static function setTagDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    if (isset($record['update_user_id'])) {
      $record['update_user_username'] = '';
      $model                          = Amst::getByID('sys_user', ['id', 'username'], $record['update_user_id']);
      if (isset($model['id'])) {
        $record['update_user_username'] = $model['username'];
      }
    }

    return $record;
  }
}
