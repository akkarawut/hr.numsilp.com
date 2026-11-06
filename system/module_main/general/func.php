<?php
class Func {
  private static $conf = [
    'description' => 'Do Anything that faster than Module',
    'code_len'    => 5,
    'code_char'   => 'abcdefghijklmnopqrstuvwxyz'
  ];
  static $function_for_autoload = [];

  public static function add($name, $module) {
    $rep = [
      'insert_id' => false,
      'name'      => $name,
      'module'    => $module
    ];

    if ($module) {
      // COMPATIBLE
      if (is_array($module) && isset($module['name'])) {
        $ex_data = $module;
        $module = $name;
        $name = $ex_data['name'];
        $managed_user_type_id = $ex_data['managed_user_type_id'];
      }

      if (!isset(Config_Module::$module_file[$module])) {
        return Amst::repError('E0000', "Module({$module}) Info Not Found");
      }
      $directory_path = Config_Module::$module_file[$module];
      // IMPORT FUNCTION FROM CLASS FOLDER
      if (!file_exists(ROOT_PHP.DIRECTORY_SEPARATOR.$directory_path.'/class/')) {
        return Amst::repError("Module({$module}) Class Not Found");
      }
      $model['id'] = 'HAVE FILE';
    }
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'Module');
    }

    // random string
    $new_code = '';
    while (true) {
      $new_code = Util::getRandomString(static::$conf['code_len'], static::$conf['code_char']);
      if (!DB::has(Info::$system_table['function'], ['code' => $new_code])) {
        break;
      }
    }

    $data = [
      'name' => "{$name}" // Older version supported
    ];
    if (isset($ex_data['description']) && $ex_data['description']) {
      $data['description'] = $ex_data['description'];
    }
    if (isset($managed_user_type_id) && $managed_user_type_id) {
      $data['managed_user_type_id'] = $managed_user_type_id;
    }

    $sys_data = [
      'code'    => $new_code,
      'module'  => $module,
      'is_show' => 1
    ];
    // DISPLAY
    if ($module == 'User') {
      $sys_data['display'] = 'user';
    } else {
      $sys_data['display'] = 'notuser';
    }
    $data = array_merge($data, $sys_data);

    $api = Amst::insert(Info::$system_table['function'], $data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $rep['insert_id'] = $api['response_data']['insert_id'];
      // generate db
      DB_Init::regenerateSQLTable();
      // Module.json
      // Module::set($new_code, $data);
    } else {
      Log::add(0, "FAILED to ADD function Name[".$data['name']."].<br />ERROR : [".var_export(DB::error(), true)."]<br />RETURN : false<br />input : [".json_encode($rep)."]");
      return Amst::repError('E0000', "FAILED to ADD function Name[{$data['name']}].");
    }

    return Amst::repSuccess($rep);
  }

  public static function edit($id, $data = []) {
    $rep = [
      'update_result' => false,
      'id'            => $id,
      'data'          => $data
    ];

    // Log::add(0, $rep, 0, 'backend');

    $model = Amst::getByID(Info::$system_table['function'], ['id'], $id);
    if (!isset($model['id'])) {
      return Amst::repError('E0005', 'Module');
    }

    if (!is_array($data)) {
      $data = [
        'name' => $data // Older version supported
      ];
    } else {
      $data = Util::makeArray($data);
    }

    return Amst::updateByID(Info::$system_table['function'], $data, $id);
  }

  public static function remove($function_id) {
    $rep = false;

    $model = Amst::getByID(Info::$system_table['function'], '*', $function_id);
    if (!isset($model['id'])) {
      return Amst::repError('E0000', 'function not existed');
    }

    // remove from sys_function
    $result = DB::delete(Info::$system_table["function"], ['id' => $function_id]);
    if ($result) {
      // remove file
      File::delete($model['code'], '../../../');

      // DROP TABLE
      $tables = Amst::query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".Config::db_name."' AND TABLE_NAME NOT LIKE 'sys_%';");
      foreach ($tables as $table) {
        if (strpos($table['TABLE_NAME'], Info::module_table_prefix.$model['code']) !== false) {
          Amst::query('DROP TABLE IF EXISTS '.$table['TABLE_NAME'].';');
        }
      }

      // All success
      $rep = true;
    } else {
      return Amst::repError('E0000', 'failed to remove function.');
    }

    return Amst::repSuccess($rep);
  }

  public static function swap($current_id, $move_to_id) {
    $rep = false;
    $result = Amst::swap(Info::$system_table["function"], $current_id, $move_to_id);
    if ($result) {
      $rep = true;
    }
    return Amst::repSuccess($rep);
  }

  public static function select($columns = '*', $where = [], $options = []) {
    $return = [];

    if (!$where || !is_array($where)) {
      $where = [];
    }
    $where['ORDER'] = ['sort_order_id' => 'ASC'];
    return Amst::selectNokey(Info::$system_table['function'], $columns, $where, $options);
  }

  /**
   * [IMPORT] Autoloader
   *
   * @author AMST
   * @return ARRAY
   */
  public static function selectForAutoload() {
    $return = [];

    // [CATCH][ONCE]
    if (static::$function_for_autoload) {
      return static::$function_for_autoload;
    }

    $columns = ['module'];
    $where = ['ORDER' => ['sort_order_id' => 'ASC']];
    static::$function_for_autoload = Amst::selectNokey(Info::$system_table['function'], $columns, $where, []);

    return static::$function_for_autoload;
  }

  public static function get($columns, $where) {
    $return = [];

    return Amst::get(Info::$system_table['function'], $columns, $where);
  }

  public static function getByCode($columns, $code) {
    $return = [];

    $where = ['code' => $code];
    return Amst::get(Info::$system_table['function'], $columns, $where);
  }

  public static function count($where) {
    $return = 0;

    return Amst::count(Info::$system_table['function'], $where);
  }

  public static function selectExistsModule() {
    $module_list = [];
    foreach (Config_Module::$module_file as $key => $value) {
      if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$key])) {
        array_push($module_list, $key);
      }
    }
    return $module_list;
  }
}
