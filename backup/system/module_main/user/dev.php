<?php
class Dev {
  private static $conf = [
    'description' => 'คลาสสำหรับจัดการเดฟ',
    'tb_dev'      => 'sys_dev',
    'lbl_dev'     => 'เดฟ',
    'session_key' => [
      'id'   => '_dev_id',
      'hash' => '_dev_hash',
      'dev'  => '_dev', // En-id
      'info' => '_dev_info' // En-info
    ]
  ];
  // SINGLETON
  private static $_current_dev = false;

  /**
  | Group | Dev
  | Description | 2.เช็คข้อมูล username ว่ามีซ้ำในระบบไหม
  | $username | ชื่อผู้ใช้
   */
  public static function isUsernameExists($username) {
    $return = 1; // DEF มี
    if ($username) {
      if (Amst::has(static::$conf['tb_dev'], ['username' => $username])) {
        $return = 1;
      } else {
        $return = 0;
      }
    }
    return $return;
  }

  private static function _encryptMessage($raw_data, $secret_key, $secret_iv) {
    $return = '';

    // Advanced Encryption Standard (AES)
    // Private Key: VARCHAR
    // IV: CHAR(16)
    // Cipher Mode: CBC
    // Block Size: 128
    // Key Size: 256

    // $encrpy_msg = openssl_encrypt(json_encode($params), 'AES-128-CBC', $agent_secret_key, false, $agent_key);
    // $encrpy_msg = urlencode($encrpy_msg);
    // $return['encrpy_msg'] = $encrpy_msg;

    // Here is the most basic way to use openssl_encrypt and openssl_decrypt. Make sure to create 32 byte secret_key and 16 byte secret_iv
    $encrypt_method = "AES-256-CBC";
    $secret_key     = substr(trim($secret_key).Util::getRandomString(32, '00'), 0, 32);
    $secret_iv      = substr(trim($secret_iv).Util::getRandomString(32, '00'), 0, 16);
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes
    $iv     = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt(json_encode($raw_data), $encrypt_method, $key, 0, $iv);
    return base64_encode($output);
  }

  private static function _dencryptMessage($message, $secret_key, $secret_iv) {
    $return = [];
    // Advanced Encryption Standard (AES)
    // Private Key: VARCHAR
    // IV: CHAR(16)
    // Cipher Mode: CBC
    // Block Size: 128
    // Key Size: 256
    // $dencrpy_msg = urldecode($message);
    // $dencrpy_msg = openssl_decrypt($dencrpy_msg, 'AES-128-CBC', $agent_secret_key, false, $agent_key);

    // Here is the most basic way to use openssl_encrypt and openssl_decrypt. Make sure to create 32 byte secret_key and 16 byte secret_iv
    $encrypt_method = "AES-256-CBC";
    $secret_key     = substr(trim($secret_key).Util::getRandomString(32, '00'), 0, 32);
    $secret_iv      = substr(trim($secret_iv).Util::getRandomString(32, '00'), 0, 16);
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes
    $iv          = substr(hash('sha256', $secret_iv), 0, 16);
    $dencrpy_msg = openssl_decrypt(base64_decode($message), $encrypt_method, $key, 0, $iv);
    return json_decode($dencrpy_msg, true);
  }

