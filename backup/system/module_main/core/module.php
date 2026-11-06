<?php
class Module {
  private static $conf = [
    'description'      => 'คลาสสำหรับจัดการโมดูลทั้งหมดในระบบ',
    'remark_20210124'  => '1.ยกเลิกการใช้ไฟล์ JSON',
    'code_len'         => 5,
    'code_char'        => 'abcdefghijklmnopqrstuvwxyz',
    'tb_user'          => 'sys_user',
    'lbl_user'         => 'User',
    'tb_setting_team'  => 'sys_setting_user_team',
    'lbl_setting_team' => 'Team Setting'
  ];
  private static $_curr_setting = [];

  /**
  | Group | เรียกทุกครั้งที่เริ่มระบบ
  | Description | 1.ตั้ง/อัพเดท การตั้งค่าโมดูล เพิ่มการตั้งค่า/image_code_list ล่าสุดจากไฟล์ setting.php
   */
  static function initModule() {
    $rep = [
      'update_result' => false
    ];

    // แก้แล้ว เทสแล้ว ถ้า Backend อัพไฟล์ setting มาใหม่แล้วเรียกฟังกชั่น อัพเดท ข้อมูลจะปรับตาม setting แต่เก็บค่าเดิมไว้ด้วย ถ้า setting นั้นยังอยู่
    $selected_records = static::selectModule();
    foreach ($selected_records as $row) {
      static::updateModule($row['id'], []);
    }
    unset($selected_records);
    $rep['update_result'] = true;

    return Amst::repSuccess($rep);
  }

  /**
  | Group | เมนู
  | Description | 1.ดึงข้อมูล เมนูจัดการโมดูล (Manage Module)
   */
  public static function selectMenu($where = [], $options = []) {
    $return = []; // list

    // $selected_records = User_type::selectUserType();
    foreach ($selected_records as $row) {
      $setting_list = [];
      // FIXED-4
      // if (isset($row['is_permission_template']) && $row['is_permission_template'] == 1) {
      //   $setting_list['permission_template'] = Amst::count(static::$conf['tb_permission_template'], ['user_type_id' => $row['id']]);
      // }
      if (isset($row['is_has_category']) && $row['is_has_category'] == 1) {
        $setting_list['has_category'] = Amst::count(static::$conf['tb_setting_category'], ['user_type_id' => $row['id']]);
      }
      if (isset($row['is_has_team']) && $row['is_has_team'] == 1) {
        $setting_list['has_team'] = Amst::count(static::$conf['tb_setting_team'], ['user_type_id' => $row['id']]);
      }
      if (isset($row['is_has_tag']) && $row['is_has_tag'] == 1) {
        $setting_list['has_tag'] = Amst::count(static::$conf['tb_setting_tag'], ['user_type_id' => $row['id']]);
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

  private static function _validateFunction($module) {
    $rep = [
      'module'                      => $module,
      'module_default_setting_path' => '',
      'file_path'                   => ''
    ];
    $module_default_setting_path = ROOT_PHP.'/'.Config_Module::$module_file[$module].'/setting.php';
    $file_path                   = File::getFolderFullPath();
    // validate
    if (!$module) {
      return Amst::repError('invalid input module');
    } else if (!isset(Config_Module::$module_file[$module])) {
      return Amst::repError('path not found');
    } else if (!is_dir($file_path) || !is_writable($file_path)) {
      return Amst::repError('no permission to save file');
    } else if (!file_exists($module_default_setting_path)) {
      return Amst::repError('cannot find module setting path ['.$module_default_setting_path.']');
    }
    $rep['module_default_setting_path'] = $module_default_setting_path;
    $rep['file_path']                   = $file_path;
    return Amst::repSuccess($rep);
  }

  /**
  | Group | INFO
  | Description | 1.ดึงข้อมูล Info ที่ตั้งค่าไว้ general / specific
   */
  public static function selectInfo($where = [], $options = []) {
    $return = [];

    $selected_records = []; //Config_Module::$module_list;
    if (isset($where['type']) && ($where['type'] == 'general' || in_array('general', $where['type']))) {
      $selected_records = array_merge($selected_records, Config_Module::$module_general_list);
    }
    if (isset($where['type']) && ($where['type'] == 'specific' || in_array('specific', $where['type']))) {
      $selected_records = array_merge($selected_records, Config_Module::$module_specific_list);
    }

    foreach ($selected_records as $i_module => $i_info) {
      if (file_exists(ROOT_PHP.DIRECTORY_SEPARATOR.$i_info['path'])) {
        $return[] = [
          'module' => $i_module,
          'name'   => $i_module,
          'prefix' => $i_info['prefix']
        ];
      }
    }
    return $return;
  }

  /**
  | Group | โมดูลที่สามารถติดตั้งได้
  | Description | 1.ดึงข้อมูลลิส 'โมดูล' ที่สามารถติดตั้งได้
  | $where | [code(โค้ดโมดูล)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectExistsModule($where = [], $options = []) {
    $return = [];

    $specific_config_list = [
      'User' => [
        'display'          => 'display',
        'manage_user_type' => 'manage_user_type',
        'admin'            => 'admin',
        'multiple_owner'   => 'multiple_owner',
        'ownership'        => 'ownership'
      ],
      'Task' => [
        'admin' => 'admin'
      ]
    ];
    $general_config_list = [
      'admin'          => 'admin',
      'multiple_owner' => 'multiple_owner',
      'ownership'      => 'ownership'
    ];

    foreach (Config_Module::$module_file as $key => $value) {
      if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$key])) {
        $return[] = [
          'key'         => $key,
          'name'        => $key,
          'config_list' => isset($specific_config_list[$key]) ? $specific_config_list[$key] : $general_config_list
        ];
      }
    }
    return $return;
  }

  /**
  | Group | โมดูลที่สามารถติดตั้งได้
  | Description | 1.ดึงข้อมูลลิส 'โมดูล' ที่สามารถติดตั้งได้
  | $module | ชื่อโมดูล เช่น User Task HunterFarm
   */
  public static function getExistsModuleByModule($module) {
    $return = null;

    $specific_config_list = [
      'User' => [
        'display'          => 'display',
        'manage_user_type' => 'manage_user_type',
        'admin'            => 'admin',
        'multiple_owner'   => 'multiple_owner',
        'ownership'        => 'ownership'
      ],
      'Task' => [
        'admin' => 'admin'
      ]
    ];
    $general_config_list = [
      'admin'          => 'admin',
      'multiple_owner' => 'multiple_owner',
      'ownership'      => 'ownership'
    ];

    if (isset(Config_Module::$module_file[$module]) && Config_Module::$module_file[$module]) {
      if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$module])) {
        $return = [
          'key'         => $module,
          'name'        => $module,
          'config_list' => isset($specific_config_list[$module]) ? $specific_config_list[$module] : $general_config_list
        ];
      }
    }
    return $return;
  }

  public static function setModuleListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Works...
    $managed_user_type_ids = [];
    foreach ($selected_records as $key => $row) {
      // Do Each Works...
      $selected_records[$key]['managed_user_type_name'] = '';
      if (isset($row['managed_user_type_id']) && $row['managed_user_type_id']) {
        $managed_user_type_ids[$row['managed_user_type_id']] = $row['managed_user_type_id'];
      }
    }

    // if ($managed_user_type_ids) {
    //   $where = [
    //     'AND' => [
    //       'id'            => array_values($managed_user_type_ids),
    //       'system_delete' => 0
    //     ]
    //   ];
    //   $user_type_list = Amst::select('sys_user_type', ['id', 'name'], $where);
    //   // SET
    //   foreach ($selected_records as $key => $row) {
    //     // Do Each Works...
    //     if (isset($user_type_list[$row['managed_user_type_id']])) {
    //       $selected_records[$key]['managed_user_type_name'] = $user_type_list[$row['managed_user_type_id']]['name'];
    //     }
    //   }
    // }

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  public static function setModuleListDetail2($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    foreach ($selected_records as $key => $record) {
      //ICON
      $light_icon                           = File::getPath('sys_function_light_icon_'.$record['id']);
      $dark_icon                            = File::getPath('sys_function_dark_icon_'.$record['id']);
      $selected_records[$key]['light_icon'] = $light_icon;
      $selected_records[$key]['dark_icon']  = $dark_icon;
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
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 1.ดึงข้อมูลลิส 'โมดูล'
  | $where | [code(โค้ดโมดูล)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectModule($where = [], $options = []) {
    $return = []; // list

    $options = Amst::checkOption($options);
    if (!isset($options['sort'])) {
      $options['sort'] = ['sort_order_id' => 'ASC'];
    }
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $modules         = Amst::selectNoKey('sys_function', $selected_fields, $where, $options);
    return static::setModuleListDetail2($modules, $options);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 1.ดึงข้อมูลลิส 'โมดูล' ที่แสดงในโหมด-ทั่วไป
  | $where | [code(โค้ดโมดูล)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectGeneralModule($where = [], $options = []) {
    $return = []; // list

    $where = Amst::checkWhere($where);
    // $where['module[!]'] = 'User';
    // $where['display'] = 'notuser';

    $options = Amst::checkOption($options);
    if (!isset($options['sort'])) {
      $options['sort'] = ['sort_order_id' => 'ASC'];
    }
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    return Amst::selectNoKey('sys_function', $selected_fields, $where, $options);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 1.ดึงข้อมูลลิส 'โมดูล' ที่แสดงในโหมด-User
  | $where | [code(โค้ดโมดูล)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectUserModule($where = [], $options = []) {
    $return = []; // list

    $where = Amst::checkWhere($where);
    // $where['module'] = 'User';
    // $where['display'] = 'user';

    $options = Amst::checkOption($options);
    if (!isset($options['sort'])) {
      $options['sort'] = ['sort_order_id' => 'ASC'];
    }
    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    $return          = Amst::selectNoKey('sys_function', $selected_fields, $where, $options);
    return static::setModuleListDetail($return, $options);
  }

  public static function setModuleDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    if (isset($record['json_image_code'])) {
      $record['image_code_list'] = json_decode($record['json_image_code'], true);
    }

    if (isset($record['json_exdata'])) {
      $exdata                = json_decode($record['json_exdata'], true);
      $record['json_exdata'] = $exdata;
      if ($exdata) {
        foreach ($exdata as $key => $value) {
          if (!isset($record[$key])) {
            $record[$key] = $value;
          }
        }
      }
    }

    // FIRST USER by TYPE
    $record['first_username'] = '';
    if ($record['module'] == 'User') {
      $model = Amst::get('sys_user', ['id', 'username'], ['user_type' => $record['name']]);
    }
    if (isset($model['id'])) {
      $record['first_username'] = $model['username'];
    }

    $record['hidden_permission']  = json_decode($record['hidden_permission'], true);
    $record['setting_permission'] = json_decode($record['setting_permission'], true);

    //ICON
    $record['light_icon'] = File::getPath('sys_function_light_icon_'.$record['id']);
    $record['dark_icon']  = File::getPath('sys_function_dark_icon_'.$record['id']);

    $json_keys = [
      'json_list_table',
      'json_tab_default',
      'json_tab_profile',
      'json_tab_addnew',
      'json_tab_account_info',
      'json_tab_permission',
      'json_image_profile'
    ];
    foreach ($json_keys as $key) {
      if (isset($record[$key])) {
        $record[$key] = json_decode($record[$key], true);
      }
    }

    return $record;
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 2.ดึงข้อมูล 'โมดูล' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getModuleByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($id) {
      $model = Amst::getByID('sys_function', $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setModuleDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 2.ดึงข้อมูล 'โมดูล' ด้วยโค้ด5ตัว
  | $code | code
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getModuleByCode($code, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);

    if ($code) {
      $model = Amst::get('sys_function', $selected_fields, ['code' => $code], $options);
      if (isset($model['id'])) {
        $return = static::setModuleDetail($model, $options);
      }
    }
    return $return;
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 2.ดึงข้อมูล 'โมดูล' ด้วยชื่อโมดูล
  | $module | ชื่อโมดูล User / HunterFarm
   */
  public static function getDefaultModuleSetting($module) {
    $return = [];

    if (!$module || !isset(Config_Module::$module_file[$module])) {
      Log::add(2, Amst::getErrorMsg('E0001', 'Module'), 0, 'backend');
      return $return;
    }

    $api = static::_validateFunction($module);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $module_default_setting_path = $api['response_data']['module_default_setting_path'];
      if ($module_default_setting_path) {
        include $module_default_setting_path;
        if (isset($setting[$module])) {
          $return = $setting[$module];
        } else {
          Log::add(2, "Invalid Module '{$module}' Setting.php setting", 0, 'backend');
        }
      }
    } else {
      // [TODO] on AltFileSystem (Cant write file)
      // Log::add(2, $api['response_message'], 0, 'backend');
    }
    return $return;
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 3.เพิ่ม 'โมดูล'
  | $data | [module(ชื่อโมดูลจริงๆ*บังคับส่ง), name(ตั้งชื่อเอง)]
   */
  public static function addNewModule($data, $light_icon = [], $dark_icon = []) {
    $rep = [
      'insert_id' => false
    ];

    $data = Amst::checkWhere($data);
    if (isset($data['module']) && ($data['module'])) {
      // INFO-Exists
      if (!isset(Config_Module::$module_file[$data['module']])) {
        return Amst::repError('E0000', "Module({$data['module']}) Info Not Found");
      }
      // CLASS-Exists
      $directory_path = Config_Module::$module_file[$data['module']];
      // IMPORT FUNCTION FROM CLASS FOLDER
      if (!file_exists(ROOT_PHP.DIRECTORY_SEPARATOR.$directory_path.'/class/')) {
        return Amst::repError("Module({$data['module']}) Class Not Found");
      }
      // Generate CODE
      while (true) {
        $new_code = Util::getRandomString(static::$conf['code_len'], static::$conf['code_char']);
        if (!DB::has(Info::$system_table['function'], ['code' => $new_code])) {
          break;
        }
      }
    } else {
      return Amst::repError('E0001', 'Selected Module');
    }

    if (!isset($new_code) || !$new_code) {
      return Amst::repError('E0000', 'Fail to create Module');
    }

    $data['code'] = $new_code;
    // AUTONAME
    if (!isset($data['name'])) {
      $data['name'] = $data['module'];
    }
    $data['json_image_code'] = '{}';
    $data['json_exdata']     = '{}';

    $api = Amst::insert('sys_function', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];

      if ($light_icon) {
        File::add('sys_function_light_icon_'.$insert_id, $light_icon);
      }
      if ($dark_icon) {
        File::add('sys_function_dark_icon_'.$insert_id, $dark_icon);
      }

      if ($data['module'] == 'User') {
        static::defUserColumns();
      }

      // INIT DB
      DB_Init::regenerateSQLTable();
      // SUCCESS
      $rep['insert_id'] = $insert_id;
      $rep['code']      = $new_code;
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | DEFAULT USER COLUMNS
  | Description | DEFAULT USER COLUMNS
   */
  public static function defUserColumns() {
    //def sys_extra_column
    $def_columns = [
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'Username',
        'category'       => 'System',
        'type'           => 'Username',
        'db_column_name' => 'username',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'Password',
        'category'       => 'System',
        'type'           => 'Password',
        'db_column_name' => 'password',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Code',
        'category'       => 'System',
        'type'           => 'Varchar',
        'db_column_name' => 'user_code',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Type',
        'category'       => 'System',
        'type'           => 'Varchar',
        'db_column_name' => 'user_type',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Permission',
        'category'       => 'System',
        'type'           => 'Longtext',
        'db_column_name' => 'permission',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Ban',
        'category'       => 'System',
        'type'           => 'Tinyint',
        'db_column_name' => 'is_ban',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'Last Online Date Time',
        'category'       => 'System',
        'type'           => 'Datetime',
        'db_column_name' => 'last_online_date_time',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Change Password Date Time',
        'category'       => 'System',
        'type'           => 'Datetime',
        'db_column_name' => 'password_datetime',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Change Password User ID',
        'category'       => 'System',
        'type'           => 'Bigint',
        'db_column_name' => 'password_user_id',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User Change Password Username',
        'category'       => 'System',
        'type'           => 'Username',
        'db_column_name' => 'password_username',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User full name',
        'category'       => 'System',
        'type'           => 'Varchar',
        'db_column_name' => 'full_name',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'Discord User ID',
        'category'       => 'System',
        'type'           => 'Longtext',
        'db_column_name' => 'discord_user_id',
        'system_column'  => 1
      ],
      [
        'code'           => 'System',
        'table_name'     => 'sys_user',
        'name'           => 'User team ID',
        'category'       => 'System',
        'type'           => 'Bigint',
        'db_column_name' => 'team_id',
        'system_column'  => 1
      ]
    ];
    foreach ($def_columns as $key => $def_column) {
      $where = [
        'AND' => [
          'code'           => $def_column['code'],
          'db_column_name' => $def_column['db_column_name'],
          'system_delete'  => 0
        ]
      ];
      $check = Amst::get('sys_extra_column', '*', $where);
      if (!isset($check['id'])) {
        Amst::insert('sys_extra_column', $def_column);
      }
    }
    return Amst::repSuccess(true);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 4.อัพเดท 'โมดูล'
  | $id | ไอดีรายการ
  | $data | [name(ตั้งชื่อเอง), description(คำอธิบาย), display(แสดงที่หมวด user/notuser), is_show(แสดงผล), managed_user_type_id(จัดการผู้ใช้งานประเภทไหนอยู่), is_multiple_owner(ปิด-เปิดโหมดหลายร้าน), is_viewas(ปิด-เปิดโหมด admin ดู), admin_user_type_id(ประเภทผู้ใช้ที่เป็นแอดมิน), vendor_user_type_id(ประเภทผู้ใช้ที่เป็นเจ้าของร้าน), image_code_list(Array โค้ดรูป), และข้อมูลอื่นๆตาม setting]
   */
  public static function updateModule($id, $data, $light_icon = [], $dark_icon = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_function', ['id', 'module', 'json_image_code', 'json_exdata'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'Module');
    }

    // Move SYSTEM_FIELDS to SYS_DATA
    $sys_data = [];
    foreach (INFO::$system_tables['sys_function'] as $key => $value) {
      if (isset($data[$key])) {
        $sys_data[$key] = $data[$key];
        unset($data[$key]);
      }
    }

    // TEMPLATE
    $def_setting = static::getDefaultModuleSetting($model['module']);

    // ExData from Def
    $exdata     = [];
    $old_exdata = json_decode($model['json_exdata'], true);
    foreach ($def_setting as $key => $value) {
      if (isset($data[$key])) {
        $exdata[$key] = $data[$key]; // NEW
      } else if (isset($old_exdata[$key])) {
        $exdata[$key] = $old_exdata[$key]; // OLD
      } else {
        $exdata[$key] = $value; // DEF
      }
    }
    if ($model['json_exdata'] != json_encode($exdata)) {
      $sys_data['json_exdata'] = json_encode($exdata);
    }

    // Update DEF-List
    if (isset($def_setting['image_code_list']) && isset($model['json_image_code'])) {
      if (json_encode($def_setting['image_code_list']) != ($model['json_image_code'])) {
        $sys_data['json_image_code'] = json_encode($def_setting['image_code_list']);
      }
    }

    if (isset($sys_data['hidden_permission'])) {
      $sys_data['hidden_permission'] = json_encode($sys_data['hidden_permission']);
    }
    if (isset($sys_data['setting_permission'])) {
      $sys_data['setting_permission'] = json_encode($sys_data['setting_permission']);
    }

    // ICON
    if ($light_icon) {
      File::add('sys_function_light_icon_'.$id, $light_icon);
    }
    if ($dark_icon) {
      File::add('sys_function_dark_icon_'.$id, $dark_icon);
    }

    $json_keys = [
      'json_list_table',
      'json_tab_default',
      'json_tab_profile',
      'json_tab_addnew',
      'json_tab_account_info',
      'json_tab_permission',
      'json_image_profile'
    ];

    foreach ($json_keys as $key) {
      if (isset($sys_data[$key])) {
        $sys_data[$key] = json_encode($sys_data[$key]);
      }
    }

    // onChange
    if ($sys_data) {
      return Amst::updateByID('sys_function', $sys_data, $id);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 4.อัพเดท 'โมดูล' เฉพาะ key นั้น (ถ้ามี)
  | $code | โคดโมดูล
  | $key | key
  | $val | value
   */
  public static function updateModuleByKey($code, $key, $val) {
    $rep = [
      'update_result' => false
    ];

    $c_setting = static::getCurrentSetting($code);
    if (!isset($c_setting['id'])) {
      return Amst::repError('E0005', 'Module');
    }

    if (!isset($c_setting[$key])) {
      return Amst::repError('E0005', 'Key');
    }

    $u_data[$key] = $val;
    return static::updateModule($c_setting['id'], $u_data);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 5.สลับที่ระหว่างสองโมดูล
  | $current_id | ไอดีรายการต้นทาง
  | $move_to_id | ไอดีรายการปลายทาง
   */
  public static function swapModule($current_id, $move_to_id) {
    $rep = [
      'update_result' => false
    ];

    $current_entity = Amst::get(Info::$system_table['function'], ['sort_order_id'], ['id' => $current_id]);
    $move_to_entity = Amst::get(Info::$system_table['function'], ['sort_order_id'], ['id' => $move_to_id]);

    if ($current_entity['sort_order_id'] == $move_to_entity['sort_order_id']) {
      return Amst::repError('E0000', 'id cant be the same');
    }

    $current_arr = ['sort_order_id' => $current_entity['sort_order_id']];
    $move_to_arr = ['sort_order_id' => $move_to_entity['sort_order_id']];

    if (!DB::update(Info::$system_table['function'], $move_to_arr, ['id' => $current_id])) {
      return Amst::repError('E0000', DB::error());
    }

    $rep['update_result'] = DB::update(Info::$system_table['function'], $current_arr, ['id' => $move_to_id]);

    return Amst::repSuccess($rep);
  }

  /**
  | Group | โมดูลที่ติดตั้งแล้ว
  | Description | 5.ลบ 'โมดูล'
  | $id | ไอดีรายการ
   */
  public static function deleteModule($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteModule($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $fn_code = $api['response_data']['code'];
      $result  = DB::delete(Info::$system_table["function"], ['id' => $id]);
      if ($result) {
        // DROP TABLE
        if ($fn_code) {
          $tables = Amst::query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".Config::db_name."' AND TABLE_NAME NOT LIKE 'sys_%';");
          foreach ($tables as $table) {
            if (strpos($table['TABLE_NAME'], Info::module_table_prefix.$fn_code) !== false) {
              Amst::query('DROP TABLE IF EXISTS '.$table['TABLE_NAME'].';');
            }
          }
        }
        // All success
        $rep['delete_result'] = true;
        return Amst::repSuccess($rep);
      } else {
        return Amst::repError('E0000', 'failed to remove function.');
      }
    }
    return $api; // ERROR SURE
  }

  /**
  | Group | จัดตำแหน่งโมดูล
  | Description | 1.ดึงข้อมูล 'โมดูลจัดการผู้ใช้' ว่าตอนนี้อยู่ไหน
   */
  public static function getUserMngPostion() {
    $return = 'bottom';

    $setting = WLoves::getProgramSetup();
    if (isset($setting['module_usermng_postion'])) {
      $return = $setting['module_usermng_postion'];
    } else {
      $position                          = 'bottom';
      $setting['module_usermng_postion'] = $position;
      WLoves::setProgramSetup($setting);
    }

    return $return;
  }

  /**
  | Group | จัดตำแหน่งโมดูล
  | Description | 1.บันทึกตำแหน่ง *เฉพาะ* 'โมดูลจัดการผู้ใช้'
  | $position | ตำแหน่ง top หรือ bottom
   */
  public static function setUserMngPostion($position = 'top') {
    $rep = [
      'update_result' => false
    ];

    $position = trim(strtolower($position));
    if ($position) {
      $setting = WLoves::getProgramSetup();
      if (isset($module_setting['module_usermng_postion'])) {
        if ($module_setting['module_usermng_postion'] != $position) {
          $setting['module_usermng_postion'] = $position;
          WLoves::setProgramSetup($setting);
          $rep['update_result'] = true;
        }
      } else {
        $setting['module_usermng_postion'] = $position;
        WLoves::setProgramSetup($setting);
        $rep['update_result'] = true;
      }
    } else {
      Log::add(2, Amst::getErrorMsg('E0001', 'position'), 0, 'backend');
    }
    return Amst::repSuccess($rep);
  }

  private static function _checkDeleteModule($id) {
    $rep = [
      'id'   => $id,
      'code' => false
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_function', ['id', 'code'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'Module');
    }
    $rep['code'] = $model['code'];

    return Amst::repSuccess($rep);
  }

  /**
  | Group | เอาการตั้งค่าไปใช้
  | Description | 1. get พร้อมกับเช็คว่าเป็น Module นั้นจริงๆ *ถ้าไม่ส่ง Key คือ เอาทั้งหมด
  | $code | โค้ดโมดูล
  | $module_name | ชื่อโมดูล *เพิ่อยืนยันว่าจะเอา
  | $key | ชื่อช่องที่จะเอา ถ้าไม่ส่งจะส่งให้ ทั้งหมดเป็น Array
   */
  public static function getCurrentSetting($code, $module_name = '', $key = false) {
    $return = null;

    // VALIDATE
    if (!$code = strtolower(trim($code))) {
      return $return;
    }

    // [CACHE]
    if (isset(static::$_curr_setting[$code])) {
      $current_setting = static::$_curr_setting[$code];
    } else {
      $current_setting              = static::getModuleByCode($code);
      static::$_curr_setting[$code] = $current_setting;
    }

    // RECHECK
    if (!isset($current_setting['module']) || !$current_setting['module']) {
      Log::add(1, "Invalid Module Setting. Code:'{$code}', Key 'module' doesn't Exists.", 0, 'backend');
      return $return;
    }

    // Check Name
    if (!$module_name) {
      // FOR ANY MODULE
      if ($key) {
        if (isset($current_setting[$key])) {
          $return = $current_setting[$key];
        } else {
          Log::add(1, ["Invalid Module Query. Key:'{$key}' doesn't Exists.", $current_setting], 0, 'backend');
        }
      } else {
        $return = $current_setting;
      }
    } else if (isset($current_setting['module']) && ($current_setting['module'] == $module_name)) {
      if ($key) {
        if (isset($current_setting[$key])) {
          $return = $current_setting[$key];
        } else {
          Log::add(1, ["Invalid Module Query. Key:'{$key}' doesn't Exists in Module:'{$module_name}'.", $current_setting], 0, 'backend');
        }
      } else {
        $return = $current_setting;
      }
    } else {
      // [TODO] ปิดไปก่อน เพราะ บางทีก็สุ่มดึงไง ถ้าได้ก็เอางี้
      // Log::add(1, "Invalid Module Query. Code:'{$code}' and Module:'{$module_name}' doesn't Match.", 0, 'backend');
    }
    return $return;
  }

  private static function set($code, $data) {
    $rep = [
      'update_result' => false
    ];

    if (!$data || !is_array($data)) {
      return Amst::repError('data is invalid', $data);
    }
    // if (isset($data['module'])) {
    //   unset($data['module']); // READ-ONLY
    // }

    $function = Func::getByCode('*', $code);
    if (!isset($function['module'])) {
      return Amst::repError('E0000', 'Function not found.');
    }
    if (isset($data['name'])) {
      Func::edit($function['id'], ['name' => $data['name']]);
    }

    $api = static::_validateFunction($function['module']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      // เอาค่าปัจจุบันมาแก้
      $default_settings = static::get($code); // LATER check 2 rop a
      foreach ($data as $data_key => $data) {
        if (isset($default_settings[$data_key])) {
          $default_settings[$data_key] = $data;
        } else {
          // return Amst::repError('unknown key ['.$data_key.']');
        }
      }
      $module_setting_path = File::getFolderFullPath(true).'setting_'.$function['module'].'_'.$code.'.json';
      file_put_contents($module_setting_path, json_encode($default_settings));
      chmod($module_setting_path, 0777);
      // SUCCESS
      $rep['update_result'] = true;
    } else {
      return $api;
    }

    return Amst::repSuccess($rep);
  }

  private static function get($code) {
    $return = [];

    // [VALIDATE]
    if ($code) {
      $function = Func::getByCode('*', $code);
    }

    if (!isset($function['module']) || !isset(Config_Module::$module_file[$function['module']])) {
      return $return;
    }

    $api = static::_validateFunction($function['module']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      // $rep['update_result'] = $api['response_data']['update_result'];
      $module_setting_path         = File::getFolderFullPath(true).'setting_'.$function['module'].'_'.$code.'.json';
      $module_default_setting_path = ROOT_PHP.'/'.Config_Module::$module_file[$function['module']].'/setting.php';

      include $module_default_setting_path;
      $default_settings = $setting[$function['module']];

      // OVERWRITE BY current File
      if (file_exists($module_setting_path)) {
        $module_settings_json = file_get_contents($module_setting_path);
        $module_settings      = json_decode($module_settings_json, true);
        if ($module_settings) {
          foreach ($module_settings as $module_key => $module_setting) {
            if (isset($default_settings[$module_key])) {
              $default_settings[$module_key] = $module_setting;
            }
          }
        }
      }
      $return = $default_settings;
    }

    return $return;
  }

  private static function hasName($code, $module_name) {
    $return = false;

    $m_conf = static::get($code);

    // SAFTY
    if (!isset($m_conf['module']) || !$m_conf['module']) {
      Log::add(1, "Invalid Module Setting. Code:'{$code}', Key 'Name' doesn't Exists.", 0, 'backend');
      return $return;
    }

    // Check Key
    return (isset($m_conf['module']) && ($m_conf['module'] == $module_name));
  }

  private static function has($code, $module_name, $key) {
    $return = false;

    $m_conf = static::get($code);

    // SAFTY
    if (!isset($m_conf['module']) || !$m_conf['module']) {
      Log::add(1, "Invalid Module Setting. Code:'{$code}', Key 'Name' doesn't Exists.", 0, 'backend');
      return $return;
    }

    // Check Key
    if (isset($m_conf['module']) && ($m_conf['module'] == $module_name)) {
      if ($key) {
        return array_key_exists($key, $m_conf);
      }
    } else {
      Log::add(1, "Invalid Module Query. Code:'{$code}' and Module:'{$module_name}' doesn't Match.", 0, 'backend');
    }
    return $return;
  }

  private static function getModuleSetting($code, $module_name, $key = false) {
    $return = null;

    $m_conf = static::get($code);

    // SAFTY
    if (!isset($m_conf['module']) || !$m_conf['module']) {
      Log::add(1, "Invalid Module Setting. Code:'{$code}', Key 'Name' doesn't Exists.", 0, 'backend');
      return $return;
    }

    // Check Name
    if (isset($m_conf['module']) && ($m_conf['module'] == $module_name)) {
      if ($key) {
        if (isset($m_conf[$key])) {
          $return = $m_conf[$key];
        }
      } else {
        $return = $m_conf;
      }
    } else {
      Log::add(1, "Invalid Module Query. Code:'{$code}' and Module:'{$module_name}' doesn't Match.", 0, 'backend');
    }
    return $return;
  }

  private static function getModuleValue($code, $module_name, $key = false) {
    $return = null;

    $m_conf = static::get($code);

    // Check Name
    if (isset($m_conf['module']) && ($m_conf['module'] == $module_name)) {
      if ($key) {
        if (isset($m_conf[$key])) {
          $return = $m_conf[$key];
        }
      } else {
        $return = $m_conf;
      }
    } else {
      // $return = $m_conf;
    }
    return $return;
  }

  private static function getModuleSettingByCode($code, $key = false) {
    $return = [];

    $m_conf = static::get($code);

    // Check Name
    if (isset($m_conf['module'])) {
      if ($key) {
        if (isset($m_conf[$key])) {
          $return = $m_conf[$key];
        }
      } else {
        $return = $m_conf;
      }
    }
    return $return;
  }

  private static function selectRelatedModule($code) {
    $return = [
      'module_list' => []
    ];

    $function = Func::getByCode('*', $code);
    //$return['_last_query'][] = Amst::lastQuery();

    if ($function['module'] == 'POS') {
      $where = [
        'AND' => [
          'module' => ['Stock']
        ]
      ];
      $selected_records = Func::select(['id', 'code', 'module', 'name'], $where);
      //$return['_last_query'][] = Amst::lastQuery();
      foreach ($selected_records as $row) {
        $return['module_list'][] = $row;
      }
    } else if ($function['module'] == 'Booking') {
      $where = [
        'AND' => [
          'module' => ['Stock']
        ]
      ];
      $selected_records = Func::select(['id', 'code', 'module', 'name'], $where);
      //$return['_last_query'][] = Amst::lastQuery();
      foreach ($selected_records as $row) {
        $return['module_list'][] = $row;
      }
    }

    return $return;
  }

  private static function __UNUSED__selectUserModule() {
    $return = [];

    $return = [
      'module_list' => Func::select('*', ['module' => 'User', 'ORDER' => ['sort_order_id']], ['hide_default_column' => 1])
    ];

    return $return;
  }

  private static function selectUserSmallModule() {
    $return = [];

    $return = [
      'module_list' => Func::select('*', ['module' => 'User', 'ORDER' => ['sort_order_id']], ['hide_default_column' => 1])
    ];

    return $return;
  }

  private static function hasOwnerSystem($code, $module_name) {
    $return = false;

    $m_conf = static::get($code);

    // FOR [SINGLE_SHOP] OR [NO_OWNER]
    if (!isset($m_conf['is_multiple_owner']) || !$m_conf['is_multiple_owner']) {
      return false;
    }

    // SAFTY
    if (!isset($m_conf['module']) || !$m_conf['module']) {
      Log::add(1, ["Invalid Module Setting. Code:'{$code}', Key 'Name' doesn't Exists.", $m_conf], 0, 'backend');
      return $return;
    }

    // Check Key
    if (isset($m_conf['module']) && ($m_conf['module'] == $module_name)) {
      return array_key_exists('admin_user_type_id', $m_conf) || array_key_exists('vendor_user_type_id', $m_conf);
    } else {
      Log::add(1, "Invalid Module Query. Code:'{$code}' and Module:'{$module_name}' doesn't Match.", 0, 'backend');
    }
    return $return;
  }

  public static function getOwnerID($code, $module_name) {
    $owner_id = 0;

    if (!$code) {
      return $owner_id;
    }

    // FOR [SINGLE_SHOP] OR [NO_OWNER]
    $is_multiple_owner = Module::getCurrentSetting($code, $module_name, 'is_multiple_owner');
    if ($is_multiple_owner !== 1) {
      // NOT is_multiple_owner && not set
      return 0;
    }

    // SAFTY
    if (!static::hasOwnerSystem($code, $module_name, 'admin_user_type_id')) {
      // Log::add(1, 'no Owner system');
      return $owner_id;
    }

    // SAFTY
    $admin_user_type_id  = Module::getCurrentSetting($code, $module_name, 'admin_user_type_id');
    $vendor_user_type_id = Module::getCurrentSetting($code, $module_name, 'vendor_user_type_id');
    if (!$admin_user_type_id && !$vendor_user_type_id) {
      // [TODO] check flag Log::add(1, "Invalid Module Setting. Module : '{$module_name}({$code})', 'Admin' and 'Owner' doesn't Exists.", 0, 'backend');
      return $owner_id;
    }

    $user = User::getCurrent();
    // Log::add(1, $user, 0, 'backend');

    if (isset($user['id'])) {
      if (!isset($user['user_type_id'])) {
        Log::add(1, "Invalid Database Column, 'user_type_id' in sys_user doesn't Exists.", 0, 'backend');
      } else {
        if ($user['user_type_id'] == $admin_user_type_id) {
          // Log::add(1, 'Im Admin');
          $owner_id = $user['id']; // I'm Admin
        } else if ($user['user_type_id'] == $vendor_user_type_id) {
          // Log::add(1, 'Im Vendor');
          $owner_id = $user['id']; // I'm Vendor
        } else {
          // Log::add(1, 'Im USER', 0, 'backend');
          // Check Owner
          if (isset($user['owner_id']) && $user['owner_id']) {
            $leader_user = Amst::getByID('sys_user', ['id', 'user_type_id'], $user['owner_id']);
            // Log::add(1, Amst::lastQuery(), 0, 'backend');
            if (isset($leader_user['id'])) {
              if ($leader_user['user_type_id'] == $admin_user_type_id) {
                $owner_id = $leader_user['id']; // My Owner is Admin
              } else if ($leader_user['user_type_id'] == $vendor_user_type_id) {
                $owner_id = $leader_user['id']; // My Owner is Vendor
              }
            }
          }
        }
      }
    }

    // Log::add(1, 'getOwnerID_EOF');
    return $owner_id;
  }

  private static function selectOwnerUserType($code, $module_name) {
    $return = [];

    // SAFTY
    $admin_user_type_id  = Module::getCurrentSetting($code, $module_name, 'admin_user_type_id');
    $vendor_user_type_id = Module::getCurrentSetting($code, $module_name, 'vendor_user_type_id');
    if (!$admin_user_type_id && !$vendor_user_type_id) {
      Log::add(1, "Invalid Module Setting. Module : '{$module_name}({$code})', 'Admin' and 'Owner' doesn't Exists.", 0, 'backend');
      return $return;
    }
    if ($admin_user_type_id) {
      $return[$admin_user_type_id] = $admin_user_type_id;
    }
    if ($vendor_user_type_id) {
      $return[$vendor_user_type_id] = $vendor_user_type_id;
    }

    return ($return);
  }

  private static function isUserHasPermission($code, $module_name) {
    $owner_id = static::getOwnerID($code, $module_name);
    return $owner_id ? 1 : 0;
  }

  public static function countNavModule() {
    //รอทำให้เรียบร้อย
    return [];
  }

  /**
  | Group | MODULE CONFIG
  | Description | MODULE CONFIG
  | $code | โค้ดโมดูล
   */
  public static function getModuleConfig($code) {
    $where = [
      'AND' => [
        'code'          => $code,
        'system_delete' => 0
      ]
    ];
    $sys_function = Amst::get('sys_function', '*', $where);
    if (!isset($sys_function['id'])) {
      return [];
    }
    $jsons = ['json_image_code', 'json_exdata', 'hidden_permission', 'setting_permission'];
    foreach ($jsons as $json) {
      if (isset($sys_function[$json])) {
        $sys_function[$json] = json_decode($sys_function[$json], true);
      }
    }

    $json_keys = [
      'json_list_table',
      'json_tab_default',
      'json_tab_profile',
      'json_tab_addnew',
      'json_tab_account_info',
      'json_tab_permission',
      'json_image_profile'
    ];
    foreach ($json_keys as $key) {
      if (isset($sys_function[$key])) {
        $sys_function[$key] = json_decode($sys_function[$key], true);
      }
    }

    return $sys_function;
  }

  public static function setModuleTabListDetail($return, $options = []) {
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
  | Group | SWAP
  | Description | 'SWAP'
  | $id | ไอดีรายการ
  | $to_id | to_id
   */
  public static function swap($id, $to_id) {
    $from = Amst::getByID('sys_function_tab', '*', $id);
    if (!isset($from['id'])) {
      return Amst::repError('E0000', 'Record not found (1)');
    }
    $to = Amst::getByID('sys_function_tab', '*', $to_id);
    if (!isset($to['id'])) {
      return Amst::repError('E0000', 'Record not found (2)');
    }

    // swap sort_no
    $u_data = [
      'sort_order_id' => $to['sort_order_id']
    ];
    Amst::updateByID('sys_function_tab', $u_data, $id);

    $u_data = [
      'sort_order_id' => $from['sort_order_id']
    ];

    Amst::updateByID('sys_function_tab', $u_data, $to_id);
    return Amst::repSuccess(true);
  }
}
