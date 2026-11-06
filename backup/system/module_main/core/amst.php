<?php
class Amst {
  private static $_sys_table_rules        = [];
  private static $_module_table_rules     = [];
  private static $_loaded_module          = [];
  private static $_prevent_recursive_log  = false;
  private static $_transaction_state      = false; // false|ON|DONE
  private static $_transaction_error_list = [];

  /**
   * [IMPORT] Autoloader
   *
   * @author AMST
   * @return NULL
   */
  public static function autoLoad($class_name) {
    // system class
    if (isset(Info::$system_file[$class_name]['path'])) {
      if (!is_null(Info::$system_file[$class_name]['path']) || Info::$system_file[$class_name]['path'] != '') {
        if (!class_exists($class_name)) {
          include_once ROOT_PHP.'/'.Info::$system_file[$class_name]['path'].'.php';
          return;
        }
      }
    }

    // Library
    if (file_exists(ROOT_PHP."/library/{$class_name}.php")) {
      require_once ROOT_PHP."/library/{$class_name}.php";
    }

    // module class
    $functions = Func::selectForAutoload();
    foreach ($functions as $function) {
      if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$function['module']].'/class/')) {
        foreach (glob(ROOT_PHP.'/'.Config_Module::$module_file[$function['module']].'/class/*.php') as $file_name) {
          $file_class_name = explode('/', $file_name);
          $file_class_name = end($file_class_name);
          if ($file_class_name != 'index.php') {
            $file_class_name = substr($file_class_name, 0, strlen($file_class_name) - 4);
            if (!class_exists($file_class_name) && (strtolower($class_name) == strtolower($file_class_name))) {
              include_once $file_name;
              return;
            }
          }
        }
      }
    }
  }

  /**
   * [HELPER] Check if code is sys_, Convert code into fully Table Name.
   *
   * @author AMST
   * @return STRING fully Table name
   */
  public static function checkCode($code) {
    if (strpos($code, 'sys_') !== false) {
      return $code;
    }
    if (strpos($code, Info::module_table_prefix) !== false) {
      return $code;
    }
    return Info::module_table_prefix.$code;
  }

  public static function extractTableCode($code) {
    if (strpos($code, 'sys_') !== false) {
      return 'sys';
    }
    if (strpos($code, Info::module_table_prefix) !== false) {
      return substr($code, strlen('func_'), 5);
    }
    return substr($code, 1, 5);
  }

  /**
   * [UTIL] Check Where is Array and not Empty
   *
   * @author TULA
   * @param ARRAY $where INPUT where
   * @return ARRAY
   */
  public static function checkWhere($where) {
    if (!$where || !is_array($where)) {
      return [];
    }

    // ดักดีไหม ถ้า $where['AND'] = [] จะ ERROR LOG - WHERE ()
    // if (isset($where['AND']) || !is_array($where)) {
    //   return [];
    // }

    return $where;
  }

  /**
  | Group | Helper
  | Description | Return OPTIONS value (Must be Key=>Value)
   */
  public static function getOptionsValue($key, $with_option, $default = false) {
    if (self::isHasOptions($key, $with_option)) {
      return isset($with_option[$key]) ? $with_option[$key] : $default;
    }
    // onlyKeyArray

    return $default;
  }

  /**
  | Group | Helper
  | Description | Check OPTIONS array has key or in array
   */
  public static function isHasOptions($key = '', $with_option = []) {
    if (is_array($with_option)) {
      return (isset($with_option[$key]) || in_array($key, $with_option, true));
    }
    return $with_option === $key;
  }

  /**
   * [UTIL] AutoWhere จาก Frontend เบื้องต้น | Check WHERE is Array and not Empty + Auto where from Fron-end
   *
   * @author TULA
   * @param ARRAY $frontend_where 'insert', 'update'
   * @param ARRAY $backend_where Table INFO
   * @param ARRAY $not_keys Table INFO
   * @return ARRAY
   */
  public static function mergeWhere($frontend_where, $backend_where = [], $not_keys = []) {
    // Validate
    $frontend_where = static::checkWhere($frontend_where);
    $backend_where  = static::checkWhere($backend_where);
    $not_keys       = static::checkOption($not_keys);

    // if ($frontend_where) {
    //   Util::recursive_unset_arraykey($frontend_where, 'AND'); // ไม่ให้ Where เอง
    //   Util::recursive_unset_arraykey($frontend_where, 'OR'); // ไม่ให้ Where เอง
    // }

    foreach ($not_keys as $i_key) {
      unset($frontend_where[$i_key]); // ไม่ให้ Where คีย์นี้
    }
    $backend_where['_frontend_where'] = $frontend_where;
    return static::_setAutoWhere($backend_where);
  }

  /**
   * [UTIL] Get Columns with Required Columns.
   * [20211210] Will not check with db, Let it ERROR (and Speed).
   *
   * @author TULA
   * @param STRING $action 'insert', 'update'
   * @param ARRAY $table_info Table INFO
   * @return ARRAY
   */
  public static function getSelectedFields($options, $required_cols = []) {
    // Validate
    $options       = static::checkOption($options);
    $required_cols = static::checkOption($required_cols);

    $selected_fields = static::getOptionsValue('selected_fields', $options, '*');
    if ($selected_fields !== '*') {
      $selected_fields = array_values(Util::makeArray($selected_fields));
      foreach ($required_cols as $i_required_name) {
        if (!in_array($i_required_name, $selected_fields)) {
          $selected_fields[] = $i_required_name;
        }
      }
    }
    return $selected_fields;
  }

  /**
   * [UTIL] Get LIMIT for MEEDOO.WHERE,
   * USAGE :: $where['LIMIT'] = static::_getPagingLimit($page_no, $page_size);
   *
   * @author TULA
   * @param INT $page_no No. of Current PAGE
   * @param INT $page_size Number of Item in PAGE
   * @return ARRAY of LIMIT for MEEDOO
   */
  private static function _getPagingLimit($page_no = 1, $page_size = 100) {
    if (is_numeric($page_no)) {
      $page_no = intval($page_no);
    } else {
      $page_no = 1;
    }
    if (is_numeric($page_size)) {
      $page_size = intval($page_size);
      if ($page_size <= 0) {
        return false; // show all
      }
    } else if (strtolower($page_size) == 'all') {
      return false;
    } else {
      $page_size = 100;
    }

    $offset = ($page_no > 0) ? ($page_no * $page_size) - $page_size : 0;
    $limit  = ($page_size > 0) ? $page_size : 100;
    return [$offset, $limit];
  }

  /**
   * [UTIL] Check SORT
   *
   * @author TULA
   * @param STRING $action 'insert', 'update'
   * @param ARRAY $table_info Table INFO
   * @return ARRAY
   */
  public static function checkSort($sort) {
    if (!is_array($sort)) {
      return [$sort => 'ASC']; // STRING ColName
    }

    if (Util::isAssocArray($sort)) {
      foreach ($sort as $key => $by) {
        $by = strtoupper($by);
        if ($by == '') {
          $sort[$key] = [$by => 'ASC']; // STRING ColName
        }
      }
    } else {
    }

    return $sort;
  }

  /**
   * [UTIL] Check WHERE is Array and not Empty + Auto where from Fron-end
   *
   * @author TULA
   * @param ARRAY $options INPUT options
   * @return ARRAY
   */
  public static function checkOption($options) {
    if (!$options || !is_array($options)) {
      return [];
    }
    return $options;
  }

  /**
   * [TABLE_INFO] Extract Single Info
   *
   * @author TULA
   * @param STRING $action 'insert', 'update'
   * @param STRING $name column name
   * @param STRING $info_string Table INFO
   * @return ARRAY
   */
  private static function _getValidateType($action, $name, $info_string) {
    $all_data_types = ['TINYINT', 'INT', 'BIGINT', 'DECIMAL', 'CHAR', 'VARCHAR', 'TEXT', 'LONGTEXT', 'DATE', 'TIME', 'DATETIME'];
    $data_types1    = ['TINYINT', 'INT', 'BIGINT', 'TEXT', 'LONGTEXT', 'TIME', 'DECIMAL']; // MAIN TYPE
    $data_types2    = ['CHAR', 'VARCHAR']; // LEN TYPE
    $data_types3    = ['DATE', 'DATETIME']; // NULLABLE TYPE
    $rules          = ['default', 'required', 'id', 'unique', 'numeric', 'positive', 'negative', 'greater_zero', 'min', 'max', 'len', 'min_len', 'max_len'];
    $rules[]        = 'notupdate';
    $rules[]        = 'hash';
    $rules[]        = 'trim';

    $data_type = false;
    // MAIN TYPE
    foreach ($data_types1 as $i_type_name) {
      if (Util::trimDataTypeName($info_string) == $i_type_name) {
        $data_type[strtolower($i_type_name)] = true;
        $i_info_list                         = explode('|', $info_string);
        foreach ($i_info_list as $i_rule) {
          $i_rule_name = Util::trimRuleName($i_rule);
          if (in_array($i_rule_name, $rules)) {
            preg_match_all("/\((.+?)\)/", $i_rule, $matches);
            if (isset($matches[1][0])) {
              $data_type[$i_rule_name] = $matches[1][0];
            } else {
              $data_type[$i_rule_name] = true;
            }
          }
        }
      }
    }
    if (!$data_type) {
      // LEN TYPE
      foreach ($data_types2 as $i_type_name) {
        if (Util::trimDataTypeName($info_string) == $i_type_name) {
          $data_type[strtolower($i_type_name)] = true;
          $i_info_list                         = explode('|', $info_string);
          foreach ($i_info_list as $i_rule) {
            $i_rule_name = Util::trimRuleName($i_rule);
            if (in_array($i_rule_name, $rules)) {
              preg_match_all("/\((.+?)\)/", $i_rule, $matches);
              if (isset($matches[1][0])) {
                $data_type[$i_rule_name] = $matches[1][0];
              } else {
                $data_type[$i_rule_name] = true;
              }
              //[TODO] RULEs
            }
          }
        }
      }
    }
    if (!$data_type) {
      // NULLABLE TYPE
      foreach ($data_types3 as $i_type_name) {
        if (Util::trimDataTypeName($info_string) == $i_type_name) {
          $data_type[strtolower($i_type_name)] = true;
          $i_info_list                         = explode('|', $info_string);
          foreach ($i_info_list as $i_rule) {
            $i_rule_name = Util::trimRuleName($i_rule);
            if (in_array($i_rule_name, $rules)) {
              preg_match_all("/\((.+?)\)/", $i_rule, $matches);
              if (isset($matches[1][0])) {
                $data_type[$i_rule_name] = $matches[1][0];
              } else {
                $data_type[$i_rule_name] = true;
              }
              //[TODO] RULEs
            }
          }
        }
      }
    }

    return ($data_type);
  }

  /**
   * [TABLE_INFO] Extract Module Info to Detailed Array
   *
   * @author TULA
   * @param STRING $action 'insert', 'update'
   * @param ARRAY $table_info Table INFO
   * @return ARRAY
   */
  public static function getTableValidateData($action, $table_info) {
    $return = [
      'validate_rules' => [],
      'insert_fields'  => [],
      'update_fields'  => []
    ];

    $validate_rules = [];
    foreach ($table_info as $i_name => $i_info) {
      $data_type = static::_getValidateType($action, $i_name, $i_info);
      if ($data_type) {
        $validate_rules[$i_name] = $data_type;
      }
    }
    if ($validate_rules) {
      $return['validate_rules'] = $validate_rules;
      foreach ($validate_rules as $key => $i_rules) {
        if (!isset($i_rules['notinsert'])) {
          $return['insert_fields'][$key] = static::_translateLabel($key, $lang = 'en'); // [TODO]
        }
        if (!isset($i_rules['notupdate'])) {
          $return['update_fields'][$key] = static::_translateLabel($key, $lang = 'en'); // [TODO]
        }
      }
    }

    return $return;
  }

  /**
   * [TABLE_INFO] Get Table Info SYS_ or _CODE_ *SET as CACHE
   *
   * @author TULA
   * @param STRING $code Table Code
   * @param STRING $action 'insert', 'update'
   * @return ARRAY
   */
  private static function _loadTableInfo($code, $action = 'insert') {
    $return            = [];
    $allow_action_list = ['insert', 'update'];

    $module_code   = '';
    $module_name   = '';
    $table_postfix = strtolower(trim($code));
    if (Util::startsWith($table_postfix, 'sys_')) {
      // SYS_
      static::_setSystemTableInfo($table_postfix, $action = 'insert');
      $return = isset(static::$_sys_table_rules[$action][$table_postfix]) ? static::$_sys_table_rules[$action][$table_postfix] : [];

      // Log::logBackendShouldKnow('setSystemTableInfo', ['table_code' => $code, 'table_postfix' => $table_postfix, 'module_code' => $module_code, 'module_name' => $module_name]);
    } else {
      // _CODE_
      $table_postfix = str_replace(Info::module_table_prefix, '', $table_postfix); // CLEAR PRE_FIX
      $module_code   = substr($table_postfix, 0, strpos($table_postfix, '_')); // CLEAR CODE
      $table_postfix = substr($table_postfix, strpos($table_postfix, '_')); // CLEAR CODE

      if (strlen($module_code) == 5) {
        //_loaded_module [CACHE?]
        $model = Func::getByCode(['id', 'module'], $module_code);
        if (isset($model['module'])) {
          $module_name = $model['module'];
          static::_setModuleTableInfo($module_name, $action);
        } else {
          Log::logBackendShouldKnow('Module not Found', ['table_code' => $code, 'module_code' => $module_code, 'model' => $model]);
        }
        $return = isset(static::$_module_table_rules[$module_name][$action][$table_postfix]) ? static::$_module_table_rules[$module_name][$action][$table_postfix] : [];
      }

      // Log::logBackendShouldKnow('setModuleTableInfo', ['table_code' => $code, 'table_postfix' => $table_postfix, 'module_code' => $module_code, 'module_name' => $module_name]);
    }

    return $return;
  }

  /**
   * [TABLE_INFO] Set Table Info SYS_ as CACHE
   *
   * @author TULA
   * @param STRING $module_name module in Func
   * @param STRING $action 'insert', 'update'
   * @return TRUE
   */
  private static function _setSystemTableInfo($table_name, $action = 'insert') {
    $return = false;

    if (!isset(Info::$system_tables[$table_name])) {
      Log::add(1, "Invalid SYSTEM Info as '{$table_name}'");
      return $return;
    }

    if (Info::$system_tables[$table_name] && !isset(static::$_sys_table_rules[$action][$table_name])) {
      // [CACHE][SET]
      static::$_sys_table_rules[$action][$table_name] = static::getTableValidateData($action, Info::$system_tables[$table_name]);
      $return                                         = true;
    }

    return $return;
  }

  /**
   * [TABLE_INFO] Set Table Info _CODE_ as CACHE
   *
   * @author TULA
   * @param STRING $module_name module in Func
   * @param STRING $action 'insert', 'update'
   * @return TRUE
   */
  private static function _setModuleTableInfo($module_name, $action = 'insert') {
    $return = false;

    // [CACHE] - GET_ONCE each
    if ($module_name && !isset(static::$_module_table_rules[$module_name][$action])) {
      if (isset(Config_Module::$module_file[$module_name]) && file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/')) {
        // Load ทั้ง Folder
        $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/');
        foreach ($files as $file) {
          $module_tables = null; // INIT
          if (('.' != $file) && ('..' != $file) && (pathinfo($file, PATHINFO_EXTENSION) == 'php')) {
            include ROOT_PHP.'/'.Config_Module::$module_file[$module_name].'/database/table/'.$file;
            if (isset($module_tables)) {
              foreach ($module_tables as $i_table_name => $i_table_data) {
                // [CACHE][SET]
                static::$_module_table_rules[$module_name][$action][$i_table_name] = static::getTableValidateData($action, $i_table_data);
                $return                                                            = true;
              }
            }
          }
        }
      } else {
        Log::logBackendShouldKnow('Invalid INFO', ['table_code' => $code, 'module_name' => $module_name]);
      }
    }

    return $return;
  }

  /**
   * [TABLE_INFO] Convert Input before INSERT/UPDATE.
   *
   * @author TULA
   * @param STRING $value Input
   * @param ARRAY $rule single rule from INFO
   * @return STRING
   */
  private static function _setInput($value, $rule = []) {
    if (isset($rule['hash'])) {
      if ($rule['hash'] == 'md5') {
        return md5($value);
      } else {
        // [LATER] other encyrpt
      }
    }

    return $value;
  }

  /**
   * [TABLE_INFO] Convert Input before INSERT/UPDATE.
   *
   * @author TULA
   * @param STRING $rule Single Info
   * @param ARRAY $inputs all input Data
   * @param STRING $key Col name
   * @param STRING $label to Display
   * @return ARRAY API
   */
  private static function _validateInput($rule, $inputs, $key, $label) {
    $return = false;

    // Required
    // Use array_key_exists() instead of isset(), because isset() will return false if the variable is null,
    // whereas array_key_exists() just checks if the key exists in the array:
    // ปรับกฏ
    // if (isset($rule['required']) && !array_key_exists($key, $inputs)) {
    //   return Amst::repError('E0001', $label);
    // }

    // NO ARRAY on field
    if (isset($inputs[$key]) && is_array($inputs[$key])) {
      return Amst::repError('E0007', $label);
    }

    // [TRIM] IS NEED to be before Validate
    if (isset($rule['trim'])) {
      $inputs[$key] = trim($inputs[$key]);
    }

    // [DATE] *unused format 'cause 'Y-m-d' != 'YYYY-MM-DD'
    if (isset($rule['date']) && array_key_exists($key, $inputs)) {
      if ($inputs[$key] == '') {
        return 'set_as_null';
      }
      if (is_null($inputs[$key]) || Util::checkDate($inputs[$key])) {
        // OK
      } else {
        return Amst::repError('E0011', $label, ['key' => $key, 'input' => $inputs[$key]]);
      }
    }

    // NULLABLE [DATE]/[DATETIME]
    if (isset($rule['datetime']) && array_key_exists($key, $inputs) && !(is_null($inputs[$key]) || Util::checkDateTime($inputs[$key]))) {
      return Amst::repError('E0012', $label."({$inputs[$key]})");
    }

    if (isset($rule['time']) && array_key_exists($key, $inputs) && !(Util::checkDate($inputs[$key], 'H:i:s') || Util::checkDate($inputs[$key], 'H:i'))) {
      if (is_null($inputs[$key])) {
        // OK
      } else {
        return Amst::repError('E0010', $label);
      }
    }

    // [ID]
    if (isset($rule['id']) && array_key_exists($key, $inputs)) {
      $uid = filter_var($inputs[$key], FILTER_VALIDATE_INT); // -1,0,1 is UID
      if (($uid <= 0) || ("{$uid}" !== "{$inputs[$key]}")) {
        return Amst::repError('E0008', $label);
      }
    }

    // [NUMERIC]
    if (isset($rule['min']) && array_key_exists($key, $inputs) && ($inputs[$key] < $rule['min'])) {
      return Amst::repError('E0019', [$label, $rule['min']]);
    }

    if (isset($rule['max']) && array_key_exists($key, $inputs) && ($inputs[$key] > $rule['max'])) {
      return Amst::repError('E0020', [$label, $rule['max']]);
    }

    if (isset($rule['numeric']) && array_key_exists($key, $inputs)) {
      if (!is_numeric($inputs[$key])) {
        return Amst::repError('E0013', $label);
      }

      $v = $rule['numeric'];

      if (($v == 'positive') && ($inputs[$key] < 0)) {
        return Amst::repError('E0014', $label);
      }

      if (($v == 'greater_zero') && ($inputs[$key] <= 0)) {
        return Amst::repError('E0015', $label);
      }

      if (($v == 'min') && ($inputs[$key] < $rule['min'])) {
        return Amst::repError('E0019', [$label, $rule['min']]);
      }

      if (($v == 'max') && ($inputs[$key] > $rule['max'])) {
        return Amst::repError('E0020', [$label, $rule['min']]);
      }
    } else {
      // STRING
      // 'E0016' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาว %s', // Strlen
      // 'E0017' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวน้อยกว่า %s ', // Strlen
      // 'E0018' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวมากกว่า %s', // Strlen
      if (isset($rule['len']) && array_key_exists($key, $inputs) && (strlen("{$inputs[$key]}") != $rule['len'])) {
        return Amst::repError('E0016', [$label, $rule['len']]);
      }
      if (isset($rule['max_len']) && array_key_exists($key, $inputs) && (strlen("{$inputs[$key]}") > $rule['max_len'])) {
        return Amst::repError('E0017', [$label, $rule['max_len']]);
      }
      if (isset($rule['min_len']) && array_key_exists($key, $inputs) && (strlen("{$inputs[$key]}") < $rule['min_len'])) {
        return Amst::repError('E0018', [$label, $rule['min_len']]);
      }
    }

    return true;
  }

  /**
   * [TABLE_INFO] Translate label. [TODO/LATER]
   *
   * @author TULA
   * @param STRING $name Single Info
   * @param STRING $lang Col name
   * @return STRING
   */
  private static function _translateLabel($name, $lang = 'en') {
    if ($name == 'owner_id') {
      return 'Owner ID';
    }
    return $name;
  }

  /**
  | Group | SELECT/GET
  | Description | 1.ส่งข้อมูลลิสเปล่า กลับตาม Options.
  | $options | [no_key(แบบไม่มีคีย์), show_all(เอาข้อมูลที่ถูกลบไปด้วย), random(ดึงข้อมูลแล้วเรียงแบบสุ่ม), log_last_query(Log ข้อมูลที่ใช้ในการ SELECT), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
  | $err_msg | ถ้ามีจะ LOG ERROR ไว้ให้ด้วย
   */
  public static function selectEmptyList($options = [], $err_msg = '') {
    $return = []; // LIST

    $options = Amst::checkOption($options);
    if ($err_msg) {
      Log::add(3, $err_msg, 0, 'backend');
      $options['err_msg'] = is_array($err_msg) ? json_encode($err_msg, JSON_UNESCAPED_UNICODE) : "{$err_msg}";
    }
    $debuger = $options;

    // OUTPUT
    if (static::isHasOptions('total_count', $options)) {
      $return = [
        'total_count' => 0,
        'list'        => []
      ];
      if (static::isHasOptions('debug', $options)) {
        $return['_debug'] = $debuger;
      }
      return $return;
    } else {
      if (static::isHasOptions('debug', $options)) {
        return [
          'list'   => [],
          '_debug' => $debuger
        ];
      }
    }

    return $return;
  }

  private static function __withdb__setAutoWhere($code, $where) {
    $code = static::checkCode($code);

    // [WHERE][AUTO]
    $where = static::checkWhere($where);
    if (isset($where['_frontend_where'])) {
      $_frontend_where = $where['_frontend_where'];

      $preload_data = [];

      foreach (DB::selectTableStructure($code) as $i_col) {
        $preload_data[$i_col['name']] = $i_col; // [TODO] Rules
      }

      // [TODO] Check Where-Table
      foreach ($_frontend_where as $key => $value) {
        // Has Cols
        if (isset($preload_data[$key])) {
          if (Util::startsWith($key, 'is_')) {
            if (!Util::isEmptyString($value) && !Util::compareKey($value, 'all')) {
              $where[$key] = trim($value); // Boolean
            }
          } else if (strpos($key, 'status') !== false) {
            $where[$key] = trim($value); // STATUS
          } else {
            if ($preload_data[$key]['data_type'] === 'varchar') {
              $i_str = trim("{$value}"); // Auto LIKE on VARCHAR
              if ($i_str) {
                $where["{$key}[~]"] = $i_str;
              }
            } else {
              // [LATER] what if you want to search "" ???
              $i_str = trim("{$value}"); // Auto LIKE on VARCHAR
              if ($i_str) {
                $where[$key] = $value;
              }
            }
          }
        } else {
          // Extra known
          // input.t -> db.d
          // if (Util::endsWith($key, '_date_time')) {
          //   $db_key = substr($key, -5);
          //   if (isset($preload_data[$db_key])) {
          //     $where[$key] = Util::getSystemDate($value);
          //   }
          // }
          // // input.d -> db.t
          // if (Util::endsWith($key, '_date')) {
          //   $db_key = "{$key}_time";
          //   if (isset($preload_data[$db_key]) && Util::checkDate("{$value}")) {
          //       $where['AND #date_between']['booking_date[>=]'] = "{$value['booking_date']} 00:00:00";
          //       $where['AND #date_between']['booking_date[<=]'] = "{$value['booking_date']} 23:59:59";
          //     }
          //     $where[$key] = Util::getSystemDate($value);
          //   }
          // }

          // meedoo-fn -- NOT ALLOW
          // if (Util::endswith($key, ']')) {
          //   $where[$key] = trim($value);
          // }
        }
      }
      unset($where['_frontend_where']);
      // unset($_frontend_where);
    }
    return $where;
  }

  private static function _setAutoWhere($where) {
    // [WHERE][AUTO]
    $where = static::checkWhere($where); // หน้าบ้านชอบส่ง สตริงเปล่ามา
    if (isset($where['_frontend_where'])) {
      $_frontend_where = $where['_frontend_where'];

      // [TODO] Check Where-Table
      foreach ($_frontend_where as $key => $value) {
        // Has Cols
        if (Util::startsWith($key, 'is_')) {
          if (!Util::isEmptyString($value) && !Util::compareKey($value, 'all')) {
            $where[$key] = trim($value); // Boolean
          }
        } else if (strpos($key, 'status') !== false) {
          $where[$key] = trim($value); // STATUS
        } else {
          // error on array $i_str = trim("{$value}"); // Auto LIKE on VARCHAR
          $i_str = ($value); // Auto LIKE on VARCHAR
          if (strlen("{$i_str}")) {
            $where[$key] = $value;
          }
        }
      }
      unset($where['_frontend_where']);
    }
    return $where;
  }

  public static function setAutoWhereTest($code, $where = []) {
    $code = static::checkCode($code);

    // $rep = static::_loadTableInfo($code, $action = 'insert');
    $rep['code']                 = $code;
    $rep['selectTableStructure'] = DB::selectTableStructure($code);
    return Amst::repSuccess($rep);

    // [WHERE][AUTO]
    $where = static::checkWhere($where);
    if (isset($where['_frontend_where']) && $where['_frontend_where']) {
      $_frontend_where = $where['_frontend_where'];

      $preload_data = [];
      $table_info   = DB::selectTableStructure($code);
      if (isset($table_info[$code]) && $table_info[$code]) {
        foreach ($_frontend_where as $i_col) {
          $preload_data[$i_col['name']] = $i_col; // [TODO] Rules
        }
      }
      // [TODO] Check Where-Table
      foreach ($_frontend_where as $key => $value) {
        if (Util::startsWith($key, 'is_')) {
          if (!Util::isEmptyString($value) && !Util::compareKey($value, 'all')) {
            $where[$key] = trim($value);
          }
        } else {
          if (isset($preload_data[$key])) {
            if ($preload_data[$key]['data_type'] === 'varchar') {
              $where["{$key}[~]"] = trim($value); // Auto LIKE on VARCHAR
            } else {
              $where[$key] = $value;
            }
          } else {
            $where[$key] = $value;
          }
        }
      }
      unset($where['_frontend_where']);
      // unset($_frontend_where);
    }
  }

  /**
  | Group | SELECT/GET
  | Description | 2.ดึงข้อมูลลิสเป็น Array คีย์เป็น id.
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [no_key(แบบไม่มีคีย์), show_all(เอาข้อมูลที่ถูกลบไปด้วย), random(ดึงข้อมูลแล้วเรียงแบบสุ่ม), log_last_query(Log ข้อมูลที่ใช้ในการ SELECT), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function select($code, $columns = '*', $where = [], $options = []) {
    $return = []; // LIST

    if (static::getOptionsValue('log_last_query', $options, false)) {
      $debuger = [
        'fn'      => 'select',
        'code'    => $code,
        'columns' => $columns,
        'where'   => $where,
        'options' => $options
      ];
    }

    $code        = static::checkCode($code);
    $module_code = static::extractTableCode($code);
    $where       = static::checkWhere($where);

    // [COUNTING]
    $count_options  = []; // [Later] select_options VS count_option
    $total_count    = 0;
    $is_total_count = static::isHasOptions('total_count', $options);
    $page_no        = static::getOptionsValue('page_no', $options, 0);
    $page_size      = static::getOptionsValue('page_size', $options, 100);
    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    } else {
      $count_options = [
        'show_all' => true
      ];
    }

    // [SORT]
    if ($sort = static::getOptionsValue('sort', $options, false)) {
      $where['ORDER'] = static::checkSort($sort);
    } else if ($sort_col = static::getOptionsValue('sort_col', $options, false)) {
      $sort_by        = static::getOptionsValue('sort_by', $options, 'ASC');
      $where['ORDER'] = static::checkSort(["{$sort_col}" => $sort_by]);
    }

    // [EXPORT][EXCEL] without PAGING
    if (static::getOptionsValue('export', $options, false) == 'excel') {
      $page_no = 0;
    }

    // [TODO] validate table&cols
    if ($columns) {
      // SELECT + LIMIT
      if ($page_no > 0) {
        if ($is_total_count) {
          $total_count = static::count($code, $where, $count_options);
          if (isset($debuger)) {
            $debuger['last_query'][] = DB::lastQuery();
          }
        }
        $where['LIMIT'] = static::_getPagingLimit($page_no, $page_size);
        if (static::getOptionsValue('random', $options, false)) {
          $record_list = DB::selectRandom($code, $columns, $where);
        } else {
          $record_list = DB::select($code, $columns, $where);
        }
        if (isset($debuger)) {
          $debuger['last_query'][] = DB::lastQuery();
        }
      } else {
        if (static::getOptionsValue('random', $options, false)) {
          $record_list = DB::selectRandom($code, $columns, $where);
        } else {
          $record_list = DB::select($code, $columns, $where);
        }
        if (isset($debuger)) {
          $debuger['last_query'][] = DB::lastQuery();
        }
        if ($is_total_count && $record_list) {
          $total_count = count($record_list);
        }
      }
    }

    // [EXPORT][EXCEL]
    if (static::getOptionsValue('export', $options, false) == 'excel') {
      if (!$record_list || !is_array($record_list)) {
        $record_list = [];
      }
      if (isset($debuger)) {
        $rep['return'] = 'EXPORTED EXCEL';
        Log::add(0, $debuger, 0, 'backend');
      }
      return Excel::exportArray("export", $record_list);
    }

    // Did not do additional Work on Empty.
    if (!isset($record_list) || !$record_list) {
      $record_list = [];
    } else {
      // AddList/DetailList
      // $ex_image = [
      //   [
      //     'key' => $code.'_food_info_', // will append data id after this for each row
      //     'name' => 'image', // optional if not send it will be image_x
      //     'target_column' => 'image' // optional default is 'id' but may be we use different column
      //     // [LATER] 'place_holder' => '', // File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
      //     // [LATER] 'no_date' => false,
      //   ]
      // ];
      $img_conf = static::getOptionsValue('image', $options, false);
      if ($img_conf) {
        $img_ids  = [];
        $def_path = File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
        foreach ($img_conf as $i_key => $i_conf) {
          if (isset($i_conf['key'])) {
            $i_conf['code'] = $i_conf['key']; // [compatibility]
          } else if (is_string($i_conf)) {
            $i_conf = ['code' => "{$i_conf}"]; // direct String
          }
          if (isset($i_conf['code'])) {
            $i_code = $i_conf['code'];
            //$i_code = (Util::endsWith($i_conf['code'], '_')) ? $i_conf['code'] : "{$code}_";
            $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
            $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
            foreach ($record_list as $key => $record) {
              if (isset($i_conf['target_column']) && $i_conf['target_column']) {
                if (isset($record[$i_conf['target_column']])) {
                  $img_ids[] = $i_code.$record[$i_conf['target_column']];
                }
              } else {
                // [AUTO] Extract
                $output_code              = $i_code;
                $use_extractCurlyBrackets = [
                  'i_code'      => $i_code,
                  'output_code' => $output_code
                ];
                foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
                  if ($e_key['data_key'] == 'code') {
                    $output_code = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
                  } else if (isset($record[$e_key['data_key']])) {
                    $output_code                           = str_replace($e_key['code_key'], $record[$e_key['data_key']], $output_code); // replace data
                    $use_extractCurlyBrackets['has_key'][] = [$e_key, $record[$e_key['data_key']], $output_code];
                  } else {
                    $use_extractCurlyBrackets['no_key'][] = $e_key;
                  }
                }
                $img_ids[] = $output_code;
                // $img_ids[] = $i_code; // no postfix
              }
              $record_list[$key][$i_img_key] = $def_path;
              if (isset($i_conf['debug'])) {
                $record_list[$key]['_i_code'.$i_key]            = $i_code;
                $record_list[$key]['_img_output_column'.$i_key] = $i_img_key;
              }
            }
          }
          if (isset($i_conf['debug'])) {
            $debug = [
              'code'    => $code,
              'img_ids' => $img_ids
            ];
            Log::logBackendShouldKnow('debug img_ids', $debug);
          }
        }

        if ($img_ids) {
          $_select_path_opt = ['placeholder' => '', 'no_date' => 1]; // [LATER]
          $_select_path_opt = [];
          if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
            $img_list = File::selectFixedPath($img_ids, $_select_path_opt);
          } else {
            $img_list = File::selectPath($img_ids, $_select_path_opt);
          }
          foreach ($img_conf as $i_key => $i_conf) {
            if (isset($i_conf['key'])) {
              $i_conf['code'] = $i_conf['key']; // [compatibility]
            } else if (is_string($i_conf)) {
              $i_conf = ['code' => "{$i_conf}"]; // direct String
            }
            if (isset($i_conf['code'])) {
              $i_code = $i_conf['code'];
              // $i_code = (Util::startsWith($i_conf['code'], '_')) ? $code.$i_conf['code'] : $i_conf['code'];
              $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
              $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
              foreach ($record_list as $key => $record) {
                if (isset($i_conf['debug']) && ($i_conf['debug'] == 'img_list')) {
                  $record_list[$key]['debug_img_list'] = $img_list;
                }

                if (isset($i_conf['target_column']) && $i_conf['target_column']) {
                  if (isset($record[$i_conf['target_column']])) {
                    $record_list[$key][$i_img_key] = $img_list[$i_code.$record[$i_conf['target_column']]];
                  }
                } else {
                  // [AUTO] Extract
                  $output_code              = $i_code;
                  $use_extractCurlyBrackets = [
                    'i_code'      => $i_code,
                    'output_code' => $output_code
                  ];
                  foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
                    if ($e_key['data_key'] == 'code') {
                      $use_extractCurlyBrackets['add'][] = $e_key;
                      $output_code                       = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
                    } else if (isset($record[$e_key['data_key']])) {
                      $use_extractCurlyBrackets['add'][] = $e_key;
                      $output_code                       = str_replace($e_key['code_key'], $record[$e_key['data_key']], $output_code); // replace data
                    } else {
                      $use_extractCurlyBrackets['no_key'][] = $e_key;
                    }
                  }
                  if (isset($i_conf['debug'])) {
                    $record_list[$key]['use_extractCurlyBrackets'] = $use_extractCurlyBrackets;
                  }
                  if (isset($img_list[$output_code])) {
                    $record_list[$key][$i_img_key] = $img_list[$output_code];
                  }
                }
              }
            }
          }
          unset($img_list);
          unset($img_ids);
        }
      }

      // NO_KEY
      if (!static::getOptionsValue('no_key', $options, false) && isset($record_list[0]['id'])) {
        $temp_records = [];
        // re arrange array if id available
        foreach ($record_list as $result) {
          $temp_records[$result['id']] = $result;
        }
        $record_list = $temp_records;
        unset($temp_records);
      }
    }

    //NO DEFAULT COLUMNS
    if (isset($options['no_def']) && $options['no_def']) {
      $def_columns = [
        'insert_date_time',
        'insert_user_id',
        'insert_detail',
        'update_date_time',
        'update_user_id',
        'update_detail',
        'sort_order_id',
        'version',
        'system_delete'
      ];

      $temps = [];
      foreach ($record_list as $key => $result) {
        foreach ($def_columns as $def_column) {
          unset($result[$def_column]);
        }
        $temps[$key] = $result;
      }
      $record_list = $temps;
      unset($temps);
    }

    // OUTPUT
    if (isset($debuger)) {
      $debuger['driver_info'] = DB::info();
      Log::add(0, $debuger, 0, 'backend');
    }

    //AUTO DECODE JSON
    // $record = reset($record_list);
    // if ($record) {
    //   $key_jsons = [];
    //   foreach ($record as $key => $value) {
    //     if (util::isJson($value)) {
    //       $key_jsons[] = $key;
    //     }
    //   }

    //   if ($key_jsons) {
    //     //Decode JSON all record_list
    //     foreach ($record_list as $key => $record) {
    //       foreach ($key_jsons as $key_json) {
    //         if (isset($record[$key_json])) {
    //           $decode            = json_decode($record[$key_json], true);
    //           $record[$key_json] = $decode;
    //         }
    //       }
    //       $record_list[$key] = $record;
    //     }
    //   }
    // }

    // OUTPUT
    if ($is_total_count) {
      $return = [
        'total_count' => $total_count,
        'list'        => ($record_list)
      ];
      // if (static::isHasOptions('debug', $options)) {
      //   $return['_debug'] = $debuger;
      // }
      return $return;
    } else {
      // if (static::isHasOptions('debug', $options)) {
      //   return [
      //     'list'   => ($record_list),
      //     '_debug' => $debuger
      //   ];
      // }
    }

    return $record_list;
  }

  public static function selectByQueryString($code, $query_string, $options = []) {
    $return = []; // LIST

    $code        = static::checkCode($code);
    $module_code = static::extractTableCode($code);

    $is_total_count = static::isHasOptions('total_count', $options);
    $total_count    = 0; // เผื่อคนยังใช้
    $page_no        = static::getOptionsValue('page_no', $options, 0);
    $page_size      = static::getOptionsValue('page_size', $options, 100);

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    $sort = static::getOptionsValue('sort', $options, false);
    if ($sort) {
      $where['ORDER'] = ($sort);
    }

    // Check Columns
    if (!$query_string) {
      return Amst::selectEmptyList($options);
    }

    // [EXPORT][EXCEL] without PAGING
    if (static::getOptionsValue('export', $options, false) == 'excel') {
      $page_no = 0;
    }

    // SELECT + LIMIT
    if ($page_no > 0) {
      if ($is_total_count) {
        $total_count              = static::count($code, $where);
        $debuger['_last_query'][] = DB::lastQuery();
      }
      $where['LIMIT'] = static::_getPagingLimit($page_no, $page_size);
      if (static::getOptionsValue('random', $options, false)) {
        $record_list = Amst::queryFetchAll($query_string);
      } else {
        // DB::phpLeftJoin($code, $sql_select, $sql_where, $sql_order, $sql_limit)
        $record_list = Amst::queryFetchAll($query_string);
      }
      $debuger['_last_query'][] = DB::lastQuery();
    } else {
      if (static::getOptionsValue('random', $options, false)) {
        $record_list = Amst::queryFetchAll($query_string);
      } else {
        $record_list = Amst::queryFetchAll($query_string);
      }
      $debuger['_last_query'][] = DB::lastQuery();
      if ($is_total_count && $record_list) {
        $total_count = count($record_list);
      }
    }

    // [EXPORT][EXCEL]
    if (static::getOptionsValue('export', $options, false) == 'excel') {
      return Excel::exportArray("export", $record_list);
    }

    // Did not do additional Work on Empty.
    if (!$record_list) {
      $record_list = [];
    } else {
      // AddList/DetailList
      // $ex_image = [
      //   [
      //     'key' => $code.'_food_info_', // will append data id after this for each row
      //     'name' => 'image', // optional if not send it will be image_x
      //     'target_column' => 'image' // optional default is 'id' but may be we use different column
      //     // [LATER] 'place_holder' => '', // File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
      //     // [LATER] 'no_date' => false,
      //   ]
      // ];
      $img_conf = static::getOptionsValue('image', $options, false);
      if ($img_conf) {
        $img_ids  = [];
        $def_path = File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
        foreach ($img_conf as $i_key => $i_conf) {
          if (isset($i_conf['key'])) {
            $i_conf['code'] = $i_conf['key']; // [compatibility]
          } else if (is_string($i_conf)) {
            $i_conf = ['code' => "{$i_conf}"]; // direct String
          }
          if (isset($i_conf['code'])) {
            $i_code = $i_conf['code'];
            //$i_code = (Util::endsWith($i_conf['code'], '_')) ? $i_conf['code'] : "{$code}_";
            $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
            $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
            foreach ($record_list as $key => $record) {
              if (isset($i_conf['target_column']) && $i_conf['target_column']) {
                if (isset($record[$i_conf['target_column']])) {
                  $img_ids[] = $i_code.$record[$i_conf['target_column']];
                }
              } else {
                // [AUTO] Extract
                $output_code              = $i_code;
                $use_extractCurlyBrackets = [
                  'i_code'      => $i_code,
                  'output_code' => $output_code
                ];
                foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
                  if ($e_key['data_key'] == 'code') {
                    $output_code = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
                  } else if (isset($record[$e_key['data_key']])) {
                    $output_code                           = str_replace($e_key['code_key'], $record[$e_key['data_key']], $output_code); // replace data
                    $use_extractCurlyBrackets['has_key'][] = [$e_key, $record[$e_key['data_key']], $output_code];
                  } else {
                    $use_extractCurlyBrackets['no_key'][] = $e_key;
                  }
                }
                $img_ids[] = $output_code;
                // $img_ids[] = $i_code; // no postfix
              }
              $record_list[$key][$i_img_key] = $def_path;
              if (isset($i_conf['debug'])) {
                $record_list[$key]['_i_code'.$i_key]            = $i_code;
                $record_list[$key]['_img_output_column'.$i_key] = $i_img_key;
              }
            }
          }
          if (isset($i_conf['debug'])) {
            $debug = [
              'code'    => $code,
              'img_ids' => $img_ids
            ];
            Log::logBackendShouldKnow('debug img_ids', $debug);
          }
        }

        if ($img_ids) {
          $_select_path_opt = ['placeholder' => '', 'no_date' => 1]; // [LATER]
          $_select_path_opt = [];
          if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
            $img_list = File::selectFixedPath($img_ids, $_select_path_opt);
          } else {
            $img_list = File::selectPath($img_ids, $_select_path_opt);
          }
          foreach ($img_conf as $i_key => $i_conf) {
            if (isset($i_conf['key'])) {
              $i_conf['code'] = $i_conf['key']; // [compatibility]
            } else if (is_string($i_conf)) {
              $i_conf = ['code' => "{$i_conf}"]; // direct String
            }
            if (isset($i_conf['code'])) {
              $i_code = $i_conf['code'];
              // $i_code = (Util::startsWith($i_conf['code'], '_')) ? $code.$i_conf['code'] : $i_conf['code'];
              $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
              $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
              foreach ($record_list as $key => $record) {
                if (isset($i_conf['debug']) && ($i_conf['debug'] == 'img_list')) {
                  $record_list[$key]['debug_img_list'] = $img_list;
                }

                if (isset($i_conf['target_column']) && $i_conf['target_column']) {
                  if (isset($record[$i_conf['target_column']])) {
                    $record_list[$key][$i_img_key] = $img_list[$i_code.$record[$i_conf['target_column']]];
                  }
                } else {
                  // [AUTO] Extract
                  $output_code              = $i_code;
                  $use_extractCurlyBrackets = [
                    'i_code'      => $i_code,
                    'output_code' => $output_code
                  ];
                  foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
                    if ($e_key['data_key'] == 'code') {
                      $use_extractCurlyBrackets['add'][] = $e_key;
                      $output_code                       = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
                    } else if (isset($record[$e_key['data_key']])) {
                      $use_extractCurlyBrackets['add'][] = $e_key;
                      $output_code                       = str_replace($e_key['code_key'], $record[$e_key['data_key']], $output_code); // replace data
                    } else {
                      $use_extractCurlyBrackets['no_key'][] = $e_key;
                    }
                  }
                  if (isset($i_conf['debug'])) {
                    $record_list[$key]['use_extractCurlyBrackets'] = $use_extractCurlyBrackets;
                  }
                  if (isset($img_list[$output_code])) {
                    $record_list[$key][$i_img_key] = $img_list[$output_code];
                  }
                }
              }
            }
          }
          unset($img_list);
          unset($img_ids);
        }
      }

      // [NO_KEY]
      if (!static::getOptionsValue('no_key', $options, false) && isset($record_list[0]['id'])) {
        // reMake key-array if id available.
        $temp_records = [];
        foreach ($record_list as $result) {
          $temp_records[$result['id']] = $result;
        }
        $record_list = $temp_records;
        unset($temp_records);
      }
    }

    // [OUTPUT]
    if ($is_total_count) {
      $return = [
        'total_count' => $total_count,
        'list'        => ($record_list)
      ];
      if (static::isHasOptions('debug', $options)) {
        $return['_debug'] = $debuger;
      }
      return $return;
    } else {
      if (static::isHasOptions('debug', $options)) {
        return [
          'list'   => ($record_list),
          '_debug' => $debuger
        ];
      }
    }
    return $record_list;
  }

  /**
  | Group | SELECT/GET
  | Description | 3.ดึงข้อมูลลิส แบบไม่มีคีย์.
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(เอาข้อมูลที่ถูกลบไปด้วย)]
   */
  public static function selectNoKey($code, $columns = '*', $where = [], $options = []) {
    $return            = []; // LIST
    $options           = static::checkOption($options);
    $options['no_key'] = true;
    return static::select($code, $columns, $where, $options);
  }

  /**
  | Group | SELECT/GET
  | Description | 3.ดึงข้อมูลลิส แบบไม่มีคีย์ *+Log Last Query ให้ด้วย ให้เอาออกก่อนส่งงาน
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(เอาข้อมูลที่ถูกลบไปด้วย)]
   */
  public static function selectNoKeyWithLog($code, $columns = '*', $where = [], $options = []) {
    $return                    = []; // LIST
    $options                   = static::checkOption($options);
    $options['no_key']         = true;
    $options['log_last_query'] = true;
    return static::select($code, $columns, $where, $options);
  }

  /**
  | Group | SELECT/GET
  | Description | 4.ดึงข้อมูล ที่ถูกลบไปด้วย
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(เอาข้อมูลที่ถูกลบไปด้วย)]
   */
  public static function selectAll($code, $columns = '*', $where = [], $options = []) {
    $return              = []; // LIST
    $options             = static::checkOption($options);
    $options['show_all'] = true;
    return static::select($code, $columns, $where, $options);
  }

  public static function extractCurlyBrackets($code, $text) {
    $return = []; // LIST
    // $text = '{$code}_im{a}ge_{$id}_{$category_id}';
    preg_match_all("/\{[^\}]*\}/", $text, $matches);
    // preg_match_all("/\[[^\]]*\]/", $text, $matches);
    // preg_match_all("/@\\[([0-9]+)\\]/", $example_message, $matches);
    // $return = ($matches[0]);

    if (isset($matches[0])) {
      foreach ($matches[0] as $key) {
        if (Util::startsWith($key, '{$')) {
          $key      = substr($key, 2, strlen($key) - 3);
          $return[] = [
            'data_key' => $key,
            'code_key' => '{$'.$key.'}'
          ];
        } else if (Util::startsWith($key, '{')) {
          $key      = substr($key, 1, strlen($key) - 2);
          $return[] = [
            'data_key' => $key,
            'code_key' => "{{$key}}"
          ];
        } else {
          // $return[] = [
          //   'data_key' => $key,
          //   'code_key' => $key,
          // ];
        }
      }
      $return = array_values($return);
    }
    return $return;
  }

  /**
  | Group | SELECT/GET
  | Description | 5.ดึงข้อมูลเจาะจง row
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
   */
  public static function get($code, $columns = '*', $where = [], $options = []) {
    $return = null;

    if (static::getOptionsValue('log_last_query', $options, false)) {
      $debuger = [
        'code'    => $code,
        'columns' => $columns,
        'where'   => $where,
        'options' => $options
      ];
    }

    // initial data
    $code        = static::checkCode($code);
    $module_code = static::extractTableCode($code);
    // Validate
    if (!is_array($where)) {
      $debug = [
        'code'    => $code,
        'columns' => $columns,
        'where'   => $where,
        'options' => $options
      ];
      Log::logBackendShouldKnow('ERROR: Invalid where', $debug);
      return $return;
    }

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    $return = DB::get($code, $columns, $where);
    if (isset($debuger)) {
      $debuger['last_query'][] = DB::lastQuery();
    }

    // [Quick] Return on Empty
    if (!$return) {
      if (isset($debuger)) {
        $debuger['return'] = 'Data not Found';
        Log::add(0, $debuger, 0, 'backend');
      }
      return null;
    }

    if (isset($options['hide_default_column']) && $return) {
      unset($return['insert_date_time']);
      unset($return['insert_user_id']);
      unset($return['insert_detail']);
      unset($return['update_date_time']);
      unset($return['update_user_id']);
      unset($return['update_detail']);
      unset($return['sort_order_id']);
      unset($return['version']);
      unset($return['system_delete']);
    }

    if (isset($options['no_def']) && $return) {
      unset($return['insert_date_time']);
      unset($return['insert_user_id']);
      unset($return['insert_detail']);
      unset($return['update_date_time']);
      unset($return['update_user_id']);
      unset($return['update_detail']);
      unset($return['sort_order_id']);
      unset($return['version']);
      unset($return['system_delete']);
    }

    // [IMG_PATH] Append image url.
    $img_conf = static::getOptionsValue('image', $options, false);
    if ($img_conf) {
      $img_ids  = [];
      $def_path = File::getPlaceHolder('image', ['getabsolute' => 'ROOT_URL']);
      foreach ($img_conf as $i_key => $i_conf) {
        if (isset($i_conf['key'])) {
          $i_conf['code'] = $i_conf['key']; // [compatibility]
        } else if (is_string($i_conf)) {
          $i_conf = ['code' => "{$i_conf}"]; // direct String
        }
        if (isset($i_conf['code'])) {
          $i_code    = $i_conf['code'];
          $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
          $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
          if (isset($i_conf['target_column']) && $i_conf['target_column']) {
            if (isset($return[$i_conf['target_column']])) {
              $img_ids[] = $i_code.$return[$i_conf['target_column']]; // add data
            }
          } else {
            // [AUTO] Extract
            $output_code = $i_code;
            foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
              if (isset($return[$e_key['data_key']])) {
                $output_code = str_replace($e_key['code_key'], $return[$e_key['data_key']], $output_code); // replace data
              } else if ($e_key['data_key'] == 'code') {
                $output_code = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
              }
            }
            $img_ids[] = $output_code;
          }
          $return[$i_img_key] = $def_path;
        }
        if (isset($i_conf['debug'])) {
          $debug = [
            'code'    => $code,
            'img_ids' => $img_ids
          ];
          Log::logBackendShouldKnow('debug img_ids', $debug);
        }
      }

      if ($img_ids) {
        $_select_path_opt = ['placeholder' => '', 'no_date' => 1];
        $_select_path_opt = [];
        if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true)) {
          $img_list = File::selectFixedPath($img_ids, $_select_path_opt);
        } else {
          $img_list = File::selectPath($img_ids, $_select_path_opt);
        }
        foreach ($img_conf as $i_key => $i_conf) {
          if (isset($i_conf['key'])) {
            $i_conf['code'] = $i_conf['key']; // [compatibility]
          } else if (is_string($i_conf)) {
            $i_conf = ['code' => "{$i_conf}"]; // direct String
          }
          if (isset($i_conf['code'])) {
            $i_code    = $i_conf['code'];
            $i_img_key = (isset($i_conf['name']) && $i_conf['name']) ? $i_conf['name'] : $i_key;
            $i_img_key = (is_numeric($i_img_key) ? "image_{$i_img_key}" : $i_img_key);
            if (isset($i_conf['target_column']) && $i_conf['target_column']) {
              if (isset($return[$i_conf['target_column']])) {
                $return[$i_img_key] = $img_list[$i_code.$return[$i_conf['target_column']]];
              }
            } else {
              // [AUTO] Extract
              $output_code = $i_code;
              foreach (static::extractCurlyBrackets($code, $i_code) as $e_key) {
                if (isset($return[$e_key['data_key']])) {
                  $output_code = str_replace($e_key['code_key'], $return[$e_key['data_key']], $output_code); // replace data
                } else if ($e_key['data_key'] == 'code') {
                  $output_code = str_replace($e_key['code_key'], $module_code, $output_code); // replace data
                }
              }
              if (isset($img_list[$output_code])) {
                $return[$i_img_key] = $img_list[$output_code];
              }
            }
          }
        }
        unset($img_list);
        unset($img_ids);
      }
    }

    //AUTO DECODE JSON
    // if ($return) {
    //   foreach ($return as $key => $value) {
    //     if (util::isJson($value)) {
    //       $return[$key] = json_decode($value, true);
    //     }
    //   }
    // }

    // [OUTPUT]
    if (isset($debuger)) {
      $debuger['return'] = $return;
      Log::add(0, $debuger, 0, 'backend');
    }

    return $return;
  }

  /**
  | Group | SELECT/GET
  | Description | 5.ดึงข้อมูลเจาะจง row *+Log Last Query ให้ด้วย ให้เอาออกก่อนส่งงาน
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $where | JSON Where
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
   */
  public static function getWithLog($code, $columns = '*', $where = [], $options = []) {
    $return = null;
    if ($where) {
      $options                   = Amst::checkOption($options);
      $options['log_last_query'] = true;
      $return                    = static::get($code, $columns, $where, $options);
    } else {
      Log::logBackendShouldKnow('Invalid WHERE on get()', ['code' => $code, 'columns' => $columns, 'where' => $where, 'options' => $options]);
    }
    return $return;
  }

  /**
  | Group | SELECT/GET
  | Description | 6.ดึงข้อมูลตาม id ไม่ต้องใส่ where ให้เมื่อยมือ
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
   */
  public static function getByID($code, $columns = '*', $id = null, $options = []) {
    $return = null;
    $uid    = filter_var($id, FILTER_VALIDATE_INT); // -1,0,1 is OK
    // ZERO is not Allow anyway
    if ($uid) {
      $options = Amst::checkOption($options);
      $return  = static::get($code, $columns, ['id' => $id], $options);
    }
    return $return;
  }

  /**
  | Group | SELECT/GET
  | Description | 6.ดึงข้อมูลตาม id ไม่ต้องใส่ where ให้เมื่อยมือ *+Log Last Query ให้ด้วย ให้เอาออกก่อนส่งงาน
  | $code | Table Code
  | $columns | คอลัมภ์ที่ต้องการ
  | $id | ไอดีรายการ
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว)]
   */
  public static function getByIDWithLog($code, $columns = '*', $id = null, $options = []) {
    $return = null;
    $uid    = filter_var($id, FILTER_VALIDATE_INT); // -1,0,1 is OK
    // ZERO is not Allow anyway
    if ($uid) {
      $return = static::getWithLog($code, $columns, ['id' => $id], $options);
    } else {
      Log::logBackendShouldKnow('Invalid ID on getByID()', ['code' => $code, 'columns' => $columns, 'id' => $id, 'options' => $options]);
    }
    return $return;
  }

  /**
  | Group | OPERATION
  | Description | 7.ใส่ข้อมูลเข้าระบบ เช็คข้อมูลตาม INFO
  <hr><h3>นโยบายการ INSERT</h3>
  1. ส่ง $data ในรูปแบบ ของ $key => $value ระบบจะ INSERT ให้ทั้งหมด ทุกช่องที่ส่งมา<br>
  2. $value ที่เป็น NULL ที่ไม่ใช่ วัน/วันเวลา จะถูกเอาออกจาก $data<br>
  3. ถ้าส่ง $key ใดๆ ที่มีเงื่อนไขใน INFO จะถูกตรวจสอบเสมอ<br>
  4. ไม่มี REQUIRED หรือช่องที่บังคับส่งทุกครั้ง แต่ถ้าส่งมาจะมีการตรวจสอบ<br>
  | $code | Table Code
  | $data | Array or JSON STRING of Data
  | $options | [direct(true|insertตรงๆเลยส่งค่ากลับเป็น insert_id ไม่rep)]
   */
  public static function insert($code, $data = [], $options = []) {
    $rep = [
      'insert_id' => false
    ];

    // initial data
    $full_table_name = static::checkCode($code);
    $options         = Amst::checkOption($options);

    // [TODO] HOTFIX
    if (isset($options['direct'])) {
      return static::_insertDirect($full_table_name, $data);
    }

    // [JSON] support
    if (!is_array($data)) {
      $data = json_decode($data, true);
    }

    if (!$data) {
      // No Data to INSERT
      return static::repError('E1001', ['code' => $code, 'full_table_name' => $full_table_name, 'data' => $data]);
    }

    // [TABLE-INFO]
    $preload_data = static::_loadTableInfo($full_table_name, 'insert');
    if (isset($preload_data['insert_fields'])) {
      // PRE-CONFIG-OPTIONS
      $req_options = [
        'validate_rules' => $preload_data['validate_rules'], // [TODO]
        'insert_fields' => $preload_data['insert_fields'],
        'name'           => 'UNTITLED',
        'call_back'      => [
          'before_insert' => [
            //'name' => 'UNNAME_CALLBACK',
            // 'fn' => function () {}
          ]
        ]
      ];
      $options = array_merge($req_options, $options);
    } else {
      Log::logBackendShouldKnow('ชื่อตารางที่จะ INSERT ผิดดดดดด เช็คโค้ด เช็ค info อีกรอบ !!!! ', ['code' => $code, 'full_table_name' => $full_table_name]);
      return Amst::repError('E0000', 'Invalid CODE');
    }

    // ================================================
    // FIELDS-VALIDATOR
    // ================================================
    // $rep['options'] = $options;
    if ("ALLOW_INSERT_ANY_DATA_FIELD") {
      $insert_values = $data;
    } else {
      $insert_values = [];
    }

    $table_columns = [];
    foreach ($options['insert_fields'] as $col_name => $i_comment) {
      $key             = trim($col_name);
      $table_columns[] = $key;
      // if REQUIRED + RULEs != TRUE return repError.
      if (isset($options['validate_rules'][$key])) {
        $validate_result = static::_validateInput($options['validate_rules'][$key], $data, $key, $i_comment);
        if ($validate_result === 'set_as_null') {
          $data[$key] = null;
        } else if ($validate_result !== true) {
          unset($insert_values);
          Log::logBackendShouldKnow('INSERT บะได้ เช็คข้อมูลแหมกำ ', ['full_table_name' => $full_table_name, 'validate_result' => $validate_result]);
          return $validate_result;
        }
      }
      // [SET_INPUT] MD5|NULLABLE|trim
      if (array_key_exists($key, $data)) {
        $insert_values[$key] = static::_setInput($data[$key], isset($options['validate_rules'][$key]) ? $options['validate_rules'][$key] : []);
      }
    }

    // unset insert_values if not in table_columns
    // foreach ($insert_values as $key => $value) {
    //   if (!in_array($key, $table_columns)) {
    //     unset($insert_values[$key]);
    //   }
    // }
    unset($data);
    // REMOVE-NULL
    foreach ($insert_values as $key => $value) {
      if (is_null($insert_values[$key]) && !isset($options['validate_rules'][$key]['date']) && !isset($options['validate_rules'][$key]['datetime'])) {
        unset($insert_values[$key]);
      }
    }

    if ($insert_values) {
      // let's insert
      $insert_values['insert_user_id'] = User::getCurrentUserID();
      $insert_values['insert_detail']  = 'Dev['.Dev::getCurrentUserID().'] IP['.Util::getClientIP().']';
      $insert_id                       = DB::insert($full_table_name, $insert_values);
      if (static::isHasOptions('log_last_query', $options)) {
        $debug = [
          'code'            => $code,
          'full_table_name' => $full_table_name,
          'insert_values'   => $insert_values,
          'lq'              => DB::lastQuery()
        ];
        Log::logBackendShouldKnow('debug Amst::insert', $debug);
      }
      if ($insert_id) {
        $u_data = [
          'sort_order_id'    => $insert_id,
          'update_date_time' => Util::getSystemTimestamp()
        ];
        $rep['update_result'] = DB::update($full_table_name, $u_data, ['id' => $insert_id]);
        // SUCCESS
        $rep['insert_id'] = $insert_id;
      } else {
        $db_err = DB::getLastError();
        // "42S22", 1054, "Unknown column 'no_cal' in 'field list'"
        // Database Failed to INSERT
        return static::repError('E1002', '', ['db_err' => $db_err]);
      }
    } else {
      // No Data to INSERT
      return static::repError('E1001', ['full_table_name' => $full_table_name, 'insert_values' => $insert_values]);
    }

    return Amst::repSuccess($rep);
  }

  private static function _insertDirect($code, $data) {
    $return = false;
    // Old Insert
    $data['insert_user_id'] = User::getCurrentUserID();
    $data['insert_detail']  = 'Dev['.Dev::getCurrentUserID().'] IP['.Util::getClientIP().']';

    // remove null data
    foreach ($data as $key => $value) {
      if (is_null($value)) {
        unset($data[$key]);
      } else {
        $data[$key] = static::_setInput($data[$key]); // TRIM
      }
    }

    // let's insert
    $insert_id  = DB::insert($code, $data);
    $data['id'] = ($insert_id != false) ? $insert_id : 0;
    if (!$insert_id) {
      return false;
    }

    // update sort_order_id
    if ($insert_id) {
      $date = new DateTime();
      $data = [
        'sort_order_id'    => $insert_id,
        'update_date_time' => $date->format('Y-m-d H:i:s')
      ];
      DB::update($code, $data, ['id' => $insert_id]);
    }

    return $insert_id;
  }

  /**
  | Group | OPERATION
  | Description | ใส่ข้อมูลเข้าระบบทีละเยอะๆ ไม่มีการตรวจสอบก่อนยัดลงฐานข้อมูลให้หมดตามนั้น
  | $code | Table Code
  | $data | Array ข้อมูล
  | $options | [page_size(จำนวนแถวต่อคิวรี่)]
   */
  public static function insertBulk($code, $data = [], $options = []) {

    $rep = [
      'query_count'  => 0,
      'memory_limit' => ini_get('memory_limit')
    ];

    if (!$data || !is_array($data)) {
      return Amst::repError('E0001', 'Data');
    }

    $rep['page_size'] = static::getOptionsValue('page_size', $options, 40);
    ini_set('memory_limit', '1024M');

    $code           = static::checkCode($code);
    $insert_user_id = User::getCurrentUserID();
    $insert_detail  = DB::setStringVal('Dev['.Dev::getCurrentUserID().'] IP['.Util::getClientIP().']');

    $sys_row = [
      'insert_user_id' => $insert_user_id,
      'insert_detail'  => $insert_detail
    ];
    // $rep['data'] = $data;

    $cols = isset($data[0]) ? array_keys($data[0]) : [];
    foreach ($cols as $key => $i_row) {
      $cols[$key] = DB::setStringCol($i_row);
    }
    foreach ($sys_row as $key => $i_row) {
      $cols[] = DB::setStringCol($key);
    }

    // [BULK]
    $rep['data_count'] = count($data);
    foreach (array_chunk($data, $rep['page_size']) as $i_data) {
      $vals = [];

      foreach ($i_data as $i_row) {
        foreach ($i_row as $key => $value) {
          $i_row[$key] = DB::setStringVal($value);
        }
        $vals[] = '('.implode(',', $i_row).','.implode(',', $sys_row).')';
      }
      // กันบัคที่บางคอลั่มไม่มี default value => SET sql_mode='';
      // *ห้ามมี ; ก่อนหน้า ????

      $sql = "INSERT INTO {$code} (".implode(',', $cols).') VALUES '.implode(',', $vals).';';
      // echo $sql;die();
      // $rep['lastQuery'] = db::lastQuery();
      static::query($sql);
      $rep['query_count']++;
    }

    static::query('UPDATE `'.$code.'` SET `sort_order_id` = `id` WHERE `sort_order_id` = 0');
    // $rep['lastQuery'] = db::lastQuery();

    return Amst::repSuccess($rep);
  }

  /**
  | Group | OPERATION
  | Description | แก้ไขข้อมูลในระบบ เช็คข้อมูลตาม INFO
  <hr><h3>นโยบายการ UPDATE</h3>
  1. ส่ง $data ในรูปแบบ ของ $key => $value ระบบจะ UPDATE ให้ทั้งหมด ทุกช่องที่ส่งมา<br>
  2. $value ที่เป็น NULL ที่ไม่ใช่ วัน/วันเวลา จะถูกเอาออกจาก $data<br>
  3. ถ้าส่ง $key ใดๆ ที่มีเงื่อนไขใน INFO จะถูกตรวจสอบเสมอ<br>
  4. ไม่มี REQUIRED หรือช่องที่บังคับส่งทุกครั้ง แต่ถ้าส่งมาจะมีการตรวจสอบ<br>
  | $code | Table Code
  | $data | Array or JSON STRING of Data
  | $options | [direct(true|insertตรงๆเลยส่งค่ากลับเป็น insert_id ไม่rep)]
   */
  public static function update($code, $data, $where, $options = []) {
    $rep = [
      'update_result' => false // is Update Successful
    ];

    // initial data
    $full_table_name = static::checkCode($code);
    $options         = Amst::checkOption($options);

    // ไม่ปล่อยให้อัพเดทแบบไม่มี WHERE
    if (!$where) {
      Log::add(0, 'Invalid update WHERE :'.json_encode($where), 0, 'backend');
      return static::repError('E0000', 'Invalid Update WHERE');
    }

    // [TODO] HOTFIX
    if (isset($options['direct'])) {
      return static::_updateDirect($full_table_name, $data, $where);
    }

    // [JSON] support
    if (!is_array($data)) {
      $data = json_decode($data, true);
    }

    if (!$data) {
      // No Data to UPDATE
      return static::repError('E1003', ['code' => $code, 'full_table_name' => $full_table_name, 'data' => $data]);
    }

    // [TABLE-INFO]
    $preload_data = static::_loadTableInfo($full_table_name, 'update');
    if (isset($preload_data['update_fields'])) {
      // PRE-CONFIG-OPTIONS
      $req_options = [
        'validate_rules' => $preload_data['validate_rules'], // [TODO]
        'update_fields' => $preload_data['update_fields'],
        'name'           => 'UNTITLED',
        'call_back'      => [
          'before_update' => [
            //'name' => 'UNNAME_CALLBACK',
            // 'fn' => function () {}
          ]
        ]
      ];
      $options = array_merge($req_options, $options);
    } else {
      Log::logBackendShouldKnow('ชื่อตารางที่จะ UPDATE ผิดดดดดด เช็คโค้ด เช็ค info อีกรอบ !!!! ', ['code' => $code, 'full_table_name' => $full_table_name]);
      return Amst::repError('E0000', 'Invalid CODE');
    }

    // ================================================
    // FIELDS-VALIDATOR
    // ================================================
    if ("ALLOW_UPDATE_ANY_DATA_FIELD") {
      $update_values = $data;
    } else {
      $update_values = [];
    }
    foreach ($options['update_fields'] as $col_name => $i_comment) {
      $key = trim($col_name);
      // if REQUIRED + RULEs != TRUE return repError.
      if (isset($options['validate_rules'][$key])) {
        $validate_result = static::_validateInput($options['validate_rules'][$key], $data, $key, $i_comment);
        if ($validate_result === 'set_as_null') {
          $data[$key] = null;
        } else if ($validate_result !== true) {
          unset($update_values);
          Log::logBackendShouldKnow('UPDATE บะได้ เช็คข้อมูลแหมกำ ', ['full_table_name' => $full_table_name, 'validate_result' => $validate_result]);
          return $validate_result;
        }
      }
      // [SET_INPUT] MD5|trim + update_data by KEY *support [+][-]
      $i_real_key = DB_Init::checkUpdateFields($key, $data);
      if ($i_real_key) {
        $update_values[$i_real_key] = static::_setInput($data[$i_real_key], isset($options['validate_rules'][$key]) ? $options['validate_rules'][$key] : []);
      }
    }

    unset($data);
    // REMOVE-NULL
    foreach ($update_values as $key => $value) {
      if (is_null($update_values[$key]) && !isset($options['validate_rules'][$key]['date']) && !isset($options['validate_rules'][$key]['datetime']) && !isset($options['validate_rules'][$key]['time'])) {
        unset($update_values[$key]);
      }
    }

    if ($update_values) {
      if (isset($options['call_back']['before_update']) && isset($options['call_back']['before_update']['name'])) {
        $rep[$options['call_back']['before_update']['name']] = ($options['call_back']['before_update']['fn']);
      }

      // SYSTEM
      $update_values['update_user_id'] = User::getCurrentUserID();
      $update_values['update_detail']  = 'Dev['.Dev::getCurrentUserID().'] IP['.Util::getClientIP().']';
      if (!isset($update_values['update_date_time'])) {
        $update_values['update_date_time'] = Util::getSystemTimestamp();
      }
      $update_values['version[+]'] = 1;
      $rep['update_result']        = DB::update($full_table_name, $update_values, $where);
      if (static::isHasOptions('log_last_query', $options)) {
        $debug = [
          'code'            => $code,
          'full_table_name' => $full_table_name,
          'update_values'   => $update_values,
          'where'           => $where,
          'lq'              => DB::lastQuery()
        ];
        Log::logBackendShouldKnow('debug Amst::update', $debug);
      }
    } else {
      // No Data to UPDATE
      return static::repError('E1003', ['full_table_name' => $full_table_name, 'update_values' => $update_values]);
    }

    return static::repSuccess($rep);
  }

  private static function _updateDirect($code, $data, $where, $options = []) {
    // Old Update()
    $data['update_user_id'] = User::getCurrentUserID();
    $data['update_detail']  = 'Dev['.Dev::getCurrentUserID().'] IP['.Util::getClientIP().']';
    $date                   = new DateTime();
    if (!isset($data['update_date_time'])) {
      $data['update_date_time'] = $date->format('Y-m-d H:i:s');
    }
    $data['version[+]'] = 1;

    // remove null data *Allow date time
    foreach ($data as $key => $value) {
      if (is_null($value) && !Util::endsWith($key, '_date_time') && !Util::endsWith($key, '_date_time')) {
        unset($data[$key]);
      } else {
        $data[$key] = static::_setInput($data[$key]); // TRIM
      }
    }
    foreach ($where as $key => $value) {
      if (is_null($value)) {
        unset($where[$key]);
      }
    }

    return DB::update($code, $data, $where);
  }

  /**
  | Group | OPERATION
  | Description | แก้ไขข้อมูล เจาะจง id ไม่ต้องใส่ where ให้เมื่อยมือ
   */
  public static function updateByID($code, $data, $id, $options = []) {
    $rep = [
      'update_result' => false
    ];

    $uid = filter_var($id, FILTER_VALIDATE_INT); // -1,0,1 is OK
    // ZERO is not Allow anyway
    if ($uid) {
      return static::update($code, $data, ['id' => $uid], $options);
    }
    return Amst::repError('E0004');
  }

  /**
  | Group | OPERATION
  | Description | ลบแม่ง
   */
  public static function delete($code, $where) {
    $rep = [
      'delete_result' => true
    ];
    $code = static::checkCode($code);
    if ($where) {
      $rep['delete_result'] = static::update($code, ['system_delete' => 1], $where, ['direct' => true]);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | OPERATION
  | Description | [QuickFunction] ลบแม่ง บัยส์ไอดี
   */
  public static function deleteByID($code, $id) {
    $rep = [
      'delete_result' => true
    ];

    $uid = filter_var($id, FILTER_VALIDATE_INT); // -1,0,1 is OK
    // ZERO is not Allow anyway
    if ($uid) {
      return static::delete($code, ['id' => $uid]);
    }
    return Amst::repError('E0004');
  }

  /**
  | Group | OPERATION
  | Description | ไม่ลบละ เปลี่ยนใจละ
   */
  public static function deleteUndo($code, $where) {
    $rep = [
      'update_result' => true
    ];
    $code                 = static::checkCode($code);
    $rep['update_result'] = static::update($code, ['system_delete' => 0], $where, ['direct' => true]);
    return Amst::repSuccess($rep);
  }

  /**
  | Group | OPERATION
  | Description | สลับที่ระหว่างสอง row
  | $code | Table Code
  | $current_id | ไอดีต้นทาง
  | $move_to_id | ไอดีปลายทาง
   */
  public static function swap($code, $current_id, $move_to_id) {
    $code = static::checkCode($code);

    $arr = [
      'current_id' => $current_id,
      'move_to_id' => $move_to_id
    ];

    $current_entity = DB::get($code, ['sort_order_id'], ['id' => $current_id]);
    $move_to_entity = DB::get($code, ['sort_order_id'], ['id' => $move_to_id]);

    if ($current_entity['sort_order_id'] == $move_to_entity['sort_order_id']) {
      return false; // 'id cant be the same'
    }

    $current_arr = ['sort_order_id' => $current_entity['sort_order_id']];
    $move_to_arr = ['sort_order_id' => $move_to_entity['sort_order_id']];

    if (!DB::update($code, $move_to_arr, ['id' => $current_id])) {
      $error = DB::error();
      return false;
    }

    DB::update($code, $current_arr, ['id' => $move_to_id]);

    return true;
  }

  /**
  | Group | AGGREGATE
  | Description | ถ้ามีตาม where ก็เป็น true
  | $code | Table Code
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function has($code, $where = [], $options = []) {
    $code  = static::checkCode($code);
    $where = static::checkWhere($where);

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'has'       => DB::has($code, $where),
        'lastQuery' => static::lastQuery()
      ];
    }

    return DB::has($code, $where);
  }

  /**
  | Group | AGGREGATE
  | Description | นับจำนวนตาม where ไม่นับที่ลบไปแล้ว
  | $code | Table Code
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย), plus(บวกลบข้อมูลให้เลย)]
   */
  public static function count($code, $where = [], $options = []) {
    $return = 0; // COUNT

    $code  = static::checkCode($code);
    $where = static::checkWhere($where);

    $plus = static::getOptionsValue('plus', $options, 0);
    $plus = filter_var($plus, FILTER_VALIDATE_INT); // -1,0,1 is OK
    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    $where_without_order = [];
    foreach ($where as $key => $value) {
      if (strtolower($key) != 'order') {
        $where_without_order[$key] = $value;
      }
    }

    // OUTPUT
    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'count'     => DB::count($code, $where_without_order) + $plus,
        'lastQuery' => static::lastQuery()
      ];
    }
    return DB::count($code, $where_without_order) + $plus;
  }

  /**
  | Group | AGGREGATE
  | Description | [QuickFunction] นับจำนวนตาม where ทุกอันรวมที่ลบไปแล้วด้วย
  | $code | Table Code
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [plus(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function countAll($code, $where = [], $plus = 0) {
    $return  = 0; // COUNT
    $options = [
      'show_all' => true,
      'plus'     => $plus
    ];
    return static::count($code, $where, $options);
  }

  /**
  | Group | AGGREGATE
  | Description | ขอค่าที่มากที่สุดตามคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
  | $code | Table Code
  | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function max($code, $column_name, $where = [], $options = []) {
    $code  = static::checkCode($code);
    $where = static::checkWhere($where);
    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }
    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'max'       => DB::max($code, $column_name, $where),
        'lastQuery' => static::lastQuery()
      ];
    }
    return DB::max($code, $column_name, $where);
  }

  /**
  | Group | AGGREGATE
  | Description | ขอค่าที่มากที่สุดตามคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
  | $code | Table Code
  | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function min($code, $column_name, $where = [], $options = []) {
    $code  = static::checkCode($code);
    $where = static::checkWhere($where);

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'min'       => DB::min($code, $column_name, $where),
        'lastQuery' => static::lastQuery()
      ];
    }

    return DB::min($code, $column_name, $where);
  }

  /**
  | Group | AGGREGATE
  | Description | ค่าเฉลี่ยของคอลั่มนั้น ** ส่งกลับ NULL ถ้า ไม่เจอข้อมูลเลย
  | $code | Table Code
  | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function avg($code, $column_name, $where = [], $options = []) {
    $code  = static::checkCode($code);
    $where = static::checkWhere($where);

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'avg'       => DB::avg($code, $column_name, $where),
        'lastQuery' => static::lastQuery()
      ];
    }
    return DB::avg($code, $column_name, $where);
  }

  /**
  | Group | AGGREGATE
  | Description | รวมค่าคอลั่มนั้น ** ส่งกลับ NULL ถ้า WHERE ไม่เจอข้อมูลเลย
  | $code | Table Code
  | $column_name | ชื่อคอลัมภ์ ไม่ใช่ '*'
  | $where | [id(กรองตามไอดี), json(กรอก JSON มาละจะแปลงเป็น $where ให้ทั้งหมด)]
  | $options | [show_all(รวมข้อมูลที่ถูกลบด้วย)]
   */
  public static function sum($code, $column_name, $where = [], $options = []) {
    $code  = static::checkCode($code);
    $where = static::checkWhere($where);

    if (!static::getOptionsValue('show_all', $options, false)) {
      $where['AND #not_show_all'] = [
        'system_delete' => 0
      ];
    }

    // May return NULL => return 0
    $sum = DB::sum($code, $column_name, $where);

    if (static::getOptionsValue('debug', $options, false)) {
      return [
        'sum'       => (!$sum ? 0 : $sum),
        'lastQuery' => static::lastQuery()
      ];
    }
    return (!$sum ? 0 : $sum);
  }

  /**
  | Group | DIRECT_QUERY
  | Description | เรียก SQL QUERY เฉยๆ
  | $sql | SQL Query String
   */
  public static function query($sql) {
    return DB::query($sql);
  }

  /**
  | Group | DIRECT_QUERY
  | Description | เรียก SQL SELECT เพื่อดึงข้อมูลตามปกติ
  | $sql | SQL Query String
   */
  public static function queryFetchAll($sql) {
    return static::query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
  | Group | DIRECT_QUERY
  | Description | เรียก SQL FUNTION จะส่งกลับค่าเดียว ช่องแรกช่องเดียว ไว
  | $sql | SQL Query String
   */
  public static function queryFetchColumn($sql) {
    // PDOStatement::fetchColumn — Returns a single column from the next row of a result set
    return static::query($sql)->fetchColumn();
  }

  /**
  | Group | DIRECT_QUERY
  | Description | DEBUG last sql command.
   */
  public static function lastQuery() {
    $lq = DB::lastQuery(); // latest
    if (Dev::getCurrentUserID()) {
      return $lq;
    }
    return 'production';
  }

  /**
  | Group | ERROR
  | Description | แปลง ErrorCode(E4) เป็น ข้อความ ถ้าไม่ใช่ก็ส่งกลับตรงๆ *แปลภาษาให้ด้วย
  | $error_code | EX00X รหัสเออเรอร์
  | $params | Array Params for vsprintf
   */
  public static function getErrorMsg($error_code, $params = []) {
    $error_string = $error_code;

    if (class_exists('Language')) {
      // [compatibility]
      $error_string = Language::getErrorMsg($error_code, User::getCurrentLanguage());
    } else {
      // BUILDIN-ERROR TRANSLATER
      $_error_msg = [
        // 1.SYSTEM
        'E0000' => '%s',
        'E0001' => 'กรุณากรอกข้อมูล \'%s\'',
        'E0002' => 'ไม่มีข้อมูลสำหรับการเพิ่มข้อมูลใหม่',
        'E0003' => 'ไม่สามารถลบข้อมูลได้ เนื่องจาก \'%s\'',
        'E0004' => 'ข้อมูลไอดีไม่ถูกต้อง',
        'E0005' => 'ไม่พบข้อมูล \'%s\' ในระบบ',
        'E0006' => 'ข้อมูลการกรองไม่ถูกต้อง',
        'E0007' => 'ไม่สามารถส่ง Array ใน \'%s\' เพื่อบันทึกข้อมูลได้', // NO array
        'E0008' => 'พบข้อผิดพลาดรุนแรง', // FREE
        'E0009' => 'ช่วงเวลาที่กรอกไม่ถูกต้อง', // BETWEEN
        'E0010' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของเวลา HH:MM:SS',
        'E0011' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่ YYYY-MM-DD',
        'E0012' => 'กรุณากรอกข้อมูล \'%s\' ให้ถูกต้องตามรูปแบบของวันที่และเวลา',
        'E0013' => 'กรุณากรอกข้อมูล \'%s\' ในรูปแบบของตัวเลข',
        'E0014' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าไม่น้อยกว่า 0',
        'E0015' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า 0',
        'E0016' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาว %s', // Strlen
        'E0017' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวน้อยกว่า %s ', // Strlen
        'E0018' => 'กรุณากรอกข้อมูล \'%s\' ที่มีความยาวมากกว่า %s', // Strlen
        'E0019' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่ามากกว่า %s',
        'E0020' => 'กรุณากรอกข้อมูล \'%s\' ที่มีค่าน้อยกว่า %s'
      ];
      if (Util::startsWith($error_string, 'E') && (strlen($error_string) == 5) && isset($_error_msg[$error_code])) {
        $error_string = $_error_msg[$error_code];
      }
    }

    if ($params) {
      return (vsprintf($error_string, $params));
    }
    return $error_string;
  }

  /**
   * Base API Reponse.
   *
   * @author TULA
   * @param BOOL $status of Success or Error
   * @param STRING $message Must be ERR_CODE - EX00X
   * @param ARRAY $data additional data to log
   * @return JSON API Response
   */
  public static function rep($status, $message, $data = []) {
    $result = [
      'response_status'    => $status,
      'response_message'   => $message,
      'response_date_time' => date('Y-m-d H:i:s'),
      'response_type'      => gettype($data),
      'response_data'      => $data
    ];
    // REMOVED due Performace
    // if (Dev::getCurrentUserID()) {
    //   $result['profiler']['memory_get_usage'] = (($usage = memory_get_usage()) != '') ? number_format($usage) : 0;
    //   $result['profiler']['memory_allowcated'] = (($usage = memory_get_usage(true)) != '') ? number_format($usage) : 0;
    // }
    return $result;
  }

  /**
   * Error API Reponse.
   *
   * @author TULA
   * @param STRING $message Must be ERR_CODE - EX00X
   * @param ARRAY $params additional data to log
   * @param ARRAY $data additional data to log
   * @return JSON API Response
   */
  public static function repError($message = 'error', $params = [], $data = []) {
    // [20211201] clickbit มาขอให้ปรับกลับก่อน
    if (false && "return as ECODE") {
      $data                     = Amst::checkOption($data);
      $data['msg_params']       = $params;
      $data['readable_message'] = static::getErrorMsg($message, $params);
    } else {
      $message = static::getErrorMsg($message, $params);
    }
    // if (Dev::getCurrentUserID()) {
    //   if ($message == 'E0002') {
    //     Log::add(0, 'We cant update this data, Check this', 0, 'backend');
    //   } else {
    //     Log::add(0, 'Somthing wrong here : '.$message, 0, 'backend');
    //   }
    // }
    return static::rep(false, $message, $data);
  }

  /**
   * Success API Reponse.
   *
   * @author TULA
   * @param ARRAY $data to return
   * @return JSON API Response
   */
  public static function repSuccess($data = []) {
    // Production Array Rep
    if (!Dev::getCurrentUserID() && is_array($data)) {
      $prodution_rep = [];
      foreach ($data as $key => $value) {
        if (!Util::startsWith($key, '_')) {
          $prodution_rep[$key] = $value;
        }
      }
      unset($data);
      return static::rep(true, 'success', $prodution_rep);
    }
    return static::rep(true, 'success', $data);
  }

  /**
   * [Transaction] ดึงสถานะ ว่าเปิดอยู่ไหม
   *
   * @author TULA
   * @return BOOL Transaction open state
   */
  public static function getTransactionState() {
    return static::$_transaction_state;
  }

  /**
   * [Transaction] เริ่ม Transaction *ถ้าเปิดอยู่จะไม่เปิดซ้ำ
   * [20211210] ยังไม่เปิดให้ใช้ทั่วไป รอให้แบคเทพก่อน
   *
   * @author TULA
   * @return BOOL True
   */
  public static function transBegin() {
    // show processlist;
    // show status where `variable_name` = 'Threads_connected' OR `variable_name` = 'Connections';
    if (static::$_transaction_state == 'ON') {
      // ERROR?
    }
    // if ($caller_id = Profiler::isProfiling()) {
    //   Profiler::saveProfiling($caller_id);
    // }
    Amst::query('BEGIN');
    // if (isset($caller_id)) {
    //   Profiler::startProfiling();
    // }
    static::$_transaction_state = 'ON';
  }

  /**
   * [Transaction] บันทึก Transaction และปิด
   *
   * @author TULA
   * @return BOOL True
   */
  public static function transCommit() {
    // if ($caller_id = Profiler::isProfiling()) {
    //   Profiler::saveProfiling($caller_id);
    // }
    Amst::query('COMMIT');
    // if (isset($caller_id)) {
    //   Profiler::startProfiling();
    // }
    static::$_transaction_state = 'DONE';
    if ($err_list = Amst::getTransError(false, true)) {
      foreach ($err_list as $data) {
        Amst::insert(Info::$system_table['log_error'], $data, ['direct' => true]);
      }
    }
  }

  /**
   * [Transaction] ยกเลิก Transaction ล้างข้อมูลที่อยู่ใน Transaction และปิด
   *
   * @author TULA
   * @return BOOL True
   */
  public static function transRollback() {
    Amst::query('ROLLBACK');
    static::$_transaction_state = 'CANCELED';
    if ($err_list = Amst::getTransError(false, true)) {
      foreach ($err_list as $data) {
        Amst::insert(Info::$system_table['log_error'], $data, ['direct' => true]);
      }
    }
  }

  /**
   * [Transaction] ดึง Error Log ทั้งหมดที่เกิดขึ้นในช่วงที่ทำ Transaction
   *
   * @author TULA
   * @return ARRAY Error Data List
   */
  public static function getTransError($key, $purge = false) {
    $return = [];
    $key    = 'fixed_key';
    if (isset(static::$_transaction_error_list[$key])) {
      $return = static::$_transaction_error_list[$key];
      if ($purge) {
        unset(static::$_transaction_error_list[$key]);
      }
    }
    return $return;
  }

  /**
   * [Transaction] เพิ่ม Error Log ที่เกิดขึ้นในช่วงที่ทำ Transaction. *ไม่งั้นถ้า Rollback Log จะหายหมด
   *
   * @author TULA
   * @return STRING Profiler Key
   */
  public static function setTransError($key = false, $err_data = '') {
    if (!$key) {
      $key = md5(time().rand(0, 10000).'salty');
    }
    $key = 'fixed_key';
    if ($err_data) {
      static::$_transaction_error_list[$key][] = $err_data;
    }
    return $key;
  }
}
