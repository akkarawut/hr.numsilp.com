<?php
class User_type {

  /**
  | Group | Configuration
  | Description | 1.selectIdentityField
   */
  static function selectIdentityField($user_type_id) {
    $return = [
      'username' => 'Username',
      'email'    => 'Email',
      'tel_no'   => 'Telephone Number'
    ];
    return $return;
  }

  /**
  | Group | Configuration
  | Description | 2.getIdentityField
   */
  static function getIdentityField($user_type_id) {
    $return = 'username';

    $options = [
      'not_view'        => true,
      'selected_fields' => ['id', 'indentity_field']
    ];
    $model = static::getUserTypeByID($user_type_id, $options);
    if (isset($model['id'])) {
      $return = $model['indentity_field'];
    }

    return $return;
  }

  /**
  | Group | Configuration
  | Description | 3.setIdentityField
   */
  static function setIdentityField($user_type_id, $field_name) {
    $return = 'username';

    $sel = static::selectIdentityField($user_type_id);
    if (isset($sel[$field_name])) {
      return Amst::updateByID('sys_user_type', ['indentity_field' => $field_name], $user_type_id);
    } else {
      return Amst::repError('E0000', 'Unsupported Field');
    }
    return $return;
  }

  /**
  | Group | ประเภทผู้ใช้
  | Description | 4.นับจำนวนข้อมูลลิส 'ประเภทผู้ใช้' (total_count)
  | $where | [search_text(กรองข้อมูลจาก ชื่อ หรือ คำอธิบาย)]
   */
  public static function countUserType($where = [], $options = []) {
    $return = 0; // COUNT

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['name[~]']        = "{$where['search_text']}";
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user_type' : 'sys_user_type';
    return Amst::count($table_name, $sql_where);
  }

  public static function setUserTypeListDetail($code, $return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    foreach ($selected_records as $key => $record) {
      // V1
      $selected_records[$key]['basic_info_progress'] = static::getBasicInfoProgress($record);
      $selected_records[$key]['user_info_progress']  = static::getUserInfoProgress($record);
      $selected_records[$key]['document_progress']   = static::getDocumentProgress($record);
      // CODE_FORMAT
      $selected_records[$key]['user_code_format'] = "{$record['user_code_format_text1']}{$record['user_code_format_text2']}".str_repeat(0, $record['user_code_format_digit3']);

      // V2
      $info                                      = static::getInfo($record);
      $selected_records[$key]['checked_count']   = $info['checked_count'];
      $selected_records[$key]['total_count']     = $info['total_count'];
      $selected_records[$key]['checked_percent'] = $info['checked_percent'];

      // V3
      $selected_records[$key]['valid_function_count'] = 99;
      $selected_records[$key]['total_function_count'] = 99;
      $selected_records[$key]['permission_percent']   = 100;
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
  | Group | ประเภทผู้ใช้
  | Description | 5.ดึงข้อมูลลิส 'ประเภทผู้ใช้'
  | $where | [search_text(กรองข้อมูลจาก ชื่อ หรือ คำอธิบาย)]
  | $options | [not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectUserType($where = [], $options = []) {
    $return = []; // user_type_list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['name[~]']        = "{$where['search_text']}";
      $sql_where['OR #search_text']['description[~]'] = "{$where['search_text']}";
    }
    if (isset($where['display'])) {
      $sql_where['AND']['display'] = "{$where['display']}";
    }
    if (isset($where['module'])) {
      $sql_where['AND']['module'] = "{$where['module']}";
    }

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_type' : 'sys_user_type';
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');
    if (Util::isHasOptions('icon_img_path', $options)) {
      $options['image'][] = [
        'code'          => 'user_type_icon_',
        'name'          => 'icon_img_path',
        'target_column' => 'id'
      ];
    }
    $return = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
    return static::setUserTypeListDetail('SYSTEM', $return, $options);
  }

  public static function setUserTypeDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    $record['basic_info_progress'] = static::getBasicInfoProgress($record);
    $record['user_info_progress']  = static::getUserInfoProgress($record);
    $record['document_progress']   = static::getDocumentProgress($record);

    // CODE_FORMAT
    $record['user_code_format'] = "{$record['user_code_format_text1']}{$record['user_code_format_text2']}".str_repeat(0, $record['user_code_format_digit3']);

    $info                      = static::getInfo($record);
    $record['checked_count']   = $info['checked_count'];
    $record['total_count']     = $info['total_count'];
    $record['checked_percent'] = $info['checked_percent'];
    // $record['getInfo'] = $info['getInfo'];

    return $record;
  }

  /**
  | Group | ประเภทผู้ใช้
  | Description | 6.ดึงข้อมูล 'ประเภทผู้ใช้' ด้วยไอดี
  | $id | ไอดีประเภทผู้ใช้
  | $options | [icon_img_path(พร้อมรูปไอค่อน), not_view(true // เอาแค่ข้อมูลหลักไป edit *เร็ว), selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */

  public static function getUserTypeByID($id, $options = []) {
    $return = null;

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user_type' : 'sys_user_type';
    $selected_fields = Amst::getSelectedFields($options, ['id', 'user_code_format_text1', 'user_code_format_text2', 'user_code_format_digit3']);

    if ($id) {
      if (Util::isHasOptions('icon_img_path', $options)) {
        $options['image'][] = [
          'code'          => 'user_type_icon_',
          'name'          => 'icon_img_path',
          'target_column' => 'id'
        ];
      }
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setUserTypeDetail($model, $options);
      }
    } else {
      Log::add(0, ['err_msg', 'Invalid ID', 'id' => $id], 0, 'backend');
    }
    return $return;
  }

