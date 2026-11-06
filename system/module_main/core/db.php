<?php

class DB {
  protected static $_db_conn  = [];
  protected static $_last_err = [];

  private static function bestPractice() {
    $return = null;
    // JUST MEMO
    return $return;

    // SHOW VARIABLES LIKE 'key_buffer_size';
    // SHOW VARIABLES LIKE 'max_allowed_packet';
    // SHOW VARIABLES LIKE 'query_cache_size';
    // SHOW VARIABLES LIKE 'query_%';
    // SHOW VARIABLES LIKE 'max_connections';
    // SHOW VARIABLES LIKE 'innodb_buffer_pool_size';
    // SHOW VARIABLES LIKE 'innodb_io_capacity';

    return $return;
  }

  public static function init($db_code, $type, $host, $database, $username, $password, $port) {
    static::$_db_conn[$db_code] = new Medoo([
      'type'     => $type,
      'host'     => $host,
      'database' => $database,
      'username' => $username,
      'password' => $password,
      'port'     => $port,
      'charset'  => 'utf8mb4' // [20220304] support emoji
      // 'option' => [ https://github.com/catfan/Medoo/issues/380
      //   PDO::MYSQL_ATTR_SSL_KEY  => '/etc/mysql/client-key.pem',
      //   PDO::MYSQL_ATTR_SSL_CERT => '/etc/mysql/client-cert.pem',
      //   PDO::MYSQL_ATTR_SSL_CA   => '/etc/mysql/ca-cert.pem'
      // ]
      // Can be set as PDO::ERRMODE_SILENT, PDO::ERRMODE_WARNING, PDO::ERRMODE_EXCEPTION
      // Default is PDO::ERRMODE_SILENT
      // 'error' => PDO::ERRMODE_SILENT
    ]);

    $tz_offset  = Util::getTimezoneOffset();
    $init_query = [
      "SET time_zone='{$tz_offset}'",
      "USE ".$database
    ];
    static::$_db_conn[$db_code]->query(implode(';', $init_query));

    return static::$_db_conn[$db_code];
  }

  public static function insert($table, $data, $db_code = 'general') {
    if (!isset(static::$_db_conn[$db_code]) || !static::$_db_conn[$db_code]) {
      return false;
      die("Database connection not found".json_encode([$table, $data, $db_code, static::$_db_conn]));
    }
    $result = static::$_db_conn[$db_code]->insert($table, $data);
    // return static::checkerror($db_code);
    $result = static::checkerror() ? false : static::$_db_conn[$db_code]->id();
    return $result;
  }

  public static function insertId($db_code = 'general') {
    return static::$_db_conn[$db_code]->id();
  }

  public static function update($table, $data, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->update($table, $data, $where);
    return !(static::checkerror());
  }

  public static function delete($table, $where = null, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->delete($table, $where);
    return !(static::checkerror());
  }

