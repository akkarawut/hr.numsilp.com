<?php
class Module_Permission {
  private static $conf = [
    'description'    => 'คลาสสำหรับจัดการสิทธิ์โมดูล',
    'lbl_permission' => 'สิทธิ์โมดูล'
  ];

  public static function setPermissionListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $record) {
      // $selected_records[$key]['other_key'] = 'other_value';
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
  | Group | Permission
  | Description | 1.ดึงข้อมูลลิส 'Permission'
  | $where | [name(ชื่อเว็บ)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectPermission($where = [], $options = []) {
    $return = []; // list

    if (isset($where['module_code']) && $where['module_code']) {
      $sql_where['AND']['module_code'] = $where['module_code'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return          = Amst::selectNoKey('sys_module_permission', $selected_fields, $where, $options);
    return static::setPermissionListDetail($return, $options);
  }

  public static function setPermissionDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_value';

    return $record;
  }

  /**
  | Group | Permission
  | Description | 2.ดึงข้อมูล 'Permission' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ)]
   */
  public static function getPermissionByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $model           = Amst::getByID('sys_module_permission', $selected_fields, $id, $options);
    if (isset($model['id'])) {
      $return = static::setPermissionDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | Permission
  | Description | 3.เพิ่ม 'Permission'
  | $data | [name(ชื่อเว็บ), system_url(ที่อยู่URLของSystem), api_key(API KEY)]
   */
  public static function addNewPermission($data = []) {
    $rep = [
      'insert_id' => false
    ];

    return Amst::insert('sys_module_permission', $data);
  }

  /**
  | Group | Permission
  | Description | 4.อัพเดท 'Permission'
  | $id | ไอดีรายการ
  | $data | [name(ชื่อเว็บ), system_url(ที่อยู่URLของSystem), api_key(API KEY)]
   */
  public static function updatePermission($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_module_permission', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_permission']);
    }

    return Amst::updateByID('sys_module_permission', $data, $id);
  }

  /**
  | Group | Permission
  | Description | 5.ลบ 'Permission'
  | $id | ไอดีรายการ
   */
  public static function deletePermission($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeletePermission($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_module_permission', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeletePermission($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_module_permission', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_permission']);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Setting Permission
  | Description | 'Setting Permission'
   */
  public static function setPermssion($module_code, $permission, $on_off = true) {
    if ($on_off) {
      $where = [
        'AND' => [
          'module_code' => $module_code,
          'permission'  => $permission
        ]
      ];

      $check = Amst::get('sys_module_permission', '*', $where);
      if (!isset($check['id'])) {
        $data = [
          'module_code' => $module_code,
          'permission'  => $permission
        ];
        return Amst::insert('sys_module_permission', $data);
      }
    } else {
      $where = [
        'AND' => [
          'module_code' => $module_code,
          'permission'  => $permission
        ]
      ];
      return Amst::delete('sys_module_permission', $where);
    }
    return Amst::repSuccess(true);
  }
  /**
  | Group | Setting Permission
  | Description | 'Setting Permission'
  | $module_code | module_code
   */
  public static function selectPermssion($module_code) {
    $where = [
      'AND' => [
        'module_code' => $module_code
      ]
    ];

    $permissions = Amst::select('sys_module_permission', 'permission', $where);
    return $permissions;
  }
}
