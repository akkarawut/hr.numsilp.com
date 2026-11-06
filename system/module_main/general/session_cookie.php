<?php
class SC {
  private static $conf = [
    'description' => 'จัดการ Session/Cookie ทั้ง File/Redis',
    'cookie_max_days' => 3600 * 24 * 365, // 1 YEAR
  ];

  public static function session_start() {
    // SET UP session timeout 3600 = 1 hour
    ini_set('session.gc_maxlifetime', 18000);
    // [20220603] ลองใส่ละไม่มีผลอะไร 
    // session_set_cookie_params(18000);

    if (defined('Config::session_driver') && (Config::session_driver == 'redis')) {
      try {
        new SessionManager(Config::session_redis_url, Config::session_redis_port, Config::session_redis_password); // connect redis
      } catch (Exception $e) {
        echo "Redis connection error: ".$e->getMessage();
        exit;
      }
    } else {
      session_start();
    }
  }

  private static function _getPath($name) {
    // [20211202] Found Bug ROOT_PHP is same when you run in Docker
    // * Changed from ROOT_PHP to ROOT_URL
    // $return['sc_path'] = 'md5('.ROOT_PHP.')+name';
    // $return['sc_path2'] = 'md5('.ROOT_URL.')+name';
    return md5(ROOT_URL).$name;
  }

  /**
  | Group | Session & Cookie
  | Description | 1.get Session & Cookie by NAME **SESSION FIRST THEN COOKIES.
   */
  static function get($name) {
    $path = static::_getPath($name);
    if (isset($_SESSION[$path])) {
      return $_SESSION[$path];
    }
    return static::getCookie($name);
  }

  /**
  | Group | Session & Cookie
  | Description | 2.set BOTH Session/Cookie by NAME.
   */
  static function set($name, $value) {
    static::setSession($name, $value);
    static::setCookie($name, $value);
  }

  /**
  | Group | Session & Cookie
  | Description | 3.remove BOTH Session/Cookie by NAME.
   */
  static function remove($name) {
    $path = static::_getPath($name);
    if (isset($_SESSION[$path])) {
      unset($_SESSION[$path]);
    }

    if (isset($_COOKIE[$path])) {
      setcookie($path, null, time() - 360000, '/');
      unset($_COOKIE[$path]);
    }
    // Log::addRaw('remove()', static::showConfig());
  }

  /**
  | Group | Session
  | Description | 1.get Session by NAME.
   */
  static function getSession($name) {
    $path = static::_getPath($name);
    if (isset($_SESSION[$path])) {
      return $_SESSION[$path];
    }
    return null;
  }

  /**
  | Group | Session
  | Description | 2.set Session by NAME.
   */
  static function setSession($name, $value) {
    $path = static::_getPath($name);
    // if (Util::startsWith($name, 'homepgify_table')) {
    //   $server_info = Util::site_url();
    //   Log::add(0, ['setSession', $name, $value, $server_info], 0, 'backend');
    // }
    $_SESSION[$path] = $value;
    // Log::addRaw('setSession()'.$name, static::showConfig());
  }

  static function setSessionWithDebug($name, $value) {
    $path = static::_getPath($name);
    $_SESSION[$path] = $value;
    $rep = [
      'server'        => $_SERVER,
      'name'          => $name,
      'value'         => $value,
      'SESSION_after' => $_SESSION
    ];
    Log::add(0, $rep, 0, 'backend');
  }

  /**
  | Group | Session
  | Description | 3.remove Session by NAME.
   */
  static function removeSession($name) {
    $path = static::_getPath($name);
    if (isset($_SESSION[$path])) {
      unset($_SESSION[$path]);
    }
  }

  /**
  | Group | Cookie
  | Description | 1.get Cookie by NAME
   */
  static function getCookie($name) {
    $path = static::_getPath($name);
    if (isset($_COOKIE[$path]) && $_COOKIE[$path]) {
      return ($_COOKIE[$path]);

      // [LATER] ทำไมต้อง serialize นะ 
      // [20220531] เอาออกไปก่อนเพราะติด SonarQube
      // $debug = [
      //   'name' => $name,
      //   'path' => $path,
      //   'cokkies' => $_COOKIE,
      //   'cp' => $_COOKIE[$path],
      // ];
      // Log::logBackendShouldKnow('debug getCookie', $debug);
      // $data = $_COOKIE[$path];
      // list($hash, $data) = explode('|', $data, 2);
      // $hash_confirm = hash_hmac("sha256", $data, "DZUJEoZOM0S6ol2y");
      // // Confirm that the data integrity is not compromised
      // if ($hash === $hash_confirm) {
      //   return unserialize($data);
      // }
    }
    return null;
  }

  /**
  | Group | Cookie
  | Description | 2.set Cookie by NAME
   */
  static function setCookie($name, $value) {
    $path = static::_getPath($name);
    $expire_days = intval(static::$conf['cookie_max_days']);
    setcookie($path, ($value), time() + $expire_days, '/');
  }

  /**
  | Group | Cookie
  | Description | 2.set Cookie by NAME for a day
   */
  static function setDailyCookie($name, $value) {
    $path = static::_getPath($name);
    $expire_days = 3600 * 24; // one day
    setcookie($path, ($value), time() + $expire_days, '/');
  }


  /**
  | Group | Cookie
  | Description | 3.remove Cookie by NAME.
   */
  static function removeCookie($name) {
    $path = static::_getPath($name);
    if (isset($_SESSION[$path])) {
      unset($_SESSION[$path]);
    }
  }