  /**
  | Group | ประเภทผู้ใช้
  | Description | 7.เพิ่ม 'ประเภทผู้ใช้'
  | $data | [name(ชื่อประเภทผู้ใช้),description(คำอธิบาย)]
  | "โค้ดอัพโหลดรูป" | ['user_type_icon_'.$id(รูปไอค่อน)]
   */
  public static function addNewUserType($data) {
    $rep = [
      'insert_id' => false
    ];

    // DEFAULT
    $data['is_allow_login']         = 1;
    $data['is_allow_program_login'] = 1;
    $data['is_allow_app_login']     = 1;
    $api                            = Amst::insert('sys_user_type', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['insert_id'] = $api['response_data']['insert_id'];
      if (isset($def_user_type_id) && $def_user_type_id) {
        // [FILE]
        $rep['_file_copy'] = File::copy('user_type_icon_'.$def_user_type_id, 'user_type_icon_'.$rep['insert_id']);
      }
    } else {
      return $api;
    }

    return Amst::repSuccess($rep);
  }

  public static function getInfo($user_type_data = []) {
    $return = [
      'checked_count'   => 0,
      'total_count'     => 0,
      'checked_percent' => 0
    ];

    foreach ($user_type_data as $key => $i_val) {
      // $return['getInfo'][] = $key;
      if (Util::startsWith($key, 'is_')) {
        $return['total_count']++;
        if ($i_val == 1) {
          $return['checked_count']++;
        }
      }
    }

    if ($return['total_count'] > 0) {
      $return['checked_percent'] = number_format($return['checked_count'] * 100 / $return['total_count'], 2);
    }

    return $return;
  }