  private static function _getUserSession() {
    $return = [
      'id'   => 0,
      'hash' => '',
      'info' => []
    ];
    // [20220603] ใช้เป็นตัวหลักตัวเดียวที่จะจัดการ SessionCookie ของ DEV
    if (defined('Config::encryption_secret_key') && (Config::encryption_secret_key)) {
      $return['id']   = SC::getSession(static::$conf['session_key']['id']);
      $return['hash'] = SC::getSession(static::$conf['session_key']['hash']);
      $temp_info      = SC::getSession(static::$conf['session_key']['info']);
      if (!is_array($temp_info)) {
        if ($temp_info = json_decode($temp_info ? $temp_info : '[]', true)) {
          $return['info'] = $temp_info;
        }
      } else {
        $return['info'] = $temp_info;
      }
      if (!$return['id']) {
        $dev_info       = static::_dencryptMessage(SC::getCookie(static::$conf['session_key']['dev']), Config::encryption_secret_key, Config::encryption_secret_iv);
        $return['id']   = isset($dev_info['id']) ? $dev_info['id'] : 0;
        $return['hash'] = isset($dev_info['hash']) ? $dev_info['hash'] : '';
      }
      if (!$return['info']) {
        $dev_info       = static::_dencryptMessage(SC::getCookie(static::$conf['session_key']['info']), Config::encryption_secret_key, Config::encryption_secret_iv);
        $return['info'] = isset($dev_info['info']) ? $dev_info['info'] : '[]';
      }
    } else {
      // OLD-COOKIEs
      $return['id']   = SC::get(static::$conf['session_key']['id']);
      $return['hash'] = SC::get(static::$conf['session_key']['hash']);
      $temp_info      = SC::get(static::$conf['session_key']['info']);
      if (!is_array($temp_info)) {
        if ($temp_info = json_decode($temp_info ? $temp_info : '[]', true)) {
          $return['info'] = $temp_info;
        }
      } else {
        $return['info'] = $temp_info;
      }
    }
    return $return;
  }

  private static function _setUserSession($user_id, $hash, $dev_info = []) {
    $rep = [
      'id'       => $user_id,
      'hash'     => $hash,
      'dev_info' => $dev_info
    ];
    // [20220603] ใช้เป็นตัวหลักตัวเดียวที่จะจัดการ SessionCookie ของ DEV
    // Session น่าจะ OK แล้ว
    SC::setSession(static::$conf['session_key']['id'], $user_id);
    SC::setSession(static::$conf['session_key']['hash'], $hash);
    if (defined('Config::encryption_secret_key') && (Config::encryption_secret_key)) {
      $data = [
        'id'   => $user_id,
        'hash' => $hash
      ];
      SC::setCookie(static::$conf['session_key']['dev'], static::_encryptMessage($data, Config::encryption_secret_key, Config::encryption_secret_iv));
    } else {
      // OLD-COOKIEs ไม่น่าดี
      SC::setCookie(static::$conf['session_key']['id'], $user_id);
      SC::setCookie(static::$conf['session_key']['hash'], $hash);
    }
    // Daily
    if ($dev_info) {
      static::_setUserInfoSession($dev_info);
    }
    return Amst::repSuccess($rep);
  }

  private static function _setUserInfoSession($dev_info = []) {
    $rep = [
      'dev_info' => $dev_info
    ];
    SC::setSession(static::$conf['session_key']['info'], json_encode($dev_info));
    if (defined('Config::encryption_secret_key') && (Config::encryption_secret_key)) {
      SC::setDailyCookie(static::$conf['session_key']['info'], static::_encryptMessage($dev_info, Config::encryption_secret_key, Config::encryption_secret_iv));
    } else {
      // OLD-COOKIEs ไม่น่าดี
      SC::setDailyCookie(static::$conf['session_key']['info'], json_encode($dev_info));
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Login/Logout
  | Description | 1.Login Dev เข้าสู่ระบบ
  | $username | indentity ข้อมูล username
  | $password | รหัสผ่าน
  | $ex_data | [remember_me(จดจำลงcookie), try(ตอนUserให้ฟอร์มเดียวกันจะลองเข้าให้)]
   */
  public static function login($username, $password, $ex_data = []) {
    $rep = []; // Dev Info

    // Normal Login
    if (!$username || !$password) {
      return Amst::repError('Invalid username or passwords [Dev]');
    }
    $input_password = $password;

    if (defined('Config::master_url') && (Config::master_url)) {
      // FROM HQ
      $json = API::call(Config::master_url, Config::master_api_key, '', 'Dev', 'login', [$username, $password, $ex_data], ['time_out' => 10]);
      $api  = json_decode($json, true);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $user = $api['response_data'];
      } else {
        return $api;
      }

      if (!isset($user['id'])) {
        return Amst::repError('E0005', 'Invalid User');
      }
      // SUCCESS
      static::_setUserSession($user['id'], $user['password']);
      $rep = $user;
      if (isset($rep['password'])) {
        unset($rep['password']);
      }
    } else {
      // FROM DB
      $username = strtolower(trim($username));
      $password = md5($password);

      // check if user
      $where = ['username' => $username];
      if (isset($where) && $where) {
        $selected_fields = [
          'id', 'username', 'password',
          'is_ban', 'system_delete', 'user_type',
          'is_on_debug', 'last_online_date_time'
        ];
        $user = Amst::get(static::$conf['tb_dev'], $selected_fields, $where);
      }
      if (!isset($user['id'])) {
        if (!isset($ex_data['try'])) {
          $i_log = [
            'user_id'        => 0,
            'input_username' => $username,
            'input_password' => $input_password,
            'type'           => 'LOGIN_DEV_NOT_FOUND',
            'sub_type'       => '',
            'json'           => json_encode([$username, $password, $ex_data])
          ];
          User_Log::addLoginLog($i_log);
        }
        return Amst::repError('Invalid username or password');
      }

      // SUPER_DEV
      if (isset($ex_data['user_type']) && ($ex_data['user_type'] == 'SUPER_DEV_ONLY')) {
        if (!isset($user['user_type']) || ($user['user_type'] != 'SUPER_DEV')) {
          return Amst::repError('Invalid username or password');
        }
      }

      // [USER_DATA]
      if (!$password || ($password != $user['password'])) {
        $i_log = [
          'user_id'        => 0,
          'input_username' => $username,
          'input_password' => $input_password,
          'type'           => 'LOGIN_DEV_INVALID_PASSWORD',
          'sub_type'       => '',
          'json'           => ''
        ];
        User_Log::addLoginLog($i_log);

        return Amst::repError('Invalid username or passwords');
      } else if (($user['system_delete'] == 1) || ($user['is_ban'] == 1)) {
        $i_log = [
          'user_id'        => $user['id'],
          'input_username' => $username,
          'input_password' => $input_password,
          'type'           => 'LOGIN_DEV_BANNED',
          'sub_type'       => ''
        ];
        User_Log::addLoginLog($i_log);

        return Amst::repError('BANNED');
      }
      // onSUCCESS [LATER] on remote no set ? this in use if on real HQ.
      static::_setUserSession($user['id'], $user['password']);

      $i_log = [
        'user_id'        => $user['id'],
        'input_username' => $username,
        'input_password' => '',
        'type'           => 'LOGIN_DEV_SUCCESS',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      // [LAST_ONLINE]
      if (array_key_exists('last_online_date_time', $user)) {
        $last_online_date_time         = Util::getSystemTimestamp(); // ME NOW !
        $rep['update_result']          = Amst::updateByID(static::$conf['tb_dev'], ['last_online_date_time' => $last_online_date_time], $user['id'], ['direct' => true]);
        $user['last_online_date_time'] = $last_online_date_time;
      }
      $rep = $user;
      // if (isset($rep['password'])) {
      //   unset($rep['password']);
      // }
    }
    return Amst::repSuccess($rep);
  }

  public static function loginPrivate($id, $hash, $ex_data = []) {
    $rep = []; // Dev Info

    // Normal Login
    if (!$id || !$hash) {
      return Amst::repError('Invalid input');
    }

    if (is_numeric($id) && $id) {
      $where           = ['id' => $id];
      $selected_fields = [
        'id', 'username', 'password',
        'is_ban', 'system_delete',
        'is_on_debug', 'last_online_date_time'
      ];
      $user = Amst::get(static::$conf['tb_dev'], $selected_fields, $where);
    }

    if (!isset($user['id'])) {
      $i_log = [
        'user_id'        => $id,
        'input_username' => '',
        'input_password' => '',
        'type'           => 'LOGIN_PRIVATE_ID_NOT_FOUND',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('Invalid id');
    }

    // [USER_DATA]
    if (!$hash || ($hash != $user['password'])) {
      $i_log = [
        'user_id'        => $user['id'],
        'input_username' => '',
        'input_password' => '',
        'type'           => 'LOGIN_PRIVATE_INVALID_PASSWORD',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('Invalid hash');
    } else if (($user['system_delete'] == 1) || ($user['is_ban'] == 1)) {
      $i_log = [
        'user_id'        => $user['id'],
        'input_username' => '',
        'input_password' => '',
        'type'           => 'LOGIN_PRIVATE_BANNED',
        'sub_type'       => ''
      ];
      User_Log::addLoginLog($i_log);

      return Amst::repError('BANNED');
    }

    // onSuccess
    $rep = $user;
    return Amst::repSuccess($rep);
  }

  private static function _logout() {
    $return               = true; // alway return true
    static::$_current_dev = null;
    foreach (static::$conf['session_key'] as $cs_name) {
      SC::remove($cs_name);
    }
    // DO NOT REP, will cause DEATHLOCK
    return $return;
  }

  /**
  | Group | Login/Logout
  | Description | 2.ออกจากระบบ เคลียร์ SC ทั้งหมด
   */
  public static function logout() {
    $rep = [
      'update_result' => false
    ];

    // MAIN
    // if (defined('Config::master_url') && (Config::master_url)) {
    //   $json = API::call(Config::master_url, Config::master_api_key, '', 'Dev', 'logout', []);
    //   return json_decode($json, true);
    // }
    $rep['update_result'] = static::_logout();

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

    $model_dev = static::getCurrent();
    if (!isset($model_dev['password'])) {
      return Amst::repError('E0005', 'User');
    }
    if ($model_dev['password'] != md5($current_password)) {
      return Amst::repError('E0000', 'Wrong password');
    }
    return Amst::repSuccess($rep);
  }

  public static function __unused__checkCurrent() {
    $return = [];

    $session_data   = static::_getUserSession();
    $id             = $session_data['id'];
    $hash           = $session_data['hash'];
    $return['id']   = $id;
    $return['hash'] = $hash;

    if (is_null($id) || is_null($hash)) {
      $return['logout'] = static::_logout();
      return $return;
    }

    // GET NEW
    static::$_current_dev  = Amst::getByID(static::$conf['tb_dev'], '*', $id);
    $return['current_dev'] = static::$_current_dev;
    if (isset(static::$_current_dev['id'])) {
      // CHECH BAN
      if ((static::$_current_dev['is_ban'] == 1) || (static::$_current_dev['system_delete'] == 1) || (static::$_current_dev['password'] != $hash)) {
        $return['banned'] = static::$_current_dev['is_ban'];
        $return['logout'] = static::_logout();
        return $return;
      }
      $return['status'] = 'SUCCESS';
    }

    return $return;
  }

  /**
  | Group | Current Dev
  | Description | 1.ดึง ID ของ Dev ใหม่ล่าสุด *เพราะ getCurrent จะดึงข้อมูลครั้งเดียว
   */
  public static function getRenewCurrent() {
    $return = null;

    $session_data = static::_getUserSession();
    $id           = $session_data['id'];
    $hash         = $session_data['hash'];
    if (is_null($id) || is_null($hash)) {
      static::_logout();
      return $return;
    }

    // GET NEW
    static::$_current_dev = Amst::getByID(static::$conf['tb_dev'], '*', $id);
    if (isset(static::$_current_dev['id'])) {
      // CHECH BAN
      if ((static::$_current_dev['is_ban'] == 1) || (static::$_current_dev['system_delete'] == 1) || (static::$_current_dev['password'] != $hash)) {
        static::_logout();
        return $return;
      }
      $return = static::$_current_dev;
    }

    return $return;
  }

  /**
  | Group | Current Dev
  | Description | 1.ดึง ID ของ Dev ที่กำลัง Login อยู่ในขณะนี้ *ดึงข้อมูลครั้งเดียว ต่อ 1 APICall เพื่อลด conn
   */
  public static function getCurrent() {
    $return = null;
    // GET ONCE
    if (static::$_current_dev === false) {
      $session_data = static::_getUserSession();
      $id           = $session_data['id'];
      $hash         = $session_data['hash'];
      $info         = $session_data['info'];
      if (!is_array($info)) {
        $info = json_decode($info, true);
      }

      // isLogedIn
      if (!$id || !$hash) {
        static::$_current_dev = null;
        // static::_logout();
        return $return;
      }
      static::$_current_dev = [
        'id'            => $id,
        'hash'          => $hash,
        'password'      => $hash,
        'is_ban'        => 0,
        'system_delete' => 0
      ];

      // แต่ถ้า ไม่มี info ให้ไปเอามาใหม่
      if (!$info) {
        if (defined('Config::master_url') && (Config::master_url)) {
          // FROM HQ
          $json = API::call(Config::master_url, Config::master_api_key, '', 'Dev', 'loginPrivate', [$id, $hash]);
          $api  = json_decode($json, true);
          if (isset($api['response_status']) && ($api['response_status'] == true)) {
            $model_user = [
              'username'      => isset($api['response_data']['username']) ? $api['response_data']['username'] : '',
              'full_name'     => isset($api['response_data']['full_name']) ? $api['response_data']['full_name'] : '',
              'is_ban'        => isset($api['response_data']['is_ban']) ? $api['response_data']['is_ban'] : 0,
              'system_delete' => isset($api['response_data']['system_delete']) ? $api['response_data']['system_delete'] : 0
            ];
            // UPDATE
            static::_setUserInfoSession($model_user);
          } else {
            static::_logout();
            return $return;
          }
        } else {
          // FROM DB
          $model_user = Amst::getByID(static::$conf['tb_dev'], ['id', 'username', 'full_name', 'is_ban', 'system_delete'], $id);
        }
        // SET
        static::$_current_dev['username']      = isset($model_user['username']) ? $model_user['username'] : '';
        static::$_current_dev['full_name']     = isset($model_user['full_name']) ? $model_user['full_name'] : '';
        static::$_current_dev['is_ban']        = isset($model_user['is_ban']) ? $model_user['is_ban'] : 0;
        static::$_current_dev['system_delete'] = isset($model_user['system_delete']) ? $model_user['system_delete'] : 0;
      }
    }

    $session_data = static::_getUserSession();
    $id           = $session_data['id'];
    $hash         = $session_data['hash'];
    $json         = API::call(Config::master_url, Config::master_api_key, '', 'Dev', 'loginPrivate', [$id, $hash]);
    $api          = json_decode($json, true);
    if (!isset($api['response_status']) || !$api['response_status']) {
      static::_logout();
      return null;
    }

    if (isset(static::$_current_dev['id'])) {
      // [ONLINE]
      // if (array_key_exists('last_online_date_time', static::$_current_dev)) {
      //   $last_online_date_time = Util::getSystemTimestamp(); // ME NOW !
      //   $rep['update_result'] = Amst::updateByID(static::$conf['tb_dev'], ['last_online_date_time' => $last_online_date_time], $user['id'], ['direct' => true]);
      //   Log::add(0, Amst::lastQuery(), 0, 'backend');
      //   $user['last_online_date_time'] = $last_online_date_time;
      // }
      // [LATER] เช็คสถานะต่างๆ
      // CHECK [BAN]  || (static::$_current_dev['password'] != $hash)
      if ((static::$_current_dev['is_ban'] == 1) || (static::$_current_dev['system_delete'] == 1)) {
        static::_logout();
        return $return;
      }
      // SUCCESS
      $return = static::$_current_dev;
    } else {
      static::_logout();
      return $return;
    }
    return $return;
  }

  /**
  | Group | Current Dev
  | Description | 2.ดึง ID ของ Dev ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentUserID() {
    $return      = 0;
    $current_dev = static::getCurrent();
    return (isset($current_dev['id']) ? $current_dev['id'] : $return);
  }

  /**
  | Group | Current Dev
  | Description | 3.ดึง Username ของ Dev ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentUsername() {
    $return      = '';
    $current_dev = static::getCurrent();
    return (isset($current_dev['username']) ? $current_dev['username'] : $return);
  }

  /**
  | Group | Current Dev
  | Description | 5.ดึง language_code ของ Dev ที่กำลัง Login อยู่ในขณะนี้
   */
  public static function getCurrentLanguage() {
    $return      = 'enus';
    $current_dev = static::getCurrent();
    return (isset($current_dev['language_code']) ? $current_dev['language_code'] : $return);
  }

  /**
  | Group | Current Dev
  | Description | 6.ดึงข้อมูลสถานะ online / offline
   */
  public static function getOnlineStatus() {
    $return = 'offline';
    return User::getOnlineStatus(static::getCurrent());
  }

  /**
  | Group | Current Dev
  | Description | 7.เช็คว่าตอนนี้ เครื่อง Client Login เข้าสู่ระบบหรือเปล่า
   */
  public static function isLogin() {
    $return      = 0;
    $current_dev = static::getCurrent();
    return (isset($current_dev['id']) ? $current_dev['id'] : $return);
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

    $user = Amst::getByID(static::$conf['tb_dev'], ['id', 'username'], $user_id);
    if (isset($user['username'])) {
      $data = [
        'is_ban' => 1 // BANNED
      ];
      $rep['update_result'] = Amst::updateByID(static::$conf['tb_dev'], $data, $user_id, ['direct' => true]);
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

    $user = Amst::get(static::$conf['tb_dev'], ['id', 'username'], ['id' => $user_id, 'is_ban' => 1]);
    if (isset($user['username'])) {
      $data = [
        'is_ban' => 0 // UNBANNED
      ];
      $rep['update_result'] = Amst::updateByID(static::$conf['tb_dev'], $data, $user_id, ['direct' => true]);
    } else {
      return Amst::repError('E0000', 'User not Found');
    }
    return Amst::repSuccess($rep);
  }

  public static function setDevListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // Do Each Works...
    foreach ($selected_records as $key => $row) {
      // $selected_records[$key]['some_field'] = 'some_val';
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
  | Group | ตั้งค่าเดฟ
  | Description | 1.ดึงข้อมูลลิส 'ตั้งค่าเดฟ'
  | $where | [รองรับauto_search(ตาราง sys_dev)]
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ), total_count(true // พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(0-N // เลขหน้า 0 คือส่งทั้งหมด), page_size('*' // จำนวนข้อมูลต่อหน้า), selected_fields('*' // คอลัมภ์ที่ต้องการ), sort(['column_name' => 'ASC/DESC'] // เรียงคอลัมภ์)]
   */
  public static function selectDev($where = [], $options = []) {
    $return = []; // list

    $selected_fields = Amst::getSelectedFields($options);
    return Amst::selectNoKey(static::$conf['tb_dev'], $selected_fields, $where, $options);
  }

  public static function setDevDetail($record, $options = []) {
    if (!isset($record['id'])) {
      return $record;
    }

    // Do Single Works...
    // $record['other_key'] = 'other_data';

    return $record;
  }

  /**
  | Group | ตั้งค่าเดฟ
  | Description | 2.ดึงข้อมูล 'ตั้งค่าเดฟ' ด้วยไอดี
  | $id | ไอดีรายการ
  | $options | [selected_fields('*' // คอลัมภ์ที่ต้องการ)]
   */
  public static function getDevByID($id, $options = []) {
    $return = null;

    $selected_fields = Amst::getSelectedFields($options, ['id', 'system_delete']);
    if ($id) {
      $model = Amst::getByID(static::$conf['tb_dev'], $selected_fields, $id, $options);
      if (isset($model['id'])) {
        $return = static::setDevDetail($model, $options);
      }
    }
    return $return;
  }

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
  | Group | ตั้งค่าเดฟ
  | Description | 3.เพิ่ม 'ตั้งค่าเดฟ'
  | $data | [username(username), password(password), name(ชื่อเดฟ), surname(ชื่อเดฟ), full_name(ชื่อเดฟ)]
   */
  public static function addNewDev($data) {
    $rep = [
      'insert_id' => false
    ];

    $data             = Amst::checkOption($data);
    $data['role']     = 'dev';
    $data['username'] = isset($data['username']) ? strtolower(trim($data['username'])) : '';
    // Allow only a-z 0-9
    if (!$data['username'] || !preg_match("/^[a-z0-9_\-@.]+$/", $data['username'])) {
      return Amst::repError('E0000', 'Invalid Username only a-z 0-9 and some@ allowed');
    }
    if (!isset($data['username']) || !strlen($data['username'])) {
      return Amst::repError('E0001', 'Username');
    }
    if (!isset($data['password']) || !strlen($data['password'])) {
      return Amst::repError('E0001', 'Password');
    }

    // [FULLNAME]
    if ($full_name = static::_getFullName($data)) {
      $data['full_name'] = $full_name;
    }

    return Amst::insert(static::$conf['tb_dev'], $data);
  }

  /**
  | Group | ตั้งค่าเดฟ
  | Description | 4.อัพเดท 'ตั้งค่าเดฟ'
  | $id | ไอดีรายการ
  | $data | [password(password), name(ชื่อเดฟ), surname(ชื่อเดฟ), full_name(ชื่อเดฟ)]
   */
  public static function updateDev($id, $data) {
    $rep = [
      'update_result' => false
    ];

    $model = Amst::getByID(static::$conf['tb_dev'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_dev']);
    }

    // [FULLNAME]
    if ($full_name = static::_getFullName($data)) {
      $data['full_name'] = $full_name;
    }

    return Amst::updateByID(static::$conf['tb_dev'], $data, $id);
  }

  /**
  | Group | ตั้งค่าเดฟ
  | Description | 5.ลบ 'ตั้งค่าเดฟ'
  | $id | ไอดีรายการ
   */
  public static function deleteDev($id) {
    $rep = [
      'delete_result' => false
    ];

    $api = static::_checkDeleteDev($id);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      if (isset($api['response_data']['username']) && $api['response_data']['username']) {
        // update
        $new_username = $api['response_data']['username'].'_del_t'.time();
        $has          = Amst::has(static::$conf['tb_dev'], ['username' => $new_username]);
        if ($has) {
          $i       = 1;
          $has_new = Amst::has(static::$conf['tb_dev'], ['username' => $new_username]);
          while ($has_new) {
            $new_username = $api['response_data']['username'].'_del_t'.time().'.'.$i++;
            $has_new      = Amst::has(static::$conf['tb_dev'], ['username' => $new_username]);
          }
        }
        $u_data = [
          'username'      => $new_username,
          'system_delete' => 1
        ];
        $rep['delete_result'] = Amst::updateByID(static::$conf['tb_dev'], $u_data, $id, ['direct' => true]);
      } else {
        $rep['delete_result'] = Amst::deleteByID(static::$conf['tb_dev'], $id);
      }
      return Amst::repSuccess($rep);
    }
    return $api; // ERROR SURE
  }

  private static function _checkDeleteDev($id) {
    $rep = [
      'id'       => $id,
      'username' => ''
    ];

    // [Model_Exist]
    $model = Amst::getByID(static::$conf['tb_dev'], ['id', 'username'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', static::$conf['lbl_dev']);
    }
    $rep['username'] = $model['username'];

    return Amst::repSuccess($rep);
  }
}
