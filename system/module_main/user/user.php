<?php
class User {
  private static $conf = [
    'session_key' => [
      'id'   => '_user_id',
      'hash' => '_user_hash'
    ]
  ];
  private static $supported_social_types = ['facebook', 'google', 'apple'];
  // Cache
  private static $_current_user = false;

  /**
   * GET full_name from any name or surname
   *
   * @author TULA
   * @param ARRAY $data any INSERT or UPDATE data
   * @return STRING Full_name
   */
  private static function _getFullName($data) {
    $full_name = '';
    if (!isset($data['full_name'])) {
      if (isset($data['name']) && $data['name']) {
        if (isset($data['surname']) && $data['surname']) {
          $full_name = "{$data['name']} {$data['surname']}";
        } else {
          $full_name = $data['name'];
        }
      } else {
        if (isset($data['surname']) && $data['surname']) {
          $full_name = $data['surname'];
        }
      }
    } else {
      $full_name = $data['full_name'];
    }
    return $full_name;
  }

  /**
  | Group | CHECK LOGIN FAIL
  | Description | CHECK LOGIN FAIL
  | $username | username
   */
  public static function checkLoginFailCount($username) {
    $username      = trim($username);
    $from_datetime = date('Y-m-d H:i:s', strtotime('-2 hours'));
    $to_datetime   = date('Y-m-d H:i:s');

    $where = [
      'AND' => [
        'input_username'       => $username,
        'type'                 => 'LOGIN_INVALID_PASSWORD',
        'insert_date_time[<>]' => [
          $from_datetime,
          $to_datetime
        ],
        'system_delete'        => 0
      ]
    ];
    $count = Amst::count('sys_user_log', $where);
    return $count;
  }

  /**
  | Group | Configuration
  | Description | 1.getIdentityField, Default is username
  | $code | โค้ดโมดูล
   */
  public static function getCurrentSetting($code) {
    $return = [];
    return Module::getCurrentSetting($code, 'User');
  }

  /**
  | Group | Configuration
  | Description | 1.getIdentityField, Default is username
  | $code | โค้ดโมดูล
   */
  public static function getIdentityField($code) {
    $return       = 'username';
    $user_type_id = Module::getCurrentSetting($code, 'User', 'managed_user_type_id');
    // $return       = User_type::getIdentityField($user_type_id);
    return $return;
  }

  /**
  | Group | Social
  | Description | ตรวจสอบว่าสมัครสมาชิกรึยัง
   */
  public static function verifyCredentialExist($credential_type, $credential_value) {
    $rep = [
      'credential_type'   => $credential_type,
      'credential_column' => '',
      'credential_value'  => $credential_value,
      'is_register'       => false,
      'user'              => []
    ];

    switch ($credential_type) {
      case "facebook":
        $rep['credential_column'] = 'facebook_id';
        break;
      case "google":
        $rep['credential_column'] = 'google_id';
        break;
      case "apple":
        $rep['credential_column'] = 'apple_id';
        break;
      case "username":
        $rep['credential_column'] = 'username';
        break;
      case "tel":
        $rep['credential_column'] = 'username';
        break;
      case "email":
        $rep['credential_column'] = 'username';
        break;
      default:
        return Amst::repError('Invalid input data');
    }

    $where = [
      'AND' => [
        'system_delete' => 0
      ]
    ];
    if (in_array($credential_type, static::$supported_social_types)) {
      $where['AND'][$credential_type . '_id'] = $credential_value;
    } else {
      $where['AND'][$credential_type] = $credential_value;
    }

    $user = Amst::get('sys_user', '*', $where);
    if ($user) {
      $rep['is_register'] = true;
      $rep['user']        = $user;
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Social
  | Description | ลงทะเบียนผู้ใช้ใหม่เข้าสู่ระบบ ผ่าน SNS
  | $code | โค้ดโมดูล
  | $social_id | UID คือค่า unique ID ของ facebook, google, apple
  | $social_type | ประเภท SNS ได้แก่ facebook, google, apple
  | $additions_data | ARRAY ข้อมูลอื่นๆที่ต้องการบันทึกลง User
   */
  public static function connectSocial($code, $social_uid, $social_type, $additions_data = []) {
    $rep = [];

    if (!in_array($social_type, static::$supported_social_types)) {
      return Amst::repError('Social type is not Support');
    }

    if (!$social_uid) {
      return Amst::repError('social_uid cannot be null');
    }

    if (isset($additions_data['user_type_id'])) {
      $managed_user_type_id = $additions_data['user_type_id'];
    } else {
      $managed_user_type_id = Module::getCurrentSetting($code, 'User', 'managed_user_type_id');
    }
    if (!isset($managed_user_type_id) || !$managed_user_type_id) {
      return Amst::repError('Invalid User Type ID');
    }

    $username = "{$social_type}{$social_uid}";
    $password = "{$social_type}_{$social_uid}_";
    $where    = [
      "{$social_type}_id" => $social_uid,
      "username"          => $username
    ];
    $model             = Amst::get('sys_user', ['id'], $where);
    $rep['modelWhere'] = $where;
    $rep['model']      = $model;
    if (!isset($model['id'])) {
      $data = [
        'username'          => $username,
        'password'          => $password,
        'user_type_id'      => $managed_user_type_id,
        'from_platform'     => $social_type,
        'social_connect'    => true,
        "{$social_type}_id" => $social_uid
      ];
      $rep['addNewUserData'] = $data;
      $rep['addNewUser']     = static::addNewUser($code, $data);
    }

    return static::login($username, ($password));
  }

  private static function __KEEP__selectWithPermission($columns = '*', $where = [], $options = []) {
    $return = [];

    $return      = Amst::select('sys_user', $columns, $where, $options);
    $where       = ['ORDER' => ['permission' => 'ASC']];
    $permissions = Amst::select(Info::$system_table['user_permission'], '*', $where);

    foreach ($return as $key => $user) {
      $return[$key]['permission'] = [];

      foreach ($permissions as $permission) {
        if ($permission['user_id'] == $user['id']) {
          $code            = explode('_', $permission['permission']);
          $permission_word = str_replace($code[0] . '_' . $code[1], '', $permission['permission']);
          if ($permission_word) {
            if ('_' == $permission_word[0]) {
              $permission_word = substr($permission_word, 1);
            }
          }

          if (!isset($return[$key]['permission'][$code[0]])) {
            $return[$key]['permission'][$code[0]] = '<b>[' . $code[0] . ']</b> ';
          } else {
            $return[$key]['permission'][$code[0]] .= ' | <small>' . $permission_word . '</small> ';
          }
        }
      }
    }

    return $return;
  }

  /**
  | Group | Login/Logout
  | Description | 1.Login ผู้ใช้เข้าสู่ระบบ จะเช็ค Dev ให้ด้วย
  | $input | indentity ข้อมูล username / เบอร์โทร / อีเมล
  | $input_password | รหัสผ่าน
  | $ex_data | [remember_me(จดจำลงcookie), site_name(กำหนด site_name)]
   */
  public static function login($input, $input_password = '', $ex_data = []) {
    $rep = []; // User Info

    $remember_me = isset($ex_data['remember_me']) ? $ex_data['remember_me'] : false;
    $site_name   = isset($ex_data['site_name']) ? $ex_data['site_name'] : API::getSiteName();
    // not working as asp $site_name = API::getSiteName();

    // INPUT as ARRAY
    if (isset($input['username']) && isset($input['password'])) {
      // check if user
      $username = trim(strtolower($input['username']));
      if ($username && $input['password']) {
        $password = md5($input['password']); // [LATER] Change by Info
        $where    = ["username" => $username];
      }
    } else if (isset($input['email']) && isset($input['password'])) {
      // check if user
      $email    = strtolower($input['email']);
      $password = md5($input['password']); // [LATER] Change by Info
      $where    = ["email" => $email];
    } else if (isset($input['tel_no']) && isset($input['password'])) {
      // check if user
      $tel      = strtolower($input['tel_no']);
      $password = md5($input['password']); // [LATER] Change by Info
      $where    = ["tel" => $tel];
      if (isset($input['tel_country_code'])) {
        $where["tel_country_code"] = $input['tel_country_code'];
      }
    } else {
      // Normal Login
      if (!$input || !$input_password) {
        return Amst::repError('Invalid username or passwords [User]');
      }
      // initial data
      $username = strtolower($input);
      if (!isset($ex_data['encoded_password']) || !$ex_data['encoded_password']) {
        $password = md5($input_password); // [LATER] Change by Info
      } else {
        $password = $input_password;
      }
      // [DEV] TRY-check if dev
      if (defined('Config::allow_dev_login')) {
        if (Config::allow_dev_login === 'SUPER_DEV_ONLY') {
          $api = Dev::login($username, $input_password, ['user_type' => 'SUPER_DEV_ONLY']);
          if (isset($api['response_status']) && ($api['response_status'] == true)) {
            return $api;
          }
        } else if (Config::allow_dev_login === true) {
          $api = Dev::login($username, $input_password, ['try' => true]);
          if (isset($api['response_status']) && ($api['response_status'] == true)) {
            return $api;
          }
        }
      }
      // check if user
      $where = ["username" => $username];
    }

    //CHECK MAX LOGIN FAIL
    // $max_fail   = 10;
    // $fail_count = static::checkLoginFailCount($username);
    // if ($fail_count >= $max_fail) {
    //   return Amst::repError('Too many login attempts. Please try again later.');
    // }

    if (isset($where) && $where) {
      $user = Amst::get('sys_user', '*', $where);
    }
    if (!isset($user['id'])) {
      $i_log = [
        'user_id'        => 0,
        'input_username' => $username,
        'input_password' => '', // ห้ามเก็บรหัสผ่าน plaintext ลง log (PDPA)
        'type'           => 'LOGIN_USER_NOT_FOUND',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('Invalid username or password');
    }

    //USER TYPE
    $user_code = $user['user_code'];
    $where     = [
      'AND' => [
        'code'          => $user_code,
        'system_delete' => 0
      ]
    ];
    $sys_func = Amst::get('sys_function', '*', $where);
    if (!isset($sys_func['id'])) {
      return Amst::repError('User type not found');
    }
    $allow_login = $sys_func['allow_login'];
    if (!$allow_login) {
      return Amst::repError('User type not allow login');
    }

    // [USER_DATA]
    if (!$password || ($password != $user['password'])) {
      $i_log = [
        'user_id'        => $user['id'],
        'input_username' => $username,
        'input_password' => '', // ห้ามเก็บรหัสผ่าน plaintext ลง log (PDPA)
        'type'           => 'LOGIN_INVALID_PASSWORD',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('Invalid username or password');
    } else if ($user['system_delete'] || $user['is_ban']) {
      $i_log = [
        'user_id'        => 0,
        'input_username' => $username,
        'input_password' => '', // ห้ามเก็บรหัสผ่าน plaintext ลง log (PDPA)
        'type'           => 'LOGIN_USER_BANNED',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('ไม่สามารถเข้าสู่ระบบได้');
    } else if ($user['system_delete'] || $user['is_can_login'] == 0) {
      $i_log = [
        'user_id'        => 0,
        'input_username' => $username,
        'input_password' => '', // ห้ามเก็บรหัสผ่าน plaintext ลง log (PDPA)
        'type'           => 'LOGIN_USER_CANT_LOGIN',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('ไม่สามารถเข้าสู่ระบบได้');
    }
    // login successful
    SC::setSession($site_name . static::$conf['session_key']['id'], $user['id']);
    SC::setSession($site_name . static::$conf['session_key']['hash'], $user['password']);

    if ($remember_me) {
      SC::setCookie($site_name . static::$conf['session_key']['id'], $user['id']);
      SC::setCookie($site_name . static::$conf['session_key']['hash'], $user['password']);
    }

    $i_log = [
      'user_id'        => $user['id'],
      'input_username' => $username,
      'input_password' => '',
      'type'           => 'LOGIN_SUCCESS',
      'sub_type'       => ''
    ];
    User_Log::addLoginLog($i_log);

    if (array_key_exists('last_online_date_time', $user)) {
      $last_online_date_time         = Util::getSystemTimestamp(); // ME NOW !
      $rep['update_result']          = Amst::updateByID('sys_user', ['last_online_date_time' => $last_online_date_time], $user['id'], ['direct' => true]);
      $user['last_online_date_time'] = $last_online_date_time;
    }

    $rep = $user;
    if (isset($rep['password'])) {
      unset($rep['password']);
    }
    if (isset($ex_data['log']) && $ex_data['log']) {
      $debug = [
        'site_name'       => $site_name,
        'rep'             => $rep,
        'getCurrent'      => static::getCurrent(),
        'getRenewCurrent' => static::getRenewCurrent()
      ];
      Log::logBackendShouldKnow('debug.login', $debug);
    }

    return Amst::repSuccess($rep);
  }

  private static function _logout($options = []) {
    $return                = true; // alway return true
    static::$_current_user = null;
    $site_name             = isset($options['site_name']) ? $options['site_name'] : API::getSiteName();
    foreach (static::$conf['session_key'] as $cs_name) {
      SC::remove($site_name . $cs_name);
    }
    // DO NOT REP, will cause DEATHLOCK
    return $return;
  }

  /**
  | Group | Login/Logout
  | Description | 2.ออกจากระบบ เคลียร์ SC ทั้งหมด
   */
  public static function logout($options = []) {
    $rep = [
      'update_result' => static::_logout($options)
    ];
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Current User
  | Description | 1.ดึง ID ของ User ใหม่ล่าสุด *เพราะ getCurrent จะดึงข้อมูลครั้งเดียว
  | $options | [site_name(กำหนด site_name)]
   */
  public static function getRenewCurrent($options = []) {
    $return = null;

    $site_name = isset($options['site_name']) ? $options['site_name'] : API::getSiteName();
    $id        = SC::get($site_name . static::$conf['session_key']['id']);
    $hash      = SC::get($site_name . static::$conf['session_key']['hash']);

    if (is_null($id) || is_null($hash)) {
      static::logout($site_name);
      return $return;
    }

    // GET NEW
    static::$_current_user = Amst::getByID('sys_user', '*', $id);
    if (isset(static::$_current_user['id'])) {
      // CHECH BAN
      if ((static::$_current_user['is_ban'] == 1) || (static::$_current_user['system_delete'] == 1) || (static::$_current_user['password'] != $hash)) {
        static::logout($site_name);
        return $return;
      }
      $return = static::$_current_user;
    }

    return $return;
  }

  /**
  | Group | Current User
  | Description | 1.ดึง ID ของ User ที่กำลัง Login อยู่ในขณะนี้ *ดึงข้อมูลครั้งเดียว ต่อ 1 APICall เพื่อลด conn
  | $options | [unread_noti_count(จำนวน noti รออ่าน), img_path(พร้อมรูป), site_name(กำหนด site_name), user_social]
   */
  public static function getCurrent($options = []) {
    $return = null;

    $site_name = isset($options['site_name']) ? $options['site_name'] : API::getSiteName();

    // GET ONCE = [BUG][TODO]
    // if (static::$_current_user === false) {
    if (!static::$_current_user) {
      $id   = SC::get($site_name . static::$conf['session_key']['id']);
      $hash = SC::get($site_name . static::$conf['session_key']['hash']);
      if (is_null($id) || is_null($hash)) {
        return $return;
      }
      static::$_current_user = Amst::getByID('sys_user', '*', $id);
      // [TEAM]
      if (isset(static::$_current_user['team_id'])) {
        if (static::$_current_user['team_id']) {
          $team = User_Basic_Setting::getTeamByID(static::$_current_user['team_id']);
        }
        static::$_current_user['team_name'] = isset($team['name']) ? $team['name'] : '';
      }
    }

    if (isset(static::$_current_user['id'])) {
      // [ONLINE]
      // if (array_key_exists('last_online_date_time', static::$_current_user)) {
      //   $last_online_date_time = Util::getSystemTimestamp(); // ME NOW !
      //   $rep['update_result'] = Amst::updateByID('sys_user', ['last_online_date_time' => $last_online_date_time], $user['id'], ['direct' => true]);
      //   Log::add(0, Amst::lastQuery(), 0, 'backend');
      //   $user['last_online_date_time'] = $last_online_date_time;
      // }
      // CHECK [BAN]  || (static::$_current_user['password'] != $hash)
      if ((static::$_current_user['is_ban'] == 1) || (static::$_current_user['system_delete'] == 1)) {
        static::logout($site_name);
        return $return;
      }
      $return = static::$_current_user;

      if (isset($return['permission'])) {
        $return['permission'] = json_decode($return['permission'], true);
      }

      if (Util::isHasOptions('img_path', $options)) {
        $return['img_path'] = static::getProfileImage($return['id']); // [LATER] CACHE
      }
      if (Util::isHasOptions('unread_noti_count', $options)) {
        $return['unread_noti_count'] = Amst::count('sys_user_notification', ['user_id' => static::$_current_user['id'], 'is_read' => 0]);
      }
    } else {
      static::logout($site_name);
      return $return;
    }

    if (isset($options['user_social'])) {
      $user_social           = User_Social::getUserSocial($return['id']);
      $return['user_social'] = $user_social;
    }

    return $return;
  }

  /**
  | Group | Current User
  | Description | 2.ดึง ID ของ User ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentUserID() {
    $return       = 0;
    $current_user = static::getCurrent();
    if (isset($current_user['id'])) {
      $return = $current_user['id'];
    }
    return $return;
  }

  /**
  | Group | Current User
  | Description | 3.ดึง Username ของ User ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentUsername() {
    $return       = '';
    $current_user = static::getCurrent();
    if (isset($current_user['username'])) {
      $return = $current_user['username'];
    }
    return $return;
  }

  /**
  | Group | Current User
  | Description | 4.ดึง user_type ของ User ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentUserType() {
    $return       = '';
    $current_user = static::getCurrent();
    if (isset($current_user['user_type'])) {
      $return = $current_user['user_type'];
    }
    return $return;
  }

  /**
  | Group | Current User
  | Description | 5.ดึง user_type ของ User ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentLanguage() {
    $return       = 'enus';
    $current_user = static::getCurrent();
    if (isset($current_user['language_code'])) {
      $return = $current_user['language_code'];
    }
    return $return;
  }

  /**
  | Group | Current User
  | Description | 6.ดึงข้อมูลสถานะ online / offline
   */
  public static function getOnlineStatus($user_model = []) {
    $return = 'offline';
    if (!$user_model) {
      $user_model = static::getCurrent();
    }
    // SUPPORT 2 fields
    if (isset($user_model['last_user_online_date_time'])) {
      $t1 = strtotime($user_model['last_user_online_date_time']);
      $t2 = strtotime('-5 minute');
      if ($t1 > $t2) {
        $return = 'online';
      } else {
        $return = 'offline';
      }
    } else if (isset($user_model['last_online_date_time'])) {
      $t1 = strtotime($user_model['last_online_date_time']);
      $t2 = strtotime('-5 minute');
      if ($t1 > $t2) {
        $return = 'online';
      } else {
        $return = 'offline';
      }
    }
    return $return;
  }

  /**
  | Group | Current User
  | Description | 7.เช็คว่าตอนนี้ เครื่อง Client Login เข้าสู่ระบบหรือเปล่า
   */
  public static function isLogin() {
    $return       = 0;
    $current_user = static::getCurrent();
    if (isset($current_user['id'])) {
      $return = 1;
    }
    return $return;
  }

  /**
  | Group | Vendor
  | Description | 0. เมื่อ Admin หรือ Vender จัดการลิสของตัวเอง จะเห็นหมด
  | $code | โค้ดโมดูล
   */
  private static function _isManageMyType($code) {
    $return = 0;

    $c_conf = Module::getCurrentSetting($code, 'User');

    // SAFTY
    if (!isset($c_conf['managed_user_type_id'])) {
      // Log::add(1, "Invalid Module Setting. Module name : 'User', 'managed_user_type_id' Exists.");
      return $return;
    }
    // NOT_SET == NOT SEE
    if (!$c_conf['managed_user_type_id']) {
      return $return;
    }

    $admin_user_type_id  = (isset($c_conf['admin_user_type_id'])) ? $c_conf['admin_user_type_id'] : 0;
    $vendor_user_type_id = (isset($c_conf['vendor_user_type_id'])) ? $c_conf['vendor_user_type_id'] : 0;

    return (($c_conf['managed_user_type_id'] == $admin_user_type_id) || ($c_conf['managed_user_type_id'] == $vendor_user_type_id));
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 1.นับจำนวนข้อมูลลิส 'ผู้ใช้งาน' (total_count)
  | $code | โค้ดโมดูล *ใช้ในการดึง user_type_id ที่ตั้งค่าไว้ ถ้าไม่ส่ง user_type_id ต้องส่ง CODE มาด้วย
  | $where | [search_text(กรองตามชื่อ), user_type_id(กรองตามไอดีประเภท)]
   */
  public static function countUser($code = '', $where = [], $options = []) {
    $return = 0; // COUNT

    if ($code) {
      $sql_where['AND']['user_code'] = $code;
    }

    $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_user' : 'sys_user';
    return Amst::count($table_name, $sql_where);
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 1.ดึงข้อมูลลิส 'ผู้ใช้งาน'
  | $code | โค้ดโมดูล *ใช้ในการดึง user_type_id ที่ตั้งค่าไว้ ถ้าไม่ส่ง user_type_id ต้องส่ง CODE มาด้วย
  | $where | [search_text(กรองชื่อผู้ใช้งาน), user_type_id(กรองไอดีประเภทผู้ใช้), full_name(full_name), email(email)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย)]
   */
  public static function selectUser($code = '', $where = [], $options = []) {
    $return    = []; // user_list
    $sql_where = [];

    if ($code) {
      $sql_where['AND']['user_code'] = $code;
    }

    if (isset($where['user_type']) && $where['user_type'] && $where['user_type'] != 'all') {
      $sql_where['AND']['user_type'] = $where['user_type'];
    }

    $table_name      = !Util::isHasOptions('not_view', $options) ? 'sys_user' : 'sys_user';
    $selected_fields = Amst::getSelectedFields($options, ['id', 'username', 'insert_date_time']);
    $return          = Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);

    $selected_records = isset($return['list']) ? $return['list'] : $return;
    foreach ($selected_records as $key => $user) {
      $selected_records[$key]['user_profile_image'] = User::getProfileImage($user['id']);
      $selected_records[$key]['permission']         = json_decode($user['permission'], true);
    }
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  public static function setUserDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }
    $record['permission']         = json_decode($record['permission'], true);
    $record['user_profile_image'] = User::getProfileImage($record['id']);
    return $record;
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 2.ดึงข้อมูล 'ผู้ใช้งาน' ด้วยไอดี *ไม่ต้องใส่ code เพราะมันไปรวมที่เดียวกัน
  | $id | ไอดีผู้ใช้
  | $options | [img_path(ขอรูปผู้ใช้ด้วย)]
   */
  public static function getUserByID($id, $options = []) {
    $return = null;

    // $table_name = !Util::isHasOptions('not_view', $options) ? 'sys_view_user_detail' : 'sys_user';
    $table_name      = 'sys_user';
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    // $uid = filter_var($id, FILTER_VALIDATE_INT); // -1,0,1 is OK
    if ($id) {
      $options = Amst::checkOption($options);
      if (Util::isHasOptions('is_exists', $options)) {
        $table_name      = 'sys_user';
        $selected_fields = Util::getOptionsValue('selected_fields', $options, ['id', 'language_code']);
        return Amst::getByID($table_name, $selected_fields, $id);
      }
      if (Util::isHasOptions('img_path', $options)) {
        $options['image']['user_profile_image'] = [
          'code'          => 'user_profile_image_',
          'target_column' => 'id'
        ];
        $options['image']['user_cover_image'] = [
          'code'          => 'user_cover_image_',
          'target_column' => 'id'
        ];
      }
      $model = Amst::getByID($table_name, $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setUserDetail($model, $options);
      }
    } else {
      // [20220509] remove of Zero
      // Log::logBackendShouldKnow('Invalid ID', ['id' => $id, 'options' => $options]);
    }
    return $return;
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 2.ดึงข้อมูล 'ผู้ใช้งาน' คนแรกในโมดูลนี้
  | $code | โค้ดโมดูล
   */
  public static function getFirstUser($code) {
    $return = null;

    $options = [
      'page_no'   => 1,
      'page_size' => 1
      // 'debug' => 1
    ];
    $selected_records = static::selectUser($code, ['owner_id' => -1], $options);
    foreach ($selected_records as $row) {
      $model = $row;
    }

    if (isset($model['id'])) {
      $return = static::setUserDetail($model, $options);
    }
    return $return;
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 2.ดึงข้อมูล 'ผู้ใช้งาน' ด้วย Username *โมบายใช้ดึงไโชวก่อนให้กรอกรหัสผ่าน
  | $username | ชื่อผู้ใช้
  | $options | [img_path(พร้อมรูป)]
   */
  public static function getUserByUsername($username, $options = []) {
    $return = null;

    if ($username) {
      $username = trim(strtolower($username));
    }

    if ($username) {
      $model = Amst::get('sys_user', ['id', 'username', 'full_name'], ['username' => $username]);
      if (isset($model['id'])) {
        if (Util::isHasOptions('img_path', $options)) {
          $model['img_path'] = static::getProfileImage($model['id']);
        }
        $return = $model;
      }
    }
    return $return;
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 2.เช็คข้อมูล username ว่ามีซ้ำในระบบไหม 1=มีแล้ว 0=ยังว่าง
  | $username | ชื่อผู้ใช้
   */
  public static function isUsernameExists($username) {
    $return = 1; // DEF มี

    if ($username) {
      $username = trim(strtolower($username));
    }

    if ($username) {
      if (Amst::has('sys_user', ['username' => $username])) {
        $return = 1;
      } else {
        $return = 0;
      }
    }
    return $return;
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 3.เพิ่ม 'ผู้ใช้งาน'
  | $code | โค้ดโมดูล *ใช้ในการดึง user_type_id ที่ตั้งค่าไว้ ถ้าไม่ส่ง user_type_id ต้องส่ง CODE มาด้วย
  | $data | [username(ชื่อผู้ใช้), password(รหัสผ่าน), user_type_id(ไอดีประเภทผู้ใช้), full_name(full_name), email(email), tel_no(tel_no)]
   */
  public static function addNewUser($code = '', $data = [], $file = []) {
    $rep = [
      'insert_id' => false
    ];

    $data      = Amst::checkOption($data);
    $m_setting = Module::getCurrentSetting($code, 'User');

    // Username
    if (isset($data['username'])) {
      $data['username'] = trim(strtolower($data['username']));
    } else {
      return Amst::repError('E0000', 'Username not found');
    }

    // [Filter] Allow only a-z 0-9
    if (isset($m_setting['username_filter']) && $m_setting['username_filter']) {
      if (!preg_match($m_setting['username_filter'], $data['username'])) {
        return Amst::repError('E0000', 'Invalid letter in Username');
      }
    }
    // [DEV] check if username same as Dev
    if (isset($m_setting['username_reserve_endswith']) && $m_setting['username_reserve_endswith']) {
      if (Util::endsWith($data['username'], $m_setting['username_reserve_endswith'])) {
        return Amst::repError('E0000', 'This Username is reserved');
      }
    }

    // [UNIQUE]
    $unique_model = Amst::get('sys_user', ['id'], ['username' => $data['username']]);
    if (isset($unique_model['id'])) {
      return Amst::repError('E0000', 'This username is already in use');
    }

    // Password
    if (!isset($data['password']) || !$data['password']) {
      return Amst::repError('E0001', 'Password');
    }
    $data['password'] = md5($data['password']);

    // [USER_TYPE]
    $module_user = Module::getCurrentSetting($code, 'User');
    if (isset($module_user['id'])) {
      $data['user_code'] = $module_user['code'];
      $data['user_type'] = $module_user['name'];
      if (!isset($data['permission']) || !$data['permission']) {
        $data['permission'] = json_encode($module_user['setting_permission']);
      } else {
        $data['permission'] = json_encode($data['permission']);
      }
    }

    $api = Amst::insert('sys_user', $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];

      if ($file) {
        User::setProfileImage($insert_id, $file);
      }

      if (isset($data['verify_status']) && ($data['verify_status'] == 'verified')) {
        $n_log_data = [
          'admin_id' => User::getCurrentUserID(),
          'user_id'  => $insert_id,
          'type'     => 'verify_status',
          'status'   => 'verified'
        ];
        $rep['log_id'] = Amst::insert('sys_user_log', $n_log_data);
      }
      // [SUCCESS]
      Log::addSuccessLog($code, __CLASS__, __FUNCTION__, 1, 'CHECKLIST', 'After insert', $rep);
      $rep['insert_id'] = $insert_id;
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | เปิดการใช้งานด้วยอีเมล
  | Description | 4.get confirm link
  | $user_id | ไอดีผู้ใช้
   */
  public static function getUserActivateEmail($user_id) {
    $return = [
      'user_id'     => $user_id,
      'active_link' => ''
    ];

    $where = [
      'login_status' => 'wait_email_activated',
      'id'           => $user_id
    ];
    $model = Amst::get('sys_user', ['id', 'email_activate_code'], $where, ['show_all' => true]);
    if (isset($model['id'])) {
      $return['active_link'] = Util::site_url() . "frontend/activate_page.php?user_id={$model['id']}&email_activate_code={$model['email_activate_code']}";
    }
    return $return;
  }

  /**
  | Group | เปิดการใช้งานด้วยอีเมล
  | Description | 4.เปิดใช้งาน 'ผู้ใช้งาน'
  | $user_id | ไอดีผู้ใช้
  | $email_activate_code | โค้ดจากอีเมลเพื่่อเปิดใช้งาน
   */
  public static function activateUserByEmail($user_id, $email_activate_code) {
    $rep = [
      'update_result' => false
    ];
    $where = [
      'login_status'        => 'wait_email_activated',
      'email_activate_code' => $email_activate_code,
      'id'                  => $user_id
    ];
    $model = Amst::get('sys_user', ['id', 'email_activate_code'], $where, ['show_all' => true]);
    if (isset($model['id'])) {
      $u_data = [
        'login_status'  => 'activated',
        'system_delete' => '0'
      ];
      $rep['update_result'] = Amst::updateByID('sys_user', $u_data, $user_id, ['direct' => true]);
    }
    return Amst::repSuccess($rep);
  }

  private static function _setUserActivateEmail($user_id) {
    $rep = [
      'update_result' => false
    ];
    $u_data = [
      'login_status'        => 'wait_email_activated',
      'email_activate_code' => md5(time() . 'salt-asdasd-q123123' . rand(100, 200)),
      'system_delete'       => '1'
    ];
    $rep['update_result'] = Amst::updateByID('sys_user', $u_data, $user_id, ['direct' => true]);
    return Amst::repSuccess($rep);
  }

  private static function _addNewUser($code, $data) {
    $return = false; // insert_id

    if (isset($data['username'])) {
      $data['username'] = trim(strtolower($data['username']));
      if (isset($data['password'])) {
        $data['password'] = $data['password'];
      } else {
        return Amst::repError('E0000', 'Password not set');
      }
    } else {
      $new_username = md5(date('Y-m-d H:i:s') . rand(1, 10000) . 'add some salt naja');
      $has          = Amst::has('sys_user', ['username' => $new_username]);
      if ($has) {
        $has_new = Amst::has('sys_user', ['username' => $new_username]);
        while ($has_new) {
          $new_username = md5(date('Y-m-d H:i:s') . rand(1, 10000) . 'add some salt naja');
          $has_new      = Amst::has('sys_user', ['username' => $new_username]);
        }
      }
      $data['username']        = $new_username;
      $data['password']        = $new_username;
      $data['is_gen_username'] = 1;
    }

    // [FULLNAME]
    if ($full_name = static::_getFullName($data)) {
      $data['full_name'] = $full_name;
    }

    // [STATUS]
    $data['login_status'] = 'activated'; // DEF

    return Amst::insert('sys_user', $data);
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 4.อัพเดท 'ผู้ใช้งาน'
  | $code | โค้ดโมดูล *ใช้ในการดึง user_type_id ที่ตั้งค่าไว้ ถ้าไม่ส่ง user_type_id ต้องส่ง CODE มาด้วย
  | $data | [password(เปลี่ยนรหัสผ่าน), name(ชื่อ), sur_name(ชื่อ)]
   */
  public static function updateUser($id, $data = []) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID('sys_user', '*', $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0000', 'User not found');
    }
    $data = Amst::checkOption($data);
    if ($full_name = static::_getFullName($data)) {
      $data['full_name'] = $full_name;
    }

    if (isset($data['permission'])) {
      $data['permission'] = json_encode($data['permission']);
    }

    unset($data['username']);
    unset($data['password']);
    if ($data) {
      $api = Amst::updateByID('sys_user', $data, $id);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $rep['update_result'] = $api['response_data']['update_result'];
      } else {
        return $api;
      }
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | ผู้ใช้งาน
  | Description | 5.ลบ 'ผู้ใช้งาน'
  | $user_id | ไอดีผู้ใช้
  | $ex_data | [username(กรอกชื่อผู้ใช้เพื่อยืนยันการลบ)]
   */
  public static function deleteUser($user_id, $ex_data = []) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteUser($user_id, $ex_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      if (isset($api['response_data']['username']) && $api['response_data']['username']) {
        // update
        $new_username = $api['response_data']['username'] . '_del_t' . time();
        $has          = Amst::has('sys_user', ['username' => $new_username]);
        if ($has) {
          $i       = 1;
          $has_new = Amst::has('sys_user', ['username' => $new_username]);
          while ($has_new) {
            $new_username = $api['response_data']['username'] . '_del_t' . time() . '.' . $i++;
            $has_new      = Amst::has('sys_user', ['username' => $new_username]);
          }
        }
        $u_data = [
          'username'      => $new_username,
          'system_delete' => 1
        ];
        $rep['delete_result'] = Amst::updateByID('sys_user', $u_data, $user_id, ['direct' => true]);
      } else {
        $rep['delete_result'] = Amst::deleteByID('sys_user', $user_id);
      }
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteUser($user_id, $ex_data = []) {
    $rep = [
      'id'       => $user_id,
      'username' => ''
    ];

    // [Model_Exist]
    $model = Amst::getByID('sys_user', ['id', 'username'], $user_id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'tb_user');
    }

    $rep['username'] = $model['username'];

    // Confirm by input username
    if (isset($ex_data['username'])) {
      if ("{$ex_data['username']}" !== "{$model['username']}") {
        return Amst::repError('E0003', 'Invalid Username');
      }
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Password
  | Description | 1.เช็ครหัสผ่านเก่าก่อน
  | $user_id | ไอดีผู้ใช้งาน
  | $current_password | รหัสผ่านเก่า
   */
  public static function checkCurrentPassword($user_id, $current_password) {
    $rep = [
      'user_id' => $user_id
    ];

    $model_user = static::getUserByID($user_id, ['selected_fields' => ['id', 'password']]);
    if (!isset($model_user['id'])) {
      return Amst::repError('E0005', 'User');
    }
    if ($model_user['password'] != md5($current_password)) {
      return Amst::repError('E0000', 'Wrong password');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Password
  | Description | 1.เปลี่ยนรหัสผ่าน ที่จะต้องเช็ครหัสผ่านเก่าก่อน
  | $user_id | ไอดีผู้ใช้งาน
  | $current_password | รหัสผ่านเก่า
  | $new_password | รหัสผ่านใหม่
   */
  public static function changePassword($user_id, $current_password, $new_password) {
    $rep = [
      'update_result' => false
    ];

    $model_user = static::getUserByID($user_id, ['selected_fields' => ['id', 'password']]);
    if (!isset($model_user['id'])) {
      return Amst::repError('E0005', 'User');
    }
    if ($model_user['password'] != md5($current_password)) {
      return Amst::repError('E0000', 'Wrong password');
    }
    if (!$new_password) {
      return Amst::repError('E0001', 'New Password');
    }

    $u_data = [
      'password' => md5($new_password)
    ];

    $user = User::getCurrent();

    if (isset($user['id'])) {
      $u_data['password_datetime'] = date('Y-m-d H:i:s');
      $u_data['password_user_id']  = $user['id'];
      $u_data['password_username'] = $user['username'];
    }

    // UPDATE
    $api = Amst::updateByID('sys_user', $u_data, $model_user['id']);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['update_result'] = $api['response_data']['update_result'];
    } else {
      return $api;
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Password
  | Description | 2.เปลี่ยนพาสเวิดใหม่ผ่าน email
  | $user_id | ไอดีผู้ใช้งาน
  | $new_password | รหัสผ่านใหม่ ถ้าไม่กรอกจะเจนให้อัตโนมัติ
   */
  public static function resetPasswordEmail($user_id, $new_password = null) {
    $rep = [
      'message' => 'error'
    ];

    $model_user = static::getUserByID($user_id, ['selected_fields' => ['id', 'email']]);
    if (!isset($model_user['id'])) {
      Log::logBackendShouldKnow('Invalid User', ['user_id' => $user_id, 'model_user' => $model_user]);
      return Amst::repError('E0005', 'User');
    }
    if (!isset($model_user['email']) || !$model_user['email']) {
      Log::logBackendShouldKnow('Invalid user Email', ['model_user' => $model_user]);
      return Amst::repError('E0000', 'Invalid user Email');
    }

    // Template
    if (!$new_password) {
      $new_password = substr(md5('new_pass_from_mail' . time() . 'salt eiei'), 0, 8);
    }
    // Info-ProgramSetting
    $info            = WLoves::getProgramSetup();
    $sender_email    = $info['support_email'];
    $ender_name      = $info['program_name'];
    $mail_title      = "Reset your password on " . $info['program_name'];
    $template_params = [
      'username'     => $model_user['username'],
      'new_password' => $new_password
    ];
    $template_path = ROOT_PHP . DIRECTORY_SEPARATOR . 'resource' . DIRECTORY_SEPARATOR . 'template_change_password.php';
    $template_html = Email::getHtmlTemplate($template_path, $template_params);

    // Send Mail
    if (isset($template_html) && $template_html) {
      $api = Email::sendHtmlMail($model_user['email'], $sender_email, $ender_name, $mail_title, $template_html);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        // SUCCESS
        Amst::updateByID('sys_user', ['password' => $new_password], $model_user['id']);
        $rep['message'] = 'Change password Successfully';
      } else {
        Log::logBackendShouldKnow('Fail to sendHtmlMail', ['sendHtmlMail' => $api]);
        return $api;
      }
    } else {
      Log::logBackendShouldKnow('Invalid Template', ['template_path' => $template_path]);
      return Amst::repError('E0000', 'Invalid Template');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Password
  | Description | 3.เปลี่ยนพาสเวิดใหม่ผ่าน SMS
  | $user_id | ไอดีผู้ใช้งาน
  | $new_password | รหัสผ่านใหม่ ถ้าไม่กรอกจะเจนให้อัตโนมัติ
   */
  public static function resetPasswordSMS($user_id, $new_password = null) {
    $rep = [];

    $model_user = static::getUserByID($user_id, ['selected_fields' => ['id', 'tel_no']]);
    if (!isset($model_user['id'])) {
      Log::logBackendShouldKnow('Invalid User', ['user_id' => $user_id, 'model_user' => $model_user]);
      return Amst::repError('E0005', 'User');
    }
    if (!isset($model_user['tel_no']) || !$model_user['tel_no']) {
      Log::logBackendShouldKnow('Invalid user Tel', ['model_user' => $model_user]);
      return Amst::repError('E0000', 'Invalid user Tel');
    }

    // Template
    if (!$new_password) {
      $new_password = substr(md5('new_pass_from_mail' . time() . 'salt eiei'), 0, 8);
    }
    // Info-ProgramSetting
    $sms_text = 'พาสเวิดใหม่ของท่านคือ ' . $new_password;
    $sms_tel  = $model_user['tel_no']; // [LATER] country code ?
    // Send Mail
    if (isset($sms_text) && $sms_text) {
      $api = SMS::sendSMSTwist($sms_tel, $sms_text);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        // SUCCESS
        Amst::updateByID('sys_user', ['password' => $new_password], $model_user['id']);
        $rep['message'] = 'Change password Successfully';
      } else {
        Log::logBackendShouldKnow('Fail to sendSMSTwist', ['sendSMSTwist' => $api]);
        return $api;
      }
    } else {
      Log::logBackendShouldKnow('Invalid SMS Message', ['sms_text' => $sms_text]);
      return Amst::repError('E0000', 'Invalid SMS Message');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Pin
  | Description | 1.เปลี่ยนพิน ที่จะต้องเช็คพินก่อน
  | $user_id | ไอดีผู้ใช้งาน
  | $current_pin | พินเก่า
  | $new_pin | พินใหม่
   */
  public static function changePin($user_id, $current_pin, $new_pin) {
    $rep = [];

    if (!$user_id || !$current_pin || !$new_pin) {
      return Amst::repError('Invalid Input');
    }

    $user_info = Amst::getByID('sys_user', ['id', 'pin'], $user_id);
    if (!$user_info) {
      return Amst::repError('User not found');
    }

    // เชคกับ pin เก่า
    if ($current_pin != $user_info['pin']) {
      return Amst::repError('PIN not correct');
    }

    $data_pin = ['pin' => $new_pin];
    $update   = static::updateUser($user_id, $data_pin);
    if (!$update) {
      return Amst::repError('Fail to update PIN');
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Ban
  | Description | 1.แบนผู้ใช้งาน จะไม่สามารถ Login ได้
  | $user_id | ไอดีผู้ใช้งาน
   */
  public static function ban($user_id) {
    $rep = [
      'user_id'       => $user_id,
      'update_result' => false
    ];

    $user = Amst::getByID('sys_user', ['id', 'username'], $user_id);
    if (isset($user['username'])) {
      $data = [
        'is_ban' => 1 // BANNED
      ];
      $rep['update_result'] = Amst::updateByID('sys_user', $data, $user_id, ['direct' => true]);
    } else {
      return Amst::repError('E0000', 'User not Found');
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Ban
  | Description | ยกเลิกการแบนผู้ใช้งาน
  | $user_id | ไอดีผู้ใช้งาน
   */
  public static function unban($user_id) {
    $rep = [];

    $user = Amst::get('sys_user', ['id', 'username'], ['id' => $user_id, 'is_ban' => 1]);
    if (isset($user['username'])) {
      $data = [
        'is_ban' => 0 // UNBANNED
      ];
      $rep['update_result'] = Amst::updateByID('sys_user', $data, $user_id, ['direct' => true]);
    } else {
      return Amst::repError('E0000', 'User not Found');
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | รูปภาพ
  | Description | 1.ดีงรูปภาพโปรของผู้ใช้งาน
  | $user_id | ไอดีผู้ใช้งาน
   */
  public static function getProfileImage($user_id) {
    $return = File::getPlaceHolder('user_square');
    return File::getPath('user_profile_image_' . $user_id, ['placeholder' => 'user_square']);
  }

  /**
  | Group | รูปภาพ
  | Description | 2.สร้างรูปภาพโปรของผู้ใช้งาน
  | $user_id | ไอดีผู้ใช้งาน
  | $file | FILE_INPUT
   */
  public static function setProfileImage($user_id, $file = null) {
    $rep = [
      'insert_id' => false
    ];
    return File::add('user_profile_image_' . $user_id, $file);
  }

  /**
  | Group | รูปภาพ
  | Description | 1.ดีงรูปบัตรประชาชน
  | $user_id | ไอดีผู้ใช้งาน
   */
  public static function getIdentityCardImage($user_id) {
    $return = File::getPlaceHolder('user_square');
    return File::getPath('user_identity_card_image_' . $user_id, ['placeholder' => 'user_square']);
  }

  /**
  | Group | รูปภาพ
  | Description | 2.สร้างรูปบัตรประชาชน
  | $user_id | ไอดีผู้ใช้งาน
  | $file | FILE_INPUT ไฟล์
   */
  public static function setIdentityCardImage($user_id, $file = null) {
    $rep = [
      'insert_id' => false
    ];
    return File::add('user_identity_card_image_' . $user_id, $file);
  }

  /**
  | Group | Excel
  | Description | 1.นำเข้า 'กลุ่มผู้ใช้' จากไฟล์ Excel
   */
  public static function importUserCsv($file = null, $fields = [], $ex_data = []) {
    $rep = [
      'insert_result' => false
    ];

    $fields  = ['name', 'surname', 'email', 'tel_no'];
    $ex_data = [
      'owner_id'  => -3, // ทำเสร็จแล้วอัพเดทอันนี้ด้วย กันคนกดมั่ว');
      'user_type_id' => 4,
      'user_type' => 'Customer'
      // 'insert_date_time' => Util::getSystemTimestamp(),
      // 'insert_user_id'   => 0,
      // 'update_date_time' => Util::getSystemTimestamp(),
      // 'update_user_id'   => 0,
      // 'version'          => 1,
      // 'system_delete'    => 0
      // 'sort_order_id' => 0,
    ];

    $insert_bulk_data = [];
    $header           = false;
    $api              = Excel::importCsv($file);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $data = $api['response_data']['output'];
      foreach ($data as $line => $i_row) {
        if (!$header) {
          $header = $i_row;
          if (json_encode($header) !== json_encode($fields)) {
            return Amst::repError('E0000', 'HEADER not match : "' . json_encode($header) . '" VS "' . json_encode($fields)) . '"';
          }
        } else {
          // ROW
          $i_n_row = [];
          if ($i_row && count($i_row) == count($header)) {
            foreach ($header as $key => $value) {
              if ($value == 'tel_no') {
                $i_n_row[$value] = Util::getNumberString($i_row[$key]);
              } else {
                $i_n_row[$value] = $i_row[$key];
              }
            }
            if ($i_n_row && count($i_n_row) == count($header)) {
              $insert_bulk_data[] = array_merge($i_n_row, $ex_data);
            } else {
              $rep['invalid_line'][$line] = 'Invalid Insert Data Lenght' . json_encode($i_n_row);
            }
          } else {
            $rep['invalid_line'][$line] = 'Invalid CSV row Lenght: "' . count($i_n_row) . '" VS "' . count($header) . '"';
          }
        }
      }
      if ($insert_bulk_data) {
        //foreach (array_chunk($insert_bulk_data, 1000) as $i_n_data) {
        //$rep['insert_result'][] = Amst::insert('sys_user', $i_n_data);
        //}
        foreach ($insert_bulk_data as $i_n_data) {
          $rep['insert_result'][] = User::addNewUser('SYSTEM', $i_n_data);
        }
      }
    } else {
      return $api;
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | HotFix
  | Description | 1.เช็คว่าอยู่โปรเจคไหน
   */
  public static function checkClassExists($class_name) {
    $rep = false;

    return class_exists($class_name);
  }

  /**
  | Group | Password
  | Description | 'Password'
  | $code | โค้ดโมดูล
  | $user_id | user_id
  | $new_password | new_password
   */
  public static function forceChangePassword($user_id, $new_password) {
    $u_data = [
      'password' => md5($new_password)
    ];

    $user = User::getCurrent();

    if (isset($user['id'])) {
      $u_data['password_datetime'] = date('Y-m-d H:i:s');
      $u_data['password_user_id']  = $user['id'];
      $u_data['password_username'] = $user['username'];
    }

    return Amst::updateByID('sys_user', $u_data, $user_id);
  }

  /**
  | Group | USER MODULE CONFIG
  | Description | 'USER MODULE CONFIG'
  | $code | โค้ดโมดูล
  | $user_id | user_id
   */
  public static function getModuleConfigByUserID($user_id) {
    $user = Amst::getByID('sys_user', '*', $user_id);
    if (!isset($user['id'])) {
      return [];
    }

    return Module::getModuleConfig($user['user_code']);
  }
}