  public static function getBasicInfoPercent($data, $model = []) {
    $return = 0;

    $checklist = [
      'is_format_user_code',
      'is_allow_login',
      'is_use_pin_code'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($data[$i_name])) {
        if ($data[$i_name] == 1) {
          $enabled_count++;
        }
      } else {
        if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
          $enabled_count++;
        }
      }
    }

    return ceil($enabled_count * 100 / count($checklist));
  }

  public static function getUserInfoPercent($data, $model = []) {
    $return = 0;

    $checklist = [
      'is_has_profile_image',
      'is_tel',
      'is_has_nick_name',
      'is_has_email',
      'is_has_facebook',
      'is_has_line',
      'is_has_wechat',
      'is_has_twitter',
      'is_address_province',
      'is_address_country',
      'is_address_zipcode',
      'is_multiple_bank_account',
      'is_has_tax_no',
      'is_has_id_card_no',
      'is_has_passport_no',
      'is_has_passport_country',
      'is_has_nationality',
      'is_has_religion',
      'is_has_gender',
      'is_has_birthdate',
      'is_has_birth_place',
      'is_has_leader',
      'is_has_career',
      'is_has_company'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($data[$i_name])) {
        if ($data[$i_name] == 1) {
          $enabled_count++;
        }
      } else {
        if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
          $enabled_count++;
        }
      }
    }

    return ceil($enabled_count * 100 / count($checklist));
  }

  public static function getDocumentPercent($data, $model = []) {
    $return = 0;

    $checklist = [
      'is_doc_id_card',
      'is_doc_passport_cover',
      'is_doc_bank_account_cover',
      'is_doc_statement_image'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($data[$i_name])) {
        if ($data[$i_name] == 1) {
          $enabled_count++;
        }
      } else {
        if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
          $enabled_count++;
        }
      }
    }

    return ceil($enabled_count * 100 / count($checklist));
  }

  public static function getBasicInfoProgress($model) {
    $return = [];

    $checklist = [
      'is_format_user_code',
      'is_allow_login',
      'is_use_pin_code'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
        $return[] = 1;
      } else {
        $return[] = 0;
      }
    }

    return $return;
  }

  public static function getUserInfoProgress($model) {
    $return = [];

    $checklist = [
      'is_has_profile_image',
      'is_tel',
      'is_has_nick_name',
      'is_has_email',
      'is_has_facebook',
      'is_has_line',
      'is_has_wechat',
      'is_has_twitter',
      'is_address_province',
      'is_address_country',
      'is_address_zipcode',
      'is_multiple_bank_account',
      'is_has_tax_no',
      'is_has_id_card_no',
      'is_has_passport_no',
      'is_has_passport_country',
      'is_has_nationality',
      'is_has_religion',
      'is_has_gender',
      'is_has_birthdate',
      'is_has_birth_place',
      'is_has_leader',
      'is_has_career',
      'is_has_company'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
        $return[] = 1;
      } else {
        $return[] = 0;
      }
    }

    return $return;
  }

  public static function getDocumentProgress($model) {
    $return = [];

    $checklist = [
      'is_doc_id_card',
      'is_doc_passport_cover',
      'is_doc_bank_account_cover',
      'is_doc_statement_image'
    ];

    $enabled_count = 0;
    foreach ($checklist as $i_name) {
      if (isset($model[$i_name]) && ($model[$i_name] == 1)) {
        $return[] = 1;
      } else {
        $return[] = 0;
      }
    }

    return $return;
  }

  /**
  | Group | ประเภทผู้ใช้
  | Description | 8.อัพเดท 'ประเภทผู้ใช้'
  | $id | ไอดีประเภทผู้ใช้
  | $data | [name(ชื่อประเภทผู้ใช้), description(คำอธิบาย)]
  | "โค้ดอัพโหลดรูป" | ['user_type_icon_'.$id(รูปไอค่อน)]
   */
  public static function updateUserType($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_user_type', '*', $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tb_sys_user_type');
    }

    $data                       = Amst::checkWhere($data);
    $data['basic_info_percent'] = static::getBasicInfoPercent($data, $model);
    $data['user_info_percent']  = static::getUserInfoPercent($data, $model);
    $data['document_percent']   = static::getDocumentPercent($data, $model);

    return Amst::updateByID('sys_user_type', $data, $id);
  }

  /**
  | Group | ประเภทผู้ใช้
  | Description | 9.ลบ 'ประเภทผู้ใช้'
  | $id | ไอดีประเภทผู้ใช้
   */
  public static function deleteUserType($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteUserType($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['delete_result'] = Amst::deleteByID('sys_user_type', $id);
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteUserType($id) {
    $rep = [
      'id' => $id
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_user_type', ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tb_sys_user_type');
    }

    $user = Amst::get('sys_user', ['id'], ['user_type_id' => $model['id']]);
    if (isset($user['id'])) {
      return Amst::repError('E0000', 'User type in use');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ซ่อนสิทธิผู้ใช้
  | Description | ดึง permission ของ UserType โดยใช้ user_type_id
  | $user_type_id | ไอดีประเภทผู้ใช้
   */
  public static function getHiddenPermissionList($user_type_id) {
    $return = [];

    if ($user_type_id) {
      $sys_user_type = Amst::getByID('sys_user_type', ['id', 'hidden_permission'], $user_type_id);
      if (isset($sys_user_type['id']) && $sys_user_type['id']) {
        $return = json_decode($sys_user_type['hidden_permission'], true);
      } else {
        return $return;
      }
    }

    return $return;
  }

  /**
  | Group | ซ่อนสิทธิผู้ใช้
  | Description | ดึง permission ของ UserType โดยใช้ user_type_id
  | $user_type_id | ไอดีประเภทผู้ใช้
   */
  public static function getSettingPermissionList($user_type_id) {
    $return = [];

    if ($user_type_id) {
      $sys_user_type = Amst::getByID('sys_user_type', ['id', 'setting_permission'], $user_type_id);
      if (isset($sys_user_type['id']) && $sys_user_type['id']) {
        $return = json_decode($sys_user_type['setting_permission'], true);
      } else {
        return $return;
      }
    }

    return $return;
  }

  /**
  | Group | ซ่อนสิทธิผู้ใช้
  | Description | เพิ่ม / ลบ Permission ให้ Block โมดูลนั้นๆ *ถ้าซ่อน ระบบจะไปเอา permission ออกด้วย
  | $user_type_id | ไอดีประเภทผู้ใช้
  | $permission_codes | โค้ดสิทธิที่หน้าบ้านตั้งชื่อไว้ เช่น 'user_menu_ja'
   */
  public static function triggerHiddenPermission($user_type_id, $permission_codes) {
    $rep = [
      'update_result' => false
    ];

    if (!$user_type_id) {
      return Amst::repError('E0000', 'Invalid user_type_id');
    }

    if (!$permission_codes) {
      return Amst::repError('E0000', 'Invalid Permission Code');
    }

    if (!is_array($permission_codes)) {
      $permission_codes = [$permission_codes];
    }

    $sys_user_type = Amst::getByID('sys_user_type', ['id', 'hidden_permission'], $user_type_id);
    if (!isset($sys_user_type['id'])) {
      return Amst::repError('E0005', 'sys_user_type');
    }

    $permission_codes = json_encode($permission_codes);

    $rep['update_result'] = Amst::updateByID('sys_user_type', ['hidden_permission' => $permission_codes], $user_type_id);

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ซ่อนสิทธิผู้ใช้
  | Description | เพิ่ม / ลบ Permission ให้ Block โมดูลนั้นๆ *ถ้าซ่อน ระบบจะไปเอา permission ออกด้วย
  | $user_type_id | ไอดีประเภทผู้ใช้
  | $permission_codes | โค้ดสิทธิที่หน้าบ้านตั้งชื่อไว้ เช่น 'user_menu_ja'
   */
  public static function triggerSettingPermission($user_type_id, $permission_codes) {
    $rep = [
      'update_result' => false
    ];

    if (!$user_type_id) {
      return Amst::repError('E0000', 'Invalid user_type_id');
    }

    if (!$permission_codes) {
      return Amst::repError('E0000', 'Invalid Permission Code');
    }

    if (!is_array($permission_codes)) {
      $permission_codes = [$permission_codes];
    }

    $sys_user_type = Amst::getByID('sys_user_type', ['id', 'setting_permission'], $user_type_id);
    if (!isset($sys_user_type['id'])) {
      return Amst::repError('E0005', 'sys_user_type');
    }

    $permission_codes = json_encode($permission_codes);

    $rep['update_result'] = Amst::updateByID('sys_user_type', ['setting_permission' => $permission_codes], $user_type_id);

    return Amst::repSuccess($rep);
  }

  private static function _removePermission($user_type_id, $permission_code) {
    $rep = [
      'user_type_id'          => $user_type_id,
      'permission_code'       => $permission_code,
      'triggerTemplateItem'   => [],
      'triggerUserPermission' => []
    ];

    if (!$user_type_id) {
      return Amst::repError('E0000', 'Invalid user_type_id');
    }

    if (!$permission_code) {
      return Amst::repError('E0000', 'Invalid Permission Code');
    }

    // Template - All user_type's Templates
    // $where = [
    //   'AND' => [
    //     'user_type_id'  => $user_type_id,
    //     'system_delete' => 0
    //   ]
    // ];
    // $selected_records = User_Basic_Setting::selectPermissionTemplate($where);
    // foreach ($selected_records as $i_template) {
    //   User_Basic_Setting::triggerTemplateItem($i_template['id'], $permission_code, 0);
    //   $rep['triggerTemplateItem'][$i_template['id']] = User_Basic_Setting::hasTemplateItem($i_template['id'], $permission_code);
    // }

    // UserPerm - All Users
    $where = [
      'user_type_id' => $user_type_id
    ];
    $selected_records = User::selectUser('', $where);
    foreach ($selected_records as $i_user) {
      User_Permission::triggerUserPermission($i_user['id'], $permission_code, 0);
      $rep['triggerUserPermission'][$i_user['id']] = User_Permission::hasUserPermission($i_user['id'], $permission_code);
    }

    return Amst::repSuccess($rep);
  }
}
?>