  public static function get($table, $columns, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->get($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function select($table, $columns = null, $where = null, $db_code = 'general') {
    if (!isset(static::$_db_conn[$db_code]) || !static::$_db_conn[$db_code]) {
      return false;
      die("Database connection not found".json_encode([$table, $columns, $where, $db_code, static::$_db_conn]));
    }
    $result = static::$_db_conn[$db_code]->select($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function selectRandom($table, $columns = null, $where = null, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->rand($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function has($table, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->has($table, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function count($table, $where = null, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->count($table, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function max($table, $columns, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->max($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function min($table, $columns, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->min($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function avg($table, $columns, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->avg($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function sum($table, $columns, $where, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->sum($table, $columns, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function query($query, $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->query($query);
    static::checkerror($db_code);
    return $result;
  }

  public static function replace($table, $column, $where = [], $db_code = 'general') {
    $result = static::$_db_conn[$db_code]->replace($table, $column, $where);
    static::checkerror($db_code);
    return $result;
  }

  public static function error($db_code = 'general') {
    return static::$_db_conn[$db_code]->error;
  }

  public static function getLastError($db_code = 'general') {
    return static::$_last_err;
  }

  public static function lastQuery($db_code = 'general') {
    return static::$_db_conn[$db_code]->last();
  }

  public static function checkerror($db_code = 'general') {
    if ($error = static::$_db_conn[$db_code]->error) {
      // The function $database->error() is removed. Just read the $database->error
      // or $database->errorInfo to get the error information last performed.
      // die(json_encode($error));
      if ($error) {
        static::$_last_err = $error;
        Log::add($error, static::lastQuery(), 0, 'sql');
        return true;
      }
    }
    return false;
  }

  public static function info($db_code = 'general') {
    return static::$_db_conn[$db_code]->info();
  }

  public static function getDB($db_code = 'general') {
    return static::$_db_conn[$db_code];
  }

  public static function queryFetchAll($sql_cmd) {
    // Direct SQL Query;
    return static::query($sql_cmd)->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
  | Group | STRUCTURE
  | Description | ชื่อฐานข้อมูลปัจจุบัน
   */
  public static function getDatabaseName() {
    return static::queryFetchAll("SELECT DATABASE() FROM DUAL;");
  }

  /**
  | Group | STRUCTURE
  | Description | select all table
   */
  public static function selectTableStructure($table_name) {
    $schema = [];
    if (!$table_name) {
      return $schema;
    }
    $table_name = Amst::checkCode($table_name);

    $sql_cmd = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".Config::db_name."'";
    $sql_cmd .= " AND TABLE_NAME='{$table_name}' ORDER BY ORDINAL_POSITION;";

    $selected_cols = static::queryFetchAll($sql_cmd);
    // Log::add(0, Amst::lastQuery(), 0, 'backend');
    foreach ($selected_cols as $row) {
      $schema[$row['TABLE_NAME']][] = [
        'name'       => $row['COLUMN_NAME'],
        'data_type'  => $row['DATA_TYPE'],
        'max_len'    => $row['CHARACTER_MAXIMUM_LENGTH'],
        'col_type'   => $row['COLUMN_TYPE'],
        'comment'    => $row['COLUMN_COMMENT'],
        'column_key' => $row['COLUMN_KEY'] // pri, uni, ind
      ];
    }

    // return $sql_cmd;
    return $schema;
  }

  /**
  | Group | STRUCTURE
  | Description | select all table
   */
  public static function selectTable() {
    return static::query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
  }

  /**
  | Group | Release
  | Description | 1.phpLeftJoin
   */
  public static function phpLeftJoin($code, $select, $where = [], $order = [], $limit = []) {
    // $code = amst::checkCode($code);

    $sql_select = [];
    $sql_table  = [];
    foreach ($select as $table_name => $i_table) {
      if (!$sql_select) {
        // MASTER
        if (isset($i_table['select'])) {
          $sql_select = is_array($i_table['select']) ? self::_getMainFields($i_table['alias'], $i_table['select']) : [$i_table['select']]; // MAIN-ALL
        } else {
          $sql_select = ["{$i_table['alias']}.*"];
        }
        $sql_table = self::_getMainTables($code, $table_name, $i_table['alias']);
      } else {
        // JOIN
        $sql_select = self::_appendJoinFields($sql_select, $table_name, $i_table['alias'], $i_table['select']);
        $sql_table  = self::_appendJoinTables($code, $sql_table, $table_name, $i_table['alias'], $i_table['on']);
      }
    }
    return self::_mergeQuery($sql_select, $sql_table, $where, $order, $limit);
  }

  private static function _appendJoinFields($sql_select, $table_name, $alias, $selected_fields) {
    // if($selected_fields == '*')
    //   return getJoinColumnList; // [LATER] if * will Query

    // array_merge($select, $select = array());
    foreach ($selected_fields as $col_name => $i_comment) {
      // if(Util::startsWith($col_name, '_')) // Fixed Alias
      if (!isset($exclude_fields[$col_name])) {
        // "`{$tbl_name}.{$col_name}`
        if (Util::startsWith($col_name, 'is_')) {
          $pretty_name  = "is_{$table_name}_".str_replace('is_', '', $col_name); // is_xxx_enabled
          $sql_select[] = "{$alias}.{$col_name} as {$pretty_name}";
        } else {
          $sql_select[] = "{$alias}.{$col_name} as {$table_name}_{$col_name}";
        }
      }
    }
    return $sql_select;
  }

  private static function _getMainTables($code, $table_name, $alias) {
    $sql_table = [];
    if (Util::startsWith($table_name, 'sys')) {
      $sql_table[] = 'FROM '.Config::db_name.".{$table_name} AS {$alias}";
    } else {
      $sql_table[] = 'FROM '.Config::db_name.".".Info::module_table_prefix."_{$code}_{$table_name} AS {$alias}";
    }

    return $sql_table;
  }

  private static function _getMainFields($alias, $selected_fields) {
    // if($selected_fields == '*')
    $sql_select = [];

    // array_merge($select, $select = array());
    foreach ($selected_fields as $col_name => $i_comment) {
      $sql_select[] = "{$alias}.{$col_name} as {$col_name}";
    }
    return $sql_select;
  }

  private static function _appendJoinTables($code, $sql_table, $table_name, $alias, $on) {
    // $sql_table = array();
    if (Util::startsWith($table_name, 'sys')) {
      $sql_table[] = 'LEFT JOIN '.Config::db_name.".{$table_name} AS {$alias}";
      $sql_table[] = "ON {$on}";
    } else {
      $sql_table[] = 'LEFT JOIN '.Config::db_name.".".Info::module_table_prefix."_{$code}_{$table_name} AS {$alias}";
      $sql_table[] = "ON {$on}";
    }

    return $sql_table;
  }

  private static function _mergeQuery($sql_select, $sql_table, $sql_where = [], $order_by = [], $limit = '') {
    if ($sql_where) {
      $sql_cmd = 'SELECT '.implode(',', $sql_select).' '.implode(' ', $sql_table).' WHERE ('.implode(') AND (', $sql_where).')';
    } else {
      $sql_cmd = 'SELECT '.implode(',', $sql_select).' '.implode(' ', $sql_table);
    }

    // ORDER AND LIMIT
    if ($order_by) {
      $sql_order_by = [];
      foreach ($order_by as $col => $direction) {
        $sql_order_by[] = "{$col} {$direction}";
      }
      $sql_cmd = $sql_cmd.' ORDER BY '.implode(',', $sql_order_by);
    }
    if ($limit) {
      if (is_array($limit)) {
        foreach ($limit as $offset => $size) {
          $sql_limit = "LIMIT {$offset},{$size}";
        }
      } else {
        $sql_limit = "LIMIT {$limit}";
      }

      $sql_cmd = $sql_cmd.' '.$sql_limit;
    }
    return $sql_cmd.';';
  }

  public static function setStringVal($val = '') {
    return ("'{$val}'");
  }

  public static function setStringCol($col = '') {
    return ("`{$col}`");
  }
}

// Handle db/config ERROR
$medoo_engine_path = ROOT_PHP.DIRECTORY_SEPARATOR.'module_main'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'medoo.php';
if (file_exists($medoo_engine_path)) {
  include $medoo_engine_path;
  try
  {
    $conn = DB::init('general', Config::db_type, Config::db_server, Config::db_name, Config::db_username, Config::db_password, Config::db_port);
  } catch (PDOException $e) {
    echo "Database connection error: ".$e->getMessage();
    exit;
  }
} else {
  die("Database engine not found");
}