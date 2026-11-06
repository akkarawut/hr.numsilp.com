<?php
class Log {
  private static $conf = [
    'description' => 'ทุกอย่างที่เกี่ยวกับ Log'
  ];
  // PROBLEM LOG = error from WARNING and ERROR from PHP run time
  // SQL LOG = error from SQL
  private static $error_count   = 0;
  private static $ishandleError = false;
  const error_nums              = [
    "1"     => "E_ERROR",
    "2"     => "E_WARNING",
    "4"     => "E_PARSE",
    "8"     => "E_NOTICE",
    "16"    => "E_CORE_ERROR",
    "32"    => "E_CORE_WARNING",
    "64"    => "E_COMPILE_ERROR",
    "128"   => "E_COMPILE_WARNING",
    "256"   => "E_USER_ERROR",
    "512"   => "E_USER_WARNING",
    "1024"  => "E_USER_NOTICE",
    "2048"  => "E_STRICT",
    "4096"  => "E_RECOVERABLE_ERROR",
    "8192"  => "E_DEPRECATED",
    "16384" => "E_USER_DEPRECATED",
    "32767" => "E_ALL"
  ];
  private static $cache_folder_setting = false;

  /**
   * [HELPER] Check Where is Array and not Empty
   *
   * @author AMST
   * @param ARRAY $line INPUT where
   * @return ARRAY
   */
  private static function _traceFileSource($line) {
    $return = [
      'target_file' => '',
      'trace'       => ''
    ];

    $tracearr = debug_backtrace();
    foreach ($tracearr as $trace) {
      // if found target file with same line
      if (isset($trace['line'])) {
        if ($trace['line'] == $line) {
          $return['target_file'] = str_replace('var/www/html/', '', $trace['file']);
        }
      }

      // add more data to trace
      if (isset($trace['file'])) {
        $return['trace'] .= 'FILE : <b>'.basename($trace['file']).'</b> ['.$trace['line'].'] call '.$trace['function'].'()<br />';
      }
    }
    $return['trace'] .= '<br />';

    return $return;
  }

  /**
   * [IMPORT] Handle
   *
   * @author AMST
   * @return NULL
   */
  public static function handleError($error_num, $error_str, $error_file, $error_line, $fatal_error = false) {
    if (strpos('Automatically populating $HTTP_RAW_POST_DATA', $error_str) === false) {
      Log::add($error_num, $error_str, $error_line);
    }
  }

  /**
   * [IMPORT] Handle
   *
   * @author AMST
   * @return NULL
   */
  public static function handleFatalError() {
    $error = error_get_last();

    if (!is_null($error)) {
      $error_num  = (null !== $error) ? $error["type"] : E_CORE_ERROR;
      $error_str  = (null !== $error) ? $error["message"] : 'shutdown';
      $error_file = (null !== $error) ? $error["file"] : 'unknown file';
      $error_line = (null !== $error) ? $error["line"] : 0;

      self::handleError($error_num, $error_str, $error_file, $error_line, true);
    }
  }

  /**
  | Group | Operation
  | Description | 1. เพิ่ม LOG เมื่อ Backend ทำงานผิดพลาด
  | $errMsg | ข้อความ
  | $data | ARRAY ข้อมูลอื่นๆที่จะ Log
   */
  public static function logBackendShouldKnow($errMsg, $data) {
    $return = false; // INSERT ID

    $log_data = array_merge(['errMsg' => $errMsg], $data);

    if (defined('Config::log_max_count') && (Config::log_max_count > 0)) {
      static::$error_count++;
      if (static::$error_count >= Config::log_max_count) {
        return $return;
      }
    }

    $bt     = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1); // performance
    $caller = array_shift($bt);

    $code = (isset($_REQUEST['c'])) ? $_REQUEST['c'] : '';
    if (!$code) {
      $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
    }

    $class    = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : '';
    $function = (isset($_REQUEST['function'])) ? $_REQUEST['function'] : '';

    $type      = 'backend';
    $error_num = 2; // E_WARNING

    $err_num = static::error_nums; // for ISSET
    $topic   = '<small>';
    if (isset($err_num[$error_num]) && $err_num[$error_num]) {
      $topic .= static::error_nums[$error_num];
    } else {
      $topic .= $error_num;
    }

    if (is_array($log_data)) {
      $log_data = json_encode($log_data, JSON_UNESCAPED_UNICODE);
    }
    $topic .= '</small><br />FILE : <b>'.$caller['file'].'</b> ['.$caller['line'].']<br />problem : <b>'.$log_data.'</b>';

    $description = '';
    if ($_REQUEST) {
      $description = Util::entityToString($_REQUEST).'<br/>';
    }

