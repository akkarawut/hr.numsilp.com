<?php
class WLoves {
  private static $conf = [
    'description'             => 'คลาสสำหรับจัดการข้อมูลการตั้งค่าโปรแกรม-เบื้องต้น',
    'fs_dir_path'             => ROOT_PHP . '/resource/setting',
    'ps_file_path'            => ROOT_PHP . '/resource/setting/program_setup.json',
    'default_program_setting' => [ // ตั่งค่า key ทั้งหมดที่ใช้ตรงนี้
      'program_name'                  => '',
      'description'                   => '',
      'email'                         => '',
      'customer'                      => '',
      'theme'                         => 'white',
      'color_theme'                   => '#89B3F7',
      'color_highlight'               => '#89B3F7',
      'color_base_background'         => '#313a41',
      'color_selected_menu'           => '#212f39',
      'color_base_icon'               => '#d8d9da',
      'start_date'                    => '',
      'end_time'                      => '00:00:00',
      'contact_person_name'           => '',
      'contact_person_phone'          => '',
      'contact_person_line'           => '',
      'default_language'              => 'enus',
      'second_language'               => 'enus',
      'default_currency'              => 'thb',
      'support_email'                 => '',
      'module_usermng_postion'        => 'bottom',
      'module_sidebar_mode'           => 'multiple', // single else is multiple
      // SidebarColors
      'color_add_page_btn' => '#676A6F',
      'color_bg_top_bar'              => '#FFFFFF',
      'color_nav_selected'            => '#E3F5FF',
      'color_nav_hover'               => '#ECF3F7',
      'color_nav_font_primary'        => '#202125',
      'color_nav_font_secondary'      => '#676A6F',
      //color topbar
      'dark_color_bg_top_bar'         => '#17171C',
      'dark_color_add_page_btn'       => '#9CA2AC',
      'dark_color_selected_menu'      => '#111117',
      'dark_color_base_icon'          => '#9CA2AC',
      //color nav
      'dark_color_base_background'    => '#17171C',
      'dark_color_nav_selected'       => '#2C3341',
      'dark_color_nav_hover'          => '#111117',
      'dark_color_nav_font_primary'   => '#FFFFFF',
      'dark_color_nav_font_secondary' => '#9CA2AC',

      // new
      'color_border'                  => '#DBDCDF',
      'color_nav_border'              => '#DBDCDF',
      'dark_color_border'             => '#222E45',
      'dark_color_nav_border'         => '#222E45',
    ],
  ];

  /**
   * Get Relative (file)Path Folder for files. *No leading DIRECTORY_SEPARATOR
   *
   * @author TULA
   * @param BOOL $with_tailing with or without tailing DIRECTORY_SEPARATOR
   * @return String
   */
  public static function getSettingPath($with_tailing = false) {
    return 'resource/setting' . ($with_tailing ? DIRECTORY_SEPARATOR : '');
  }

  /**
   * Get Full (file)Path Folder for files.
   *
   * @author TULA
   * @param BOOL $with_tailing with or without tailing DIRECTORY_SEPARATOR
   * @return String
   */
  public static function getSettingFullPath($with_tailing = false) {
    return ROOT_PHP . '/resource/setting' . ($with_tailing ? DIRECTORY_SEPARATOR : '');
  }

  public static function getLinkInfo() {
    // [TODO] show password on $_GET is not secure
    return '?server=' . Config::db_server . '%3A' . Config::db_port . '&username=' . Config::db_username . '&password=' . Config::db_password . '&db=' . Config::db_name;
  }

  /**
  | Group | VERSION
  | Description | เอา version จาก INFO
   */
  public static function getVersion() {
    return Info::version_main . '.' . Info::version_sub . '.' . Info::version_hotfix;
  }

  /**
  | Group | เรียกทุกครั้งที่เริ่มระบบ
  | Description | 1.ตั้ง/อัพเดท ระบบฐานข้อมูล ระบบไฟล์ การตั้งค่าโมดูล
   */
  static function initSystem() {
    $rep = [];

    $rep['upgradeTable'] = DB_Init::upgradeTable();

    $rep['regenerateSQLTable'] = DB_Init::regenerateSQLTable();

    // auto add API KEY
    if (API::countApiKey() == 0) {
      API::insert(['site_name' => 'wloves', 'description' => 'auto generate from system for frontend']);
      API::insert(['site_name' => 'backend', 'description' => 'auto generate from system for backend']);
      API::insert(['site_name' => 'ios', 'description' => 'auto generate from system for iOS']);
      API::insert(['site_name' => 'android', 'description' => 'auto generate from system for android']);
      // FIRST KEYs
      $rep['api_key'] = API::select();
    }

    // FILE
    File::createFolderFile();

    // Language
    if (class_exists('Language')) {
      Language::initSystemLanguage();
    }

    Module::initModule();

    return Amst::repSuccess($rep);
  }

  private static function _selectNavModule() {
    return Module::selectModule();
  }

  /**
  | Group | เรียกทุกครั้งที่เริ่มระบบ
  | Description | 1. โหลดเมนู NAV
   */
  public static function getNavMenu($where = [], $options = []) {
    $return = [];

    $where = [
      'AND'   => [
        'is_show' => 1,
      ],
      'ORDER' => ['sort_order_id'],
    ];
    $selected_records = Func::select('*', $where, ['hide_default_column' => 1]);
    foreach ($selected_records as $row) {
      $return[] = $row;
    }

    return $return;
  }

  /**
  | Group | เรียกทุกครั้งที่เริ่มระบบ
  | Description | 1. โหลดข้อมูล NAV ทั้งหมดของ WLoves
   */
  public static function getInitialData() {
    $return = [];

    // [SECURE] if not login
    $site_name          = API::getSiteName();
    $is_dev             = false;
    $getCurrentUserID   = '';
    $getCurrentUsername = '';
    if ($is_dev = Dev::isLogin()) {
      $getCurrentUserID   = Dev::getCurrentUserID();
      $getCurrentUsername = Dev::getCurrentUsername();
    }

    $is_user         = User::isLogin();
    $current_user    = User::getCurrent();
    $current_user_id = (isset($current_user['id'])) ? $current_user['id'] : 0;

    $program_json = static::getProgramSetup();
    $return       = [
      'program'           => $program_json,
      'ROOT_PHP'          => $is_dev ? ROOT_PHP : '', // [DEBUG]
      'ROOT_URL' => $is_dev ? ROOT_URL : '', // [DEBUG]
      'site_name' => $is_dev ? $site_name : '', // [DEBUG]
      'sidenav' => [
        'check_error' => [],
        'db_link'     => 'NOT FROM API ANYMORE',
        'log'         => [],
      ],
      'permission'        => [
        'module_list' => ($is_dev || $is_user)?static::_selectNavModule() : '',
        'allow_list'  => [],
      ],
      'current_user_data' => [
        'Dev'  => [
          'id'        => $getCurrentUserID,
          'username'  => $getCurrentUsername,
          'user_type' => 'Dev',
          'is_login'  => $is_dev,
          'log'       => 0, // คืออะไร ?? Dev::$cs_names['id'],
          'profile_image' => File::getPlaceHolder(),
        ],
        'User' => [
          'id'           => $current_user_id,
          'username'     => (isset($current_user['username'])) ? $current_user['username'] : '',
          'user_type_id' => (isset($current_user['user_type_id'])) ? $current_user['user_type_id'] : '',
          'user_type'    => (isset($current_user['user_type'])) ? $current_user['user_type'] : '',
          'is_login'     => $is_user,
          'log'          => 0, // คืออะไร User::$cs_names['id'],
          'profile_image' => User::getProfileImage($current_user_id),
        ],
      ],
      'theme'             => isset($program_json['theme']) ? $program_json['theme'] : 'dark',
      'system_version'    => static::getVersion(),
    ];

    // for side nav
    if (User::isLogin()) {
      $return['permission']['allow_list'] = User_Permission::selectPermissionByID($current_user_id);
    }

    // for log
    if ($is_dev) {
      // check error
      $return['sidenav']['check_error'] = [
        'is_exists'   => is_dir(static::$conf['fs_dir_path']),
        'is_writable' => is_writable(static::$conf['fs_dir_path']),
      ];

      // send log
      $logs_count               = Log::countAllType();
      $return['sidenav']['log'] = [
        'sql_log'      => [
          'name'  => 'SQL LOG',
          'type'  => 'sql',
          'count' => $logs_count['sql'],
        ],
        'backend_log'  => [
          'name'  => 'BACKEND LOG',
          'type'  => 'backend',
          'count' => $logs_count['backend'],
        ],
        'frontend_log' => [
          'name'  => 'FRONTEND LOG',
          'type'  => 'frontend',
          'count' => $logs_count['frontend'],
        ],
        'web_log'      => [
          'name'  => 'WEB LOG',
          'type'  => 'unknown',
          'count' => $logs_count['unknown'],
        ],
      ];
    }

    $favicon           = File::getPath('favicon');
    $logo_image        = File::getPath('logo_image');
    $dark_logo_image   = File::getPath('dark_logo_image');
    $dark_logo_image   = File::getPath('dark_logo_image');
    $mobile_logo_image = File::getPath('mobile_logo_image');
    $signin_image      = File::getPath('signin_image');

    $user_permission  = User_Permission::getUserPermissionList($current_user_id);
    $user_mng_postion = Module::getUserMngPostion();

    $user_module = Module::selectUserModule();
    $user_menu   = User_Basic_Setting::selectMenu();

    $general_module = Module::selectGeneralModule();

    $return['favicon']           = $favicon;
    $return['logo_image']        = $logo_image;
    $return['dark_logo_image']   = $dark_logo_image;
    $return['mobile_logo_image'] = $mobile_logo_image;
    $return['signin_image']      = $signin_image;
    $return['user_permission']   = $user_permission;
    $return['user_mng_postion']  = $user_mng_postion;
    $return['user_module']       = $user_module;
    $return['user_menu']         = $user_menu;
    $return['general_module']    = $general_module;

    return $return;
  }

  /**
  | Group | เมนู
  | Description | 1.ดึงข้อมูล เมนูคั้งค่าโปรแกรม (Manage Module)
   */
  public static function getProgramSettingMenu($where = [], $options = []) {
    $return = []; // list

    // Core [20211108]
    // 1.Program Setting
    // 1.1.General Information
    // 1.2.Language
    // 1.3.Theme & Color
    // 1.4.Database Connection
    // 1.5.Alert
    // 1.6.User Type

    // 1.MAP URL-ICON
    // 'general_information' = [
    //   [
    //     'icon' => '',
    //     'url' => '#',
    //   ],
    //   'program_Setting' = [
    //     [
    //       'icon' => '',
    //       'url' => '#',
    //     ],
    // ];
    // // 2.PERMISSION

    // // หล้งบ้านส่งให้
    // // $menu[core][main_menu][sub_menu][general_information][url] = ''
    // $menu = [
    //   [ // CORE
    //     'key' => 'core',
    //     'name' => 'CORE',
    //     'type' => 'main/module',
    //     // 'desccipt' => '',
    //     // 'icon' => '',
    //     'main_menu' => [
    //       'name' => 'SYSTEM',
    //       'sub_menu' = [
    //         [
    //           'key'    => 'program setting',
    //           'name'  => 'General Information',
    //           // 'icon' => '',
    //           // 'url' => '#',
    //           'count' => 0
    //         ],
    //         [
    //           'key'    => 'general_information',
    //           'name'  => 'General Information',
    //           'count' => 0
    //         ]
    //       ];
    //       'name' => 'FOR_DEV',
    //       'list' = [];
    //     ];
    //   ],
    //   // หลังบ้านเรียงลำดับให้
    //   [
    //     'name' => 'MODULE2',
    //     'type' => 'main/module',
    //     'desccipt' => '',
    //     // 'icon' => '',
    //     'main_menu' => [
    //       // 'name' => 'SYSTEM',
    //       // 'sub_menu' = [
    //       //   [
    //       //     'key'    => 'program setting',
    //       //     'name'  => 'General Information',
    //       //     'icon' => '',
    //       //     'url' => '#',
    //       //     'count' => 0
    //       //   ],
    //       //   [
    //       //     'key'    => 'general_information',
    //       //     'name'  => 'General Information',
    //       //     'count' => 0
    //       //   ]
    //       // ];
    //       // 'name' => 'FOR_DEV',
    //       // 'list' = [];
    //     ];
    //   ],
    //   'MODULE3' => [],
    //   'MODULE4' => [],
    //   // กลุ่มบน -- กลุ่มล่าง
    //   'MODULE5' => [],
    //   'MODULE6' => [],
    // ]

    return $return;
  }

  /**
  | Group | General Information
  | Description | 1.ดึงข้อมูลการตั้งค่าระบบเบื้องต้น
   */
  static function getProgramSetup() {
    $return = [];

    if (!file_exists(static::$conf['ps_file_path'])) {
      //File Doesn't Exists!
      $return = static::$conf['default_program_setting'];
    } else {
      // File Exists
      $get_file = file_get_contents(static::$conf['ps_file_path']);
      $return   = json_decode($get_file, true);
    }

    $default_language                  = Language::getSystemDefaultLanguage();
    $return['default_system_language'] = ($default_language) ? $default_language : [];
    $system_languages                  = Language::selectSystemLanguage();
    $return['system_languages']        = ($system_languages) ? $system_languages : [];

    return $return;
  }

  /**
  | Group | General Information
  | Description | 2.บันทึกข้อมูลการตั้งค่าระบบเบื้องต้น
  | $data | [program_name(program_name), module_sidebar_mode(module_sidebar_mode)]
   */
  static function setProgramSetup($data = []) {
    $rep = [
      'update_result' => false,
    ];

    // 'description'            => '',
    // 'email'                  => '',
    // 'customer'               => '',
    // 'theme'                  => 'white',
    // 'color_theme'            => '#89B3F7',
    // 'color_highlight'        => '#89B3F7',
    // 'color_base_background'  => '#313a41',
    // 'color_selected_menu'    => '#212f39',
    // 'color_base_icon'        => '#d8d9da',
    // 'start_date'             => '',
    // 'end_time'               => '00:00:00',
    // 'contact_person_name'    => '',
    // 'contact_person_phone'   => '',
    // 'contact_person_line'    => '',
    // 'default_language'       => 'enus',
    // 'second_language'        => 'enus',
    // 'default_currency'       => 'thb',
    // 'support_email'          => '',
    // 'module_usermng_postion' => 'bottom',

    if (!$data) {
      return Amst::repError('E0001', 'Data');
    }

    // SAVE ALL FIELDs by ANY FIELD.
    $save_data       = [];
    $current_setting = static::getProgramSetup();
    foreach (static::$conf['default_program_setting'] as $key => $def_val) {
      if (isset($data[$key])) {
        $save_data[$key] = $data[$key]; // UPDATE
      } else {
        // RESTORE
        if (isset($current_setting[$key])) {
          $save_data[$key] = $current_setting[$key]; // USE CURRENT
        } else {
          $save_data[$key] = $def_val; // DEF
        }
      }
    }

    if (!is_dir(static::$conf['fs_dir_path']) || !is_writable(static::$conf['fs_dir_path'])) {
      return Amst::repError('no permission to save file');
    }

    if ($save_data) {
      file_put_contents(static::$conf['ps_file_path'], json_encode($save_data));
      chmod(static::$conf['ps_file_path'], 0777);
      // SUCCESS
      $rep['update_result'] = true;
    }

    return Amst::repSuccess($rep);
  }

  static function selectContactList($field = '*') {
    $return = [];

    return Amst::selectNoKey(Info::$system_table['contact_person'], $field, []);
  }

  static function addContactList($name, $phone_number, $line_id) {
    $rep = [];

    if (!$name) {
      return Amst::repError('name is invalid');
    }

    $data = [
      'name'         => $name,
      'phone_number' => $phone_number,
      'line_id'      => $line_id,
    ];

    $rep = Amst::insert(Info::$system_table['contact_person'], $data);

    return Amst::repSuccess($rep);
  }

  static function editContactList($id, $name, $phone_number, $line_id) {
    $rep = [];

    if (!$id || !$name) {
      return Amst::repError('id and name is invalid');
    }

    $data = [
      'name'         => $name,
      'phone_number' => $phone_number,
      'line_id'      => $line_id,
    ];

    $where = ['id' => $id];
    $rep   = Amst::update(Info::$system_table['contact_person'], $data, $where);

    return Amst::repSuccess($rep);
  }

  static function deleteContactList($id) {
    $rep = [];

    if (!$id) {
      return Amst::repError('id is invalid');
    }

    $where = [
      'AND' => [
        'id'            => $id,
        'system_delete' => 0,
      ],
    ];
    $has = Amst::has(Info::$system_table['contact_person'], $where);

    if (!$has) {
      return Amst::repError('dont have contact_person');
    }

    $where = [
      'AND' => [
        'id'            => $id,
        'system_delete' => 0,
      ],
    ];
    $rep = Amst::delete(Info::$system_table['contact_person'], $where);

    return Amst::repSuccess($rep);
  }

  static function setTheme($theme_type) {
    $rep = [];

    // *เลิกใช้ 20211108
    // SC::setSession('wloves_theme', $theme_type);
    // SC::setCookie('wloves_theme', $theme_type);

    return Amst::repSuccess($rep);
  }

  static function countProgramSettingMenu() {
    $return = [
      'language'  => 0,
      'user_type' => 0,
    ];

    // *เลิกใช้ 20211108
    // $return['language'] = Language::countSystemLanguage();
    // $return['user_type'] = User_type::countUserType();

    return $return;
  }

  /**
  | Group | Database Connection
  | Description | 1.โหลดการตั้งค่าการเชื่อมฐานข้อมูล
   */
  static function getDatabaseSetting() {
    $return = [
      'db_type'     => Config::db_type,
      'db_server'   => Config::db_server,
      'db_name'     => Config::db_name,
      'db_username' => Config::db_username,
      'db_password' => Config::db_password, // SAFTY LATER
      'db_port' => Config::db_port,
    ];

    return $return;
  }

  /**
  | Group | Database Connection
  | Description | 1.โหลดการตั้งค่าการเชื่อมฐานข้อมูล
   */
  static function getFrontEndVar() {
    $return = [
      'F_WLOVES_VERSION' => defined('F_WLOVES_VERSION') ? F_WLOVES_VERSION : 'not-defined',
      'F_BRIDGE_API_URL' => defined('F_BRIDGE_API_URL') ? F_BRIDGE_API_URL : 'not-defined',
      'F_BRIDGE_API_KEY' => defined('F_BRIDGE_API_KEY') ? F_BRIDGE_API_KEY : 'not-defined',
      'F_SITE_NAME'      => defined('F_SITE_NAME') ? F_SITE_NAME : 'not-defined',
    ];
    return $return;
  }

  /**
  | Group | Database Connection
  | Description | 2.บันทึกการตั้งค่าการเชื่อมฐานข้อมูล
   */
  static function setDatabaseSetting($data) {
    $rep = [
      'update_result' => false,
    ];

    // [TODO] ยังไม่มีวิธีอัพเดท

    return Amst::repSuccess($rep);
  }
}