  static function flushSession() {
    return session_write_close();
  }

  /**
  | Group | Session & Cookie
  | Description | 99.test GET [20210714] working as x-pec.
   */
  public static function showConfig() {
    $return = [];

    $return['sc_path_template'] = 'md5('.ROOT_URL.')+name';

    if (isset($_SESSION)) {
      $return['session_data'] = $_SESSION;
    }

    if (isset($_COOKIE)) {
      $return['cookie_data'] = $_COOKIE;
    }

    $return['config.session_driver'] = defined('Config::session_driver') ? Config::session_driver : 'NOT_DEFINE';
    $return['config.session_redis_url'] = defined('Config::session_redis_url') ? Config::session_redis_url : 'NOT_DEFINE';
    $return['config.session_redis_password'] = defined('Config::session_redis_password') ? Config::session_redis_password : 'NOT_DEFINE';
    $return['config.session_redis_port'] = defined('Config::session_redis_port') ? Config::session_redis_port : 'NOT_DEFINE';

    $return['php_ini.session.save_handler'] = ini_get('session.save_handler');
    $return['php_ini.session.save_path'] = ini_get('session.save_path');
    $return['php_ini.session.gc_max_lifetime'] = ini_get('session.gc_max_lifetime');
    $return['php_ini.session.gc_probability'] = ini_get('session.gc_probability');
    $return['php_ini.session.gc_divisor'] = ini_get('session.gc_divisor');
    $return['php_ini.session.hash_bits_per_character'] = ini_get('session.hash_bits_per_character');

    return $return;
  }

  /**
  | Group | Session & Cookie
  | Description | 99.test FORCE DELETE SERVER SESSION
   */
  private static function _removeAllServerSession() {
    $rep = [
      'update_result' => true
    ];
    // "config.session_driver": "file",
    // "config.session_redis_url": "",
    // "config.session_redis_password": "",
    // "config.session_redis_port": 25061,
    // "php_ini.session.save_handler": "files",
    // "php_ini.session.save_path": "",
    // "php_ini.session.gc_max_lifetime": false,
    // "php_ini.session.gc_probability": "1",
    // "php_ini.session.gc_divisor": "100",
    // "php_ini.session.hash_bits_per_character": false
    // You could try to force PHP to delete all the sessions by doing
    // ini_set('session.gc_max_lifetime', 0);
    // ini_set('session.gc_probability', 1);
    // ini_set('session.gc_divisor', 1);
    // That forces PHP to treat all sessions as having a 0-second lifetime, and a 100% probability of getting cleaned up.
    // The drawback is that whichever unlucky user runs this first will get a long pause while PHP does cleanup, especially if there's a lot of session files to go through.
    // Finds all server sessions
    // problem : ini_set(): A session is active. You cannot change the session module's ini settings at this time
    // https://stackoverflow.com/questions/5193744/how-to-kill-a-all-php-sessions
    session_start();
    // Stores in Array
    $_SESSION = [];
    // Swipe via memory
    if (ini_get("session.use_cookies")) {
      // Prepare and swipe cookies
      $params = session_get_cookie_params();
      // clear cookies and sessions
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
      $rep['$params'] = $params;
    }
    // Just in case.. swipe these values too
    ini_set('session.gc_max_lifetime', 0);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 1);
    // Completely destroy our server sessions..
    session_destroy();
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Session & Cookie
  | Description | 99.test SET [20210714] working as x-pec.
   */
  public static function testSesssion($key = '555+', $value = 0) {
    $return = [
      'before' => $_SESSION
    ];
    $_SESSION[$key] = $value;
    $return['after'] = $_SESSION;
    return $return;
  }
}

class SessionManager {
  private $redis;
  private $sessionSavePath;
  private $sessionName;
  private $sessionExpireTime = 3600; //= 1 hour

  public function __construct($url, $port = 6379, $password = '') {
    $this->redis = new Redis(); // Create phpredis instance
    // $this->redis->connect($url, $port); // connect redis
    $this->redis->pconnect($url, $port, 2); // connect redis
    // $redis->setOption(Redis::OPT_READ_TIMEOUT, 1);

    if ($password) {
      $this->redis->auth($password);
      //   if ($this->_socket->auth($this->password) === false) {
      //     throw new Exception('Redis authentication failed!');
      // }
    }

    $retval = session_set_save_handler(
      [$this, "open"],
      [$this, "close"],
      [$this, "read"],
      [$this, "write"],
      [$this, "destroy"],
      [$this, "gc"]
    );
    session_start();
  }

  public function open($path, $name) {
    return true;
  }

  public function close() {
    return true;
  }

  public function read($id) {
    $value = $this->redis->get($id); // Get the specified record in redis
    if ($value) {
      return $value;
    } else {
      return '';
    }
  }

  public function write($id, $data) {
    if ($this->redis->set($id, $data)) {
      // stored with session ID as the key
      $this->redis->expire($id, $this->sessionExpireTime); // Set the expiration time of data in redis, that is, session expiration time
      return true;
    }

    return false;
  }

  public function destroy($id) {
    if ($this->redis->delete($id)) {
      // delete the specified record in redis
      return true;
    }
    return false;
  }

  public function gc($maxlifetime) {
    return true;
  }

  public function __destruct() {
    session_write_close();
  }
}