    // insert
    $data = [
      'code'        => $code,
      'topic'       => $topic,
      'file'        => $caller['file'],
      'line'        => $caller['line'],
      'class'       => $class,
      'func'        => $function,
      'link'        => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'file_source' => $caller['file'],
      'description' => $description,
      'type'        => $type
    ];
    // CACHE LOG IN TRANSACTION
    if (Amst::getTransactionState() === 'ON') {
      Amst::setTransError(false, $data);
      return 1;
    }
    return Amst::insert(Info::$system_table['log_error'], $data, ['direct' => true]);
  }

  /**
  | Group | Operation
  | Description | 2. เพิ่ม LOG เมื่อ Frontend ทำงานผิดพลาด
  | $errMsg | ข้อความ
  | $data | ARRAY ข้อมูลอื่นๆที่จะ Log
   */
  public static function logFrontendShouldKnow($errMsg, $data) {
    $log_data = [
      'errMsg' => $errMsg
    ];
    static::add(2, array_merge($log_data, $data), 0, 'frontend');
    return true;
  }

  /**
  | Group | Operation
  | Description | ใส่ Log เพิ่ม type = backend, frontend, sql, unknown
  | $error_num | error_num
  | $error_str | error_str
  | $error_line | error_line
  | $type | unknown|backend|frontend
   */
  public static function add($error_num, $error_str, $error_line = 0, $type = 'unknown') {
    $return = false; // INSERT ID

    //Count Current Error
    $count = Log::countLog();
    if ($count >= 10000) {
      //ระบบชอบมีปัญหาเวลา Log เยอะมากๆ จะทำให้เว็บช้า
      Log::deleteLog();
    }

    if (static::$ishandleError) {
      return false; // [20220518] prevent recusive log
    }
    static::$ishandleError = true;
    if (defined('Config::log_max_count') && (Config::log_max_count > 0)) {
      static::$error_count++;
      if (static::$error_count >= Config::log_max_count) {
        return $return;
      }
    }

    global $_REQUEST;
    global $_SERVER;

    // initial data
    $trace_data = static::_traceFileSource($error_line);

    $code = (isset($_REQUEST['c'])) ? $_REQUEST['c'] : '';
    if (!$code) {
      $code = (isset($_REQUEST['code'])) ? $_REQUEST['code'] : '';
    }

    $class    = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : '';
    $function = (isset($_REQUEST['function'])) ? $_REQUEST['function'] : '';

    if ('unknown' == $type) {
      if (strpos($_SERVER['PHP_SELF'], 'system/') !== false) {
        $type = 'backend';
      } else if (strpos($_SERVER['PHP_SELF'], 'wloves/') !== false) {
        $type = 'frontend';
      }
    }

    $err_num = static::error_nums; // for ISSET
    $topic   = '<small>';
    if (isset($err_num[$error_num]) && $err_num[$error_num]) {
      $topic .= static::error_nums[$error_num];
    } else {
      $topic .= $error_num;
    }

    if (is_array($error_str)) {
      $error_str = json_encode($error_str, JSON_UNESCAPED_UNICODE);
    }
    $topic .= '</small><br />FILE : <b>'.$trace_data['target_file'].'</b> ['.$error_line.']<br />problem : <b>'.$error_str.'</b>';

    $description = '';
    if ($_REQUEST) {
      $description = Util::entityToString($_REQUEST).'<br/>';
    }

    if (static::$cache_folder_setting === false) {
      // ไม่มีโค้ดให้อ่าน setting
      // $m_conf = Module::getModuleByCode($m_code);
      static::$cache_folder_setting = [
        '/system/library/PHPExcel'
      ];
    }
    if ($trace_data['target_file'] && static::$cache_folder_setting) {
      // [20220615] ไม่ใส่ Log จาก Folder ที่เซ็ตไว้
      foreach (static::$cache_folder_setting as $i_folder_path) {
        if (Util::startsWith($trace_data['target_file'], $i_folder_path)) {
          return $return;
        }
      }
    }

    // [SSL][HTTPS]
    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) {
      $protocol = 'https';
    }
    if (($protocol == 'http') && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
      $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    }

    // insert
    $data = [
      'code'        => $code,
      'topic'       => $topic,
      'file'        => $trace_data['target_file'],
      'line'        => $error_line,
      'class'       => $class,
      'func'        => $function,
      'link'        => $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'file_source' => $trace_data['trace'],
      'description' => $description,
      'type'        => $type
    ];
    // CACHE LOG IN TRANSACTION
    if (Amst::getTransactionState() === 'ON') {
      Amst::setTransError(false, $data);
      return 1;
    }

    $api = Amst::insert(Info::$system_table['log_error'], $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $return                = $api['response_data']['update_result'];
      static::$ishandleError = false;
    }
    return $return;
  }

  public static function addRaw($error_str, $data) {
    $return = false; // INSERT ID

    $error_line = 0;
    $error_num  = 0;
    // initial data
    $trace_data = static::_traceFileSource($error_line);

    $err_num = static::error_nums; // for ISSET
    $topic   = '<small>';
    if (isset($err_num[$error_num]) && $err_num[$error_num]) {
      $topic .= static::error_nums[$error_num];
    } else {
      $topic .= $error_num;
    }

    if (is_array($error_str)) {
      $error_str = json_encode($error_str, JSON_UNESCAPED_UNICODE);
    }
    $topic .= '</small><br />FILE : <b>'.$trace_data['target_file'].'</b> ['.$error_line.']<br />problem : <b>'.$error_str.'</b>';

    $description = '';
    if ($_REQUEST) {
      $description = Util::entityToString($_REQUEST).'<br/>';
    }

    // insert
    $data = [
      'code'        => 'DEBUG',
      'topic'       => $topic,
      'file'        => $trace_data['target_file'],
      'line'        => $error_line,
      'class'       => 'class',
      'func'        => 'function',
      'link'        => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'file_source' => $trace_data['trace'],
      'description' => $description,
      'type'        => 'backend'
    ];
    return DB::insert('sys_log_error', $data);
  }

  /**
  | Group | SELECT
  | Description | 1.ลิส ประเภท Log
  | $where | ไม่มี
  | $options | [it_nav_wolves(ส่งข้อมูลกลับแบบ it_nav_wolves)]
   */
  public static function selectCategory($where = [], $options = []) {
    $return = []; // pass_list

    $logs_count       = Log::countAllType();
    $selected_records = [
      'sql_log'      => [
        'name'  => 'SQL LOG',
        'type'  => 'sql',
        'count' => $logs_count['sql']
      ],
      'backend_log'  => [
        'name'  => 'BACKEND LOG',
        'type'  => 'backend',
        'count' => $logs_count['backend']
      ],
      'frontend_log' => [
        'name'  => 'FRONTEND LOG',
        'type'  => 'frontend',
        'count' => $logs_count['frontend']
      ],
      'web_log'      => [
        'name'  => 'WEB LOG',
        'type'  => 'unknown',
        'count' => $logs_count['unknown']
      ]
    ];

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      $nav_list = [];
      foreach ($selected_records as $record) {
        $color_status = 'green-status';
        $color        = '#31ca48';
        $status       = 'เยี่ยม !';
        if (0 < $record['count'] && $record['count'] < 50) {
          $color_status = 'yellow-status';
          $color        = '#ffbb39';
          $status       = 'เช็คด่วน';
        } else if (50 <= $record['count']) {
          $color_status = 'red-status';
          $color        = '#fe635b';
          $status       = 'ชิบหาย';
        }

        $record['id']           = $record['type'];
        $record['color_status'] = $color_status;
        $record['status']       = $status;
        $record['color']        = $color;
        $record['icon']         = '../../structure/image/icon/general/icon-log.svg';

        $nav_list[] = $record;
      }

      return [
        'title' => '',
        'class' => '',
        'list'  => ($nav_list)
      ];
    }

    return $selected_records;
  }

  /**
  | Group | SELECT
  | Description | 1.นับจำนวนข้อมูลลิส 'Log' (total_count)
  | $where | [search_text(ค้นหาบางส่วนของ Topic),type(ตามประเภท),file_name(ค้นหาบางส่วนของชื่อไฟล์),code_name(ค้นหาบางส่วนของ Code)]
  | $options | ไม่มี
   */
  public static function countLog($where = [], $options = []) {
    $return = 0; // COUNT

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['topic[~]'] = "{$where['search_text']}";
    }
    if (isset($where['type']) && strlen($where['type'])) {
      $sql_where['AND']['type'] = $where['type'];
    }
    if (isset($where['file_name']) && strlen($where['file_name'])) {
      $sql_where['AND']['file[~]'] = "{$where['file_name']}";
    }
    if (isset($where['code_name']) && strlen($where['code_name'])) {
      $sql_where['AND']['code[~]'] = "{$where['code_name']}";
    }

    $table_name    = Info::$system_table['log_error'];
    return $return = Amst::count($table_name, $sql_where);
  }

  /**
  | Group | SELECT
  | Description | 1.ดึงข้อมูลลิส 'Log'
  | $where | [search_text(ค้นหาบางส่วนของ Topic),type(ตามประเภท),file_name(ค้นหาบางส่วนของชื่อไฟล์),code_name(ค้นหาบางส่วนของ Code)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(*|คอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรอง(ไม่แบ่งหน้า)), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามคอลัมภ์), sort_by(DESC|เรียงจากมากไปน้อย), export(excel)]
   */
  public static function selectLog($where = [], $options = []) {
    $return = []; // pass_list

    $sql_where = [];
    if (isset($where['search_text']) && strlen("{$where['search_text']}")) {
      $sql_where['OR #search_text']['topic[~]'] = "{$where['search_text']}";
    }
    if (isset($where['type']) && strlen($where['type'])) {
      $sql_where['AND']['type'] = $where['type'];
    }
    if (isset($where['file_name']) && strlen($where['file_name'])) {
      $sql_where['AND']['file[~]'] = "{$where['file_name']}";
    }
    if (isset($where['code_name']) && strlen($where['code_name'])) {
      $sql_where['AND']['code[~]'] = "{$where['code_name']}";
    }

    $table_name      = Info::$system_table['log_error'];
    $selected_fields = Util::getOptionsValue('selected_fields', $options, '*');

    // For It-Nav
    if (Util::getOptionsValue('it_nav_wolves', $options, false)) {
      return [
        'title' => '',
        'class' => '',
        'list'  => Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options)
      ];
    }

    return Amst::selectNoKey($table_name, $selected_fields, $sql_where, $options);
  }

  public static function selectByType($type) {
    // tobe removed
    $where = [
      'type'  => $type,
      'LIMIT' => 100,
      'ORDER' => [
        'id' => 'DESC'
      ]
    ];
    return Amst::select(Info::$system_table['log_error'], '*', $where);
  }

  /**
  | Group | SELECT
  | Description | นับจำนวน Log ทุกประเภท (backend, frontend, sql, unknown)
   */
  public static function countAllType() {
    $return = [
      'unknown'  => 0,
      'backend'  => 0,
      'frontend' => 0,
      'sql'      => 0
    ];

    // $logs = Amst::select(Info::$system_table['log_error'], 'type');
    if (Config::db_type == 'mssql') {
      $selected_records = Amst::queryFetchAll("SELECT count(*) as cc, type FROM [sys_log_error] WHERE system_delete = 0 GROUP BY type;");
    } else {
      $selected_records = Amst::queryFetchAll("SELECT count(*) as cc, type FROM `sys_log_error` WHERE system_delete = 0 GROUP BY type;");
    }
    foreach ($selected_records as $record) {
      if (isset($return[$record['type']])) {
        $return[$record['type']] = $record['cc'];
      }
    }

    return $return;
  }

  /**
  | Group | Operation
  | Description | ลบ Log ทั้งหมด (หรือใส่ where มาได้)
  | $where | [id(ลบด้วยไอดี),same_id(ลบด้วยไอดีและข้อมูลที่ใกล้เคียงกัน),type(ลบทั้งหมดในประเภทนี้)]
   */
  public static function deleteLog($where = []) {
    $rep = [];

    if (isset($where['id'])) {
      return DB::delete(Info::$system_table['log_error'], $where);
    } else if (isset($where['same_id'])) {
      return static::_deleteSameLog($where['same_id']);
    } else if (isset($where['type'])) {
      return DB::delete(Info::$system_table['log_error'], $where);
    }

    return DB::delete(Info::$system_table['log_error'], ['system_delete' => 0]);
  }

  private static function _deleteSameLog($log_id) {
    $rep = [
      'delete_result' => false
    ];
    $where = [
      'id' => $log_id // will be where other data from this log soon
    ];
    $log = Amst::get(Info::$system_table['log_error'], ['id', 'code', 'file', 'line'], $where);
    if (isset($log['id'])) {
      // define value which will be the same as target log and delete
      $where = [
        'AND' => [
          'code' => $log['code'],
          'file' => $log['file'],
          'line' => $log['line']
        ]
      ];
      DB::delete(Info::$system_table['log_error'], $where);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | ACTION_LOG
  | Description | ใส่ LOG SUCCESS
   */
  public static function addSuccessLog($code, $c, $fn, $lv, $e, $t, $data) {
    $return = false;
    // CONF [TODO] check open|level|class|fn
    $log = [
      'class'     => $c,
      'func'      => $fn,
      'log_level' => $lv,
      'work_name' => $e, // Event
      'work_type' => $t, // Type
      'work_result' => 'SUCCESS',
      'json_text' => is_array($data) ? json_encode($data) : $data
    ];
    return Amst::insert(info::$system_table['log_action'], $log, ['direct' => true]);
  }

  /**
  | Group | ACTION_LOG
  | Description | ใส่ LOG ERROR
   */
  public static function addErrorLog($code, $c, $fn, $lv, $e, $t, $data) {
    $return = false;
    $log    = [
      'class'       => $c,
      'func'        => $fn,
      'log_level'   => $lv,
      'work_name'   => $e,
      'work_type'   => $t,
      'work_result' => 'ERROR',
      'json_text'   => is_array($data) ? json_encode($data) : $data
    ];
    return Amst::insert(info::$system_table['log_action'], $log);
  }
}