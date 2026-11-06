<?php
class DB_Init {
  private static $conf = [
    'description' => 'คลาสสำหรับก่อตั้งฐานข้อมูล'
  ];

  private static function _getTableSchema($name) {
    $return = $name;
    // [SUPPORT] mssql
    if (Config::db_type == 'mssql') {
      $return = "[{$name}]";
    } else {
      $return = "`{$name}`";
    }
    return $return;
  }

  private static function _dropSQLTable() {
    // delete database
    Amst::query("DROP DATABASE IF EXISTS ".static::_getTableSchema(Config::db_name).";");
    Amst::query("CREATE DATABASE ".static::_getTableSchema(Config::db_name).";");
    return true;
  }

  /**
  | Group | Operation
  | Description | Backup ข้อมูลในระบบ
   */
  static function backup() {
    $user = Config::db_username;
    $pass = Config::db_password;
    $host = Config::db_server;
    $name = Config::db_name;
    $backup_name = Config::customer_name."_".date('YmdHis').".sql";
    $tables = false;
    $mysqli = new mysqli($host, $user, $pass, $name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8mb4'");

    $queryTables = $mysqli->query('SHOW TABLES');
    while ($row = $queryTables->fetch_row()) {
      $target_tables[] = $row[0];
    }

    if (false !== $tables) {
      $target_tables = array_intersect($target_tables, $tables);
    }

    foreach ($target_tables as $table) {
      $result = $mysqli->query('SELECT * FROM '.$table);
      $fields_amount = $result->field_count;
      $rows_num = $mysqli->affected_rows;
      $res = $mysqli->query('SHOW CREATE TABLE '.$table);
      $TableMLine = $res->fetch_row();
      $content = (!isset($content) ? '' : $content)."\n\n".$TableMLine[1].";\n\n";

      for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
        while ($row = $result->fetch_row()) {
          //when started (and every after 100 command cycle):
          if ($st_counter % 100 == 0 || $st_counter == 0) {
            $content .= "\nINSERT INTO ".$table." VALUES";
          }

          $content .= "\n(";
          for ($j = 0; $j < $fields_amount; $j++) {
            $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
            $content .= (isset($row[$j])) ? '"'.$row[$j].'"' : '""';

            if ($j < ($fields_amount - 1)) {
              $content .= ',';
            }
          }
          $content .= ")";

          //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
          if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
            $content .= ";";
          } else {
            $content .= ",";
          }

          $st_counter = $st_counter + 1;
        }
      }

      $content .= "\n\n\n";
    }

    // set backup name and prompt to download
    $backup_name = $backup_name ? $backup_name : $name.".sql";
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"".$backup_name."\"");
    echo $content;
    exit();
  }

  /**
  | Group | Operation
  | Description | ซ่อม เทเบิ้ลให้เป็นเวอร์ชั่นปัจจุบัน
   */
  static function upgradeTable() {
    $rep = [
      'current_version' => '3.0'
    ];

    // ยังไม่มี

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | เช็คไฟล์ PHP เผลอ Echo อะไรมาอะป่าววว
   */
  static function checkInvalidPhpFile() {
    $rep = [
      'msg' => 'Checking..'
    ];

    // SYS_view
    $sys_view_path = ROOT_PHP.'/required/view.php';
    if (file_exists($sys_view_path)) {
      // CHECK
      ob_start();
      include $sys_view_path;
      $content = ob_get_contents();
      ob_end_clean();
      if (!empty($content)) {
        $rep['err_list'][] = [
          'file_path'       => $sys_view_path,
          'showing_content' => $content,
          'err_msg'         => "You forgot to delete echos for this function"
        ];
      }
    }

    //// INSTALLED MODULEs
    $functionlist = Func::select();
    foreach ($functionlist as $function) {
      // info module
      $modulename = $function['module'];
      // add table if exists
      if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/')) {
        $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/');
        foreach ($files as $file) {
          $type = pathinfo($file, PATHINFO_EXTENSION);
          if ('.' != $file && '..' != $file && ($file != 'index.php') && $type == 'php') {
            // CHECK
            ob_start();
            include ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/'.$file;
            $content = ob_get_contents();
            ob_end_clean();
            if (!empty($content)) {
              $rep['err_list'][] = [
                'file_path'       => $sys_view_path,
                'showing_content' => $content,
                'err_msg'         => "You forgot to delete echos for this function"
              ];
            }
          }
        }
      }
    }

    if (!isset($rep['err_list'])) {
      $rep['msg'] = 'NO ERROR FILE YATTA!';
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | เช็ค ฐานข้อมูล กับ info ว่าตรงกันไหม
   */
  static function diagnoseSQLTable($options = []) {
    $rep = [
      'output' => []
    ];

    $full_info = Util::getOptionsValue('full_info', $options, false);
    $is_strict = Util::getOptionsValue('is_strict', $options, true);

    // system_tables
    foreach (Info::$system_tables as $tb_name => $i_col) {
      $db_table = DB::selectTableStructure($tb_name);
      if (isset($db_table[$tb_name])) {
        // in INFO
        $tb1_cols = [];
        $tb1_const = [];
        foreach ($i_col as $i_col_name => $i_col_info) {
          $tb1_cols[$i_col_name] = Util::getFullDataType($i_col_info);
          $i_cont = static::getAttrConstraintName($i_col_info);
          if ($i_cont) {
            $tb1_const[$i_col_name] = $i_cont;
          }
        }
        // in REAL DATABASE
        $tb2_cols = [];
        $tb2_const = [];
        foreach ($db_table[$tb_name] as $i_col_info) {
          // Exclude Sys_fields
          if ($i_col_info['name'] == 'insert_date_time') {
            break;
          }
          if ($i_col_info['name'] != 'id') {
            $tb2_cols[$i_col_info['name']] = Util::getFullDataType($i_col_info['col_type']);
            $i_cont = ($i_col_info['column_key']);
            if ($i_cont) {
              if ($i_cont == 'UNI') {
                $tb2_const[$i_col_info['name']] = 'UNIQUE';
              } else if ($i_cont == 'MUL') {
                $tb2_const[$i_col_info['name']] = 'INDEX';
              } else {
                $tb2_const[$i_col_info['name']] = $i_cont;
              }
            }
          }
        }
        if ($full_info) {
          $rep['output'][$tb_name]['tb1_cols'] = $tb1_cols;
          $rep['output'][$tb_name]['tb1_const'] = $tb1_const;
          $rep['output'][$tb_name]['tb2_cols'] = $tb2_cols;
          $rep['output'][$tb_name]['tb2_const'] = $tb2_const;
        }

        // COMPARING DATA TYPE
        foreach ($tb1_cols as $i_col_name => $i_col_type) {
          if (!isset($tb2_cols[$i_col_name])) {
            $rep['output'][$tb_name]['error'][] = "db_table.{$i_col_name} Doesnt Exists.";
          } else {
            if ($i_col_type != $tb2_cols[$i_col_name]) {
              $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name}.{$i_col_type} != db_table.{$i_col_name}.{$tb2_cols[$i_col_name]}.";
            }
          }
        }
        foreach ($tb2_cols as $i_col_name => $i_col_type) {
          if (!isset($tb1_cols[$i_col_name])) {
            $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name} Doesnt Exists.";
          }
        }
        // COM CONSTAINT
        foreach ($tb1_const as $i_col_name => $i_col_type) {
          if (!isset($tb2_const[$i_col_name])) {
            $rep['output'][$tb_name]['error'][] = "db_table.{$i_col_name} Doesnt have '{$i_col_type}' Constant.";
          } else {
            if ($i_col_type != $tb2_const[$i_col_name]) {
              $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name}.{$i_col_type} != db_table.{$i_col_name}.{$tb2_const[$i_col_name]}.";
            }
          }
        }
        foreach ($tb2_const as $i_col_name => $i_col_type) {
          if (!isset($tb1_const[$i_col_name])) {
            $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name} Doesnt have '{$tb2_const[$i_col_name]}' Constant.";
          }
        }

        if (!isset($rep['output'][$tb_name]['error'])) {
          $rep['output'][$tb_name]['result'] = 'PASSED';
        } else {
          $rep['output'][$tb_name]['result'] = 'ERROR';
        }
      } else {
        $rep['output'][$tb_name] = 'ERROR : DB_TABLE NOT FOUND';
      }
    }

    // Module
    //// create Function
    $functionlist = Func::select();

    // initial
    if ($functionlist != null) {
      foreach ($functionlist as $function) {
        // info module
        $modulename = $function['module'];

        // add table if exists
        if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/')) {
          $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/');
          foreach ($files as $file) {
            $type = pathinfo($file, PATHINFO_EXTENSION);
            if ('.' != $file && '..' != $file && ($file != 'index.php') && $type == 'php') {
              $module_tables = null;
              include ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/'.$file;
              if (isset($module_tables)) {
                foreach ($module_tables as $i_tb_name => $i_col) {
                  $tb_name = Info::module_table_prefix.$function['code'].$i_tb_name;
                  $db_table = DB::selectTableStructure($tb_name);
                  if (isset($db_table[$tb_name])) {
                    $cp_table1 = [];
                    foreach ($i_col as $i_col_name => $i_col_info) {
                      $cp_table1[$i_col_name] = Util::getFullDataType($i_col_info);
                    }
                    $cp_table2 = [];
                    foreach ($db_table[$tb_name] as $i_col_info) {
                      // Exclude Sys_fields
                      if ($i_col_info['name'] == 'insert_date_time') {
                        break;
                      }
                      if ($i_col_info['name'] != 'id') {
                        $cp_table2[$i_col_info['name']] = Util::getFullDataType($i_col_info['col_type']);
                      }
                    }
                    // $rep['output'][$tb_name]['cp_table1'] = $cp_table1;
                    // $rep['output'][$tb_name]['cp_table2'] = $cp_table2;

                    foreach ($cp_table1 as $i_col_name => $i_col_info) {
                      $i_col_type = Util::getFullDataType($i_col_info);
                      if (!isset($cp_table2[$i_col_name])) {
                        $rep['output'][$tb_name]['error'][] = "db_table.{$i_col_name} Doesnt Exists.";
                      } else {
                        if ($i_col_type != $cp_table2[$i_col_name]) {
                          $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name}.{$i_col_type} != db_table.{$i_col_name}.{$cp_table2[$i_col_name]}.";
                        }
                      }
                    }
                    foreach ($cp_table2 as $i_col_name => $i_col_type) {
                      if (!isset($cp_table1[$i_col_name])) {
                        $rep['output'][$tb_name]['error'][] = "table_info.{$i_col_name} Doesnt Exists.";
                      }
                    }

                    if (!isset($rep['output'][$tb_name]['error'])) {
                      $rep['output'][$tb_name]['result'] = 'PASSED';
                    } else {
                      $rep['output'][$tb_name]['result'] = 'ERROR';
                    }
                  } else {
                    $rep['output'][$tb_name] = 'ERROR : DB_TABLE NOT FOUND';
                  }
                }
              }
            }
          }
        }
      }
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | generate ฐานข้อมูลใหม่
   */
  static function regenerateSQLTable() {
    $rep = [];

    if (Config::db_type == 'mssql') {
      return Amst::repSuccess($rep); // [TODO] Support mssql
    }

    //// create database
    $rep['db_name'] = Config::db_name;
    $rep['q_create_database'] = Amst::query("CREATE DATABASE IF NOT EXISTS ".static::_getTableSchema(Config::db_name).";");

    //// create default system table
    $rep['generateSystemTable'] = static::_generateSystemTable(Info::$system_tables);

    // SYS_view
    $sys_view_path = ROOT_PHP.'/required/view.php';
    if (file_exists($sys_view_path)) {
      $rep['sys_view']['sys_view_path'] = $sys_view_path;
      $prepend_data = 'CREATE OR REPLACE VIEW '.''.static::_getTableSchema(Config::db_name).'.`'.'sys_view';
      include $sys_view_path;
      // [LATER] BUG some server not get Variable $rep['sys_view']['sql_view_info'] = $sql_view_info;
      // https://stackoverflow.com/questions/16959576/reference-what-is-variable-scope-which-variables-are-accessible-from-where-and
      if (isset($sql_view_info['User'])) {
        foreach ($sql_view_info['User'] as $value) {
          if (isset($value['query']) && $value['query']) {
            $rep['sys_view']['sql'][] = $prepend_data.$value['query'];
            Amst::query($prepend_data.$value['query']);
          }
        }
      }
    }

    //// create Function
    $functionlist = Func::select();
    $debug['functionlist'] = $functionlist;
    $table_index = [];
    if ($functionlist) {
      foreach ($functionlist as $function) {
        // info module
        $modulename = $function['module'];
        $prepend_data = 'CREATE TABLE IF NOT EXISTS'.''.static::_getTableSchema(Config::db_name).'.`'.Info::module_table_prefix.$function['code'];

        // add table if exists
        if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/')) {
          $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/');
          foreach ($files as $file) {
            $type = pathinfo($file, PATHINFO_EXTENSION);
            if ('.' != $file && '..' != $file && ($file != 'index.php') && $type == 'php') {
              $module_tables = null;
              include ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/table/'.$file;
              if (isset($module_tables)) {
                foreach ($module_tables as $i_table_name => $i_table_data) {
                  $query_string = static::generateModuleTable($function, $i_table_name, $i_table_data);
                  if ($query_string) {
                    Amst::query($query_string);
                    $debug['lq'][] = DB::lastQuery();
                  }

                  foreach ($i_table_data as $column_name => $colum_type) {
                    if (strpos($colum_type, 'BIGINT') !== false) {
                      $temp = [
                        'func_tb_name' => 'func_'.$function['code'].$i_table_name,
                        'column_name'  => $column_name
                      ];
                      $table_index[] = $temp;
                    }
                  }
                }
              }
            }
          }
        }

        // add view if exists
        if (file_exists(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/view/')) {
          $files = scandir(ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/view/');
          foreach ($files as $file) {
            $type = pathinfo($file, PATHINFO_EXTENSION);
            if ('.' != $file && '..' != $file && ($file != 'index.php') && $type == 'php') {
              $prepend_data = 'CREATE OR REPLACE VIEW '.''.static::_getTableSchema(Config::db_name).'.`'.Info::module_table_prefix.$function['code'].'_view';
              include ROOT_PHP.'/'.Config_Module::$module_file[$modulename].'/database/view/'.$file;
              if (isset($sql_view_info[$modulename])) {
                foreach ($sql_view_info[$modulename] as $value) {
                  if (isset($value['query']) && $value['query']) {
                    Amst::query($prepend_data.$value['query']);
                    $debug['lq'][] = DB::lastQuery();
                  }
                }
              }
            }
          }
        }
      }

      //MAKE INDEX
      foreach ($table_index as $key => $data) {
        $table = $data['func_tb_name'];
        $column_name = $data['column_name'];
        $sql = "SHOW INDEX FROM $table;";
        $table_indexs = Amst::queryFetchAll($sql);
        $already_indexs = [];
        foreach ($table_indexs as $key => $table_index) {
          $already_indexs[] = $table_index['Column_name'];
        }
        $log = [
          'table'          => $table,
          'column_name'    => $column_name,
          'already_indexs' => $already_indexs
        ];
        // Log::add(0, $log, 0, 'backend');
        if (in_array($column_name, $already_indexs)) {
          continue;
        }
        $sql = "CREATE  INDEX $column_name ON $table($column_name);";
        Amst::query($sql);
      }
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | STRUCTURE
  | Description | create SYSTEM Table
   */
  private static function _generateSystemTable($table_info_list) {
    $return = [];

    foreach ($table_info_list as $tb_name => $i_info) {
      $sql_columns = [];
      $sql_constraint = [];
      foreach ($i_info as $i_col_name => $i_col_info) {
        $i_col_infos = explode('|', $i_col_info);
        $i_sql_col = static::_getSqlColumn($i_col_name, $i_col_infos[0]);
        if ($i_sql_col) {
          $sql_columns[] = $i_sql_col;
          // on Col-Exists
          $i_sql_const = static::_getSqlConstraint($i_col_name, $i_col_infos[0]);
          if ($i_sql_const) {
            $sql_constraint[] = $i_sql_const;
          }
        }
      }

      $query_string = '';
      if ($sql_columns) {
        $query_string = 'CREATE TABLE IF NOT EXISTS'.''.static::_getTableSchema(Config::db_name).'.'.static::_getTableSchema($tb_name).' ('.Info::db_field_prefix;
        $query_string .= implode(',', $sql_columns);
        $query_string .= ($sql_constraint) ? ','.implode(',', $sql_constraint) : '';
        $query_string .= ','.Info::db_field_postfix.') ENGINE=MyISAM;';
      }
      if ($query_string) {
        $return['sql'][] = $query_string;
        Amst::query($query_string);
      }
    }

    return $return;
  }

  /**
  | Group | STRUCTURE
  | Description | create Table
   */
  static function generateModuleTable($function, $table_name, $table_info) {
    $return = ''; // SQL QUERY

    if (!Util::startsWith($table_name, '_') && !Util::startsWith($table_name, 'sys_')) {
      Log::add(1, ["Invalid Table name.", $table_name, $function]);
      return false;
    }
    if (!$table_info) {
      Log::add(1, ["Invalid Table Data.", $table_name, $function]);
      return false;
    }

    $sql_columns = [];
    $sql_constraint = [];
    foreach ($table_info as $i_col_name => $i_col_info) {
      $i_sql_col = static::_getSqlColumn($i_col_name, $i_col_info);
      if ($i_sql_col) {
        $sql_columns[] = $i_sql_col;
        // on Col-Exists
        $i_sql_const = static::_getSqlConstraint($i_col_name, $i_col_info);
        if ($i_sql_const) {
          $sql_constraint[] = $i_sql_const;
        }
      }
    }
    if ($sql_columns) {
      $return = 'CREATE TABLE IF NOT EXISTS'.''.static::_getTableSchema(Config::db_name).'.';
      $return .= static::_getTableSchema(Info::module_table_prefix.$function['code'].$table_name);
      $return .= '('.Info::db_field_prefix;
      $return .= implode(',', $sql_columns);
      $return .= ($sql_constraint) ? ','.implode(',', $sql_constraint) : '';
      $return .= ','.Info::db_field_postfix.') ENGINE=MyISAM;';
    }
    return $return;
  }

  private static function _getSqlColumn($name, $info_string) {
    $all_data_types = ['TINYINT', 'INT', 'BIGINT', 'DECIMAL', 'CHAR', 'VARCHAR', 'TEXT', 'LONGTEXT', 'DATE', 'TIME', 'DATETIME'];
    $data_types1 = ['TINYINT', 'INT', 'BIGINT', 'TEXT', 'LONGTEXT']; // MAIN TYPE
    $data_types2 = ['DECIMAL', 'CHAR', 'VARCHAR']; // LEN TYPE
    $data_types3 = ['DATE', 'DATETIME', 'TIME']; // NULLABLE TYPE

    $data_type = false;
    // MAIN TYPE
    foreach ($data_types1 as $i_type_name) {
      if (Util::trimDataTypeName($info_string) == $i_type_name) {
        $i_rule_str = '';
        $i_info_list = explode('|', $info_string);
        foreach ($i_info_list as $i_rule) {
          preg_match_all("/\((.+?)\)/", strtolower($i_rule), $matches);
          if (Util::startsWith($i_rule, 'default(') && (count($matches) >= 2)) {
            if ($matches[1][0] == 'now') {
              $i_rule_str .= " DEFAULT (TIME(CURRENT_TIMESTAMP)) ";
              // ไม่มี  // https://stackoverflow.com/questions/53906174/current-time-as-default-value-for-time-in-mysql/53907446
            } else {
              $i_rule_str .= " DEFAULT '{$matches[1][0]}' ";
            }
          }
          if (Util::startsWith($i_rule, 'comment(') && (count($matches) >= 2)) {
            if (isset($matches[1][0])) {
              $i_rule_str .= " COMMENT '{$matches[1][0]}' ";
            }
          }
          //[TODO] RULEs
        }
        $data_type = static::_getTableSchema($name)." {$i_type_name} NOT NULL {$i_rule_str}";
      }
    }
    if (!$data_type) {
      // LEN TYPE
      foreach ($data_types2 as $i_type_name) {
        if (Util::trimDataTypeName($info_string) == $i_type_name) {
          $i_rule_str = '';
          $i_info_list = explode('|', $info_string);
          foreach ($i_info_list as $i_rule) {
            preg_match_all("/\((.+?)\)/", strtolower($i_rule), $matches);
            if (Util::startsWith($i_rule, 'default(') && (count($matches) >= 2)) {
              if ($matches[1][0] == 'now') {
                // $i_rule_str .= " DEFAULT NOW() ";
              } else {
                $i_rule_str .= " DEFAULT '{$matches[1][0]}' ";
              }
            }
            if (Util::startsWith($i_rule, 'comment(') && (count($matches) >= 2)) {
              if (isset($matches[1][0])) {
                $i_rule_str .= " COMMENT '{$matches[1][0]}' ";
              }
            }
            //[TODO] RULEs
          }
          if (isset($i_info_list[0])) {
            if (Util::startsWith($i_info_list[0], 'CHAR') || Util::startsWith($i_info_list[0], 'VARCHAR')) {
              if (Util::startsWith($i_info_list[0], 'CHAR(') || Util::startsWith($i_info_list[0], 'VARCHAR(')) {
                $data_type = static::_getTableSchema($name)." {$i_info_list[0]} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL {$i_rule_str}";
              } else {
                $data_type = static::_getTableSchema($name)." {$i_info_list[0]}(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL {$i_rule_str}";
              }
            } else if (Util::startsWith($i_info_list[0], 'DECIMAL')) {
              if (Util::startsWith($i_info_list[0], 'DECIMAL(')) {
                $data_type = static::_getTableSchema($name)." {$i_info_list[0]} NOT NULL {$i_rule_str}";
              } else {
                $data_type = static::_getTableSchema($name)." {$i_info_list[0]}(14,2) NOT NULL {$i_rule_str}";
              }
            }
          } else {
            if ($i_type_name == 'VARCHAR') {
              $data_type = static::_getTableSchema($name)." {$i_type_name}(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL {$i_rule_str}"; // DEF
            } else if ($i_type_name == 'DECIMAL') {
              $data_type = static::_getTableSchema($name)." {$i_type_name}(14,2) NOT NULL {$i_rule_str}"; // DEF
            }
          }
        }
      }
    }
    if (!$data_type) {
      // NULLABLE TYPE
      foreach ($data_types3 as $i_type_name) {
        if (Util::trimDataTypeName($info_string) == $i_type_name) {
          $i_rule_str = '';
          $i_info_list = explode('|', $info_string);
          foreach ($i_info_list as $i_rule) {
            preg_match_all("/\((.+?)\)/", strtolower($i_rule), $matches);
            if (Util::startsWith($i_rule, 'default(') && (count($matches) >= 2)) {
              if ($matches[1][0] == 'now') {
                if ($i_type_name == 'DATE') {
                  $i_rule_str .= " DEFAULT (DATE(CURRENT_TIMESTAMP)) ";
                } else {
                  $i_rule_str .= " DEFAULT CURRENT_TIMESTAMP ";
                }
              } else {
                if (isset($matches[1][0])) {
                  $i_rule_str .= " DEFAULT '{$matches[1][0]}' ";
                }
              }
            }
            if (Util::startsWith($i_rule, 'comment(') && (count($matches) >= 2)) {
              if (isset($matches[1][0])) {
                $i_rule_str .= " COMMENT '{$matches[1][0]}' ";
              }
            }
            //[TODO] RULEs
          }
          $data_type = static::_getTableSchema($name)." {$i_type_name} NULL {$i_rule_str}";
        }
      }
    }

    return str_replace('  ', ' ', trim($data_type));
  }

  private static function _getSqlConstraint($col_name, $info_string) {
    $return = '';
    $supported_info_attr = ['INDEX', 'UNIQUE'];

    $unique_info = '';
    $index_info = '';
    $attr_list = explode('|', $info_string);
    foreach ($attr_list as $i_attr) {
      if (($i_attr == 'UNIQUE')) {
        // [LATER] Multiple Unique Columns
        $unique_info = "UNIQUE ".static::_getTableSchema("uni_{$col_name}")." (".static::_getTableSchema("uni_{$col_name}").")";
      }
      if (($i_attr == 'INDEX')) {
        $unique_info = "INDEX ".static::_getTableSchema("idx_{$col_name}")." (".static::_getTableSchema("uni_{$col_name}").")";
      }
    }

    // Every UNIQUE field is by definition indexed with a UNIQUE INDEX,
    // you dont have to index it again. When you specify UNIQUE KEY, the column is indexed.
    if ($unique_info) {
      $return = $unique_info;
    } else if ($index_info) {
      $return = $index_info;
    }
    return $return;
  }

  public static function getAttrConstraintName($info_string) {
    $return = '';
    $supported_info_attr = ['INDEX', 'UNIQUE'];

    $unique_info = '';
    $index_info = '';
    $attr_list = explode('|', $info_string);
    foreach ($attr_list as $i_attr) {
      if (($i_attr == 'UNIQUE')) {
        // [LATER] Multiple Unique Columns
        $unique_info = "UNIQUE";
      }
      if (($i_attr == 'INDEX')) {
        $index_info = "INDEX";
      }
    }

    // Every UNIQUE field is by definition indexed with a UNIQUE INDEX,
    // you dont have to index it again. When you specify UNIQUE KEY, the column is indexed.
    if ($unique_info) {
      $return = $unique_info;
    } else if ($index_info) {
      $return = $index_info;
    }
    return $return;
  }

  public static function checkUpdateFields($key, $data) {
    $return = false;

    if (is_array($data)) {
      if (array_key_exists($key, $data)) {
        return $key;
      } else if (array_key_exists("{$key}[+]", $data)) {
        return "{$key}[+]";
      } else if (array_key_exists("{$key}[-]", $data)) {
        return "{$key}[-]";
      }
    }

    // SYS
    // $sys_pattern = [
    //   '/\[(.)\]/i',
    // ];
    // foreach ($sys_pattern as $i_pattern) {
    //   if (preg_match($i_pattern, $code) == 1) {
    //     $rep['product_list'] = preg_replace($i_pattern, "", $code);
    //   }
    // }

    return $return;
  }

  /**
  | Group | DB DIAGNOSE ALTER
  | Description | 'DB DIAGNOSE ALTER'
   */
  public static function countDiagnoseErrorTable() {
    $diagnose = static::diagnoseErrorTable();
    $count = count($diagnose);
    unset($diagnose);
    return $count;
  }

  /**
  | Group | DB DIAGNOSE ALTER
  | Description | 'DB DIAGNOSE ALTER'
   */
  public static function diagnoseErrorTable() {
    $system_columns = [
      'id',
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

    $where = [
      'AND' => [
        'module[!]'     => 'User',
        'system_delete' => 0
      ]
    ];
    $modules = Amst::select('sys_function', '*', $where);
    if (!$modules) {
      // return Amst::repError('E0000', 'Module not found.');
      return [];
    }
    $tb_columns_doesnt_exist = [];
    foreach ($modules as $module) {
      $code = $module['code'];
      $module_name = strtolower($module['module']);
      $module_path = ROOT_PHP.'/module/specific/'.$module_name;

      //check path
      $check_path = false;
      if (file_exists($module_path.'/database/table/')) {
        $check_path = true;
      } else {
        $module_path = ROOT_PHP.'/module/general/'.$module_name;
        if (file_exists($module_path.'/database/table/')) {
          $check_path = true;
        }
      }

      if (!$check_path) {
        continue;
      }

      if (file_exists($module_path.'/database/table/')) {
        $files = scandir($module_path.'/database/table/');
        $files = array_diff($files, ['..', '.']);
        $files = array_values($files);

        //get file content
        foreach ($files as $file_name) {
          $file_path = $module_path.'/database/table/'.$file_name;
          include $file_path;
          $file_tables = $module_tables; //variable from $file_path
          foreach ($file_tables as $table => $columns) {
            $file_db_info = $columns;
            $table_name = 'func_'.$code.$table;
            $db_table = DB::selectTableStructure($table_name);
            $db_table = isset($db_table[$table_name]) ? $db_table[$table_name] : [];
            $db_columns = [];

            foreach ($db_table as $db_table_data) {
              if (!in_array($db_table_data['name'], $system_columns)) {
                $db_columns[$db_table_data['name']] = strtoupper($db_table_data['data_type']);
              }
            }

            //compare file_info to db_info
            $columns_doesnt_exist = [];
            foreach ($file_db_info as $column_name => $column_type) {
              if (!isset($db_columns[$column_name])) {
                $columns_doesnt_exist[$column_name] = $column_type;
              }
            }
            if ($columns_doesnt_exist) {
              $tb_columns_doesnt_exist[$table_name] = $columns_doesnt_exist;
            }
          }
        }
      }
    }

    //ignore column
    foreach ($tb_columns_doesnt_exist as $tb_name => $columns) {
      $where = [
        'AND' => [
          'table_name'    => $tb_name,
          'system_delete' => 0
        ]
      ];
      $ignore_columns = Amst::selectNokey('sys_dbcheck_ignore_column', 'column_name', $where);
      if ($columns) {
        $temp = [];
        foreach ($columns as $column_name => $column_type) {
          if (!in_array($column_name, $ignore_columns)) {
            $temp[$column_name] = $column_type;
          }
        }
        $tb_columns_doesnt_exist[$tb_name] = $temp;
        unset($temp);
      }
    }

    //re-check data
    foreach ($tb_columns_doesnt_exist as $tb_name => $columns) {
      if (!$columns) {
        unset($tb_columns_doesnt_exist[$tb_name]);
      }
    }

    return $tb_columns_doesnt_exist;
  }

  /**
  | Group | DB DIAGNOSE ALTER
  | Description | 'DB DIAGNOSE ALTER'
  | $alter_data | [force_alter, alter_data]
  | $alter_diagnose | alter_diagnose
   */
  public static function alterColumns($alter_data = [], $alter_diagnose = [], $options = []) {
    $rep = [
      'params'        => func_get_args(),
      'alter_success' => [],
      'alter_failed'  => []
    ];

    // if ($example_data) {
    //   $example_data = [
    //     'force_alter' => false,
    //     'alter_data'  => [
    //       'func_'.$code.'_example_1' => [
    //         'column_name_1'  => 'VARCHAR',
    //         'column_name_2'  => 'VARCHAR(255)',
    //         'column_name_3'  => 'TINYINT',
    //         'column_name_4'  => 'INT',
    //         'column_name_5'  => 'BIGINT',
    //         'column_name_6'  => 'TEXT',
    //         'column_name_7'  => 'DATETIME',
    //         'column_name_8'  => 'DATE',
    //         'column_name_9'  => 'TIME',
    //         'column_name_10' => 'DECIMAL',
    //         'column_name_11' => 'DECIMAL(14,2)'
    //       ],
    //       'func_'.$code.'_example_2' => [
    //         'column_name_1'  => 'VARCHAR',
    //         'column_name_2'  => 'VARCHAR(255)',
    //         'column_name_3'  => 'TINYINT',
    //         'column_name_4'  => 'INT',
    //         'column_name_5'  => 'BIGINT',
    //         'column_name_6'  => 'TEXT',
    //         'column_name_7'  => 'DATETIME',
    //         'column_name_8'  => 'DATE',
    //         'column_name_9'  => 'TIME',
    //         'column_name_10' => 'DECIMAL',
    //         'column_name_11' => 'DECIMAL(14,2)'
    //       ]
    //     ]
    //   ];
    //   return $example_data;
    // }

    $diagnoseErrorTable = static::diagnoseErrorTable();
    if ($alter_diagnose) {
      $alter_data['alter_data'] = $diagnoseErrorTable;
    }

    $force_alter = false;
    if (isset($alter_data['force_alter']) && $alter_data['force_alter']) {
      $force_alter = true;
    }

    if ($force_alter == false && !$diagnoseErrorTable) {
      return Amst::repError('E0000', 'No data to alter. (1)');
    }

    $alter_tb_columns = $alter_data['alter_data'];
    if (!$alter_tb_columns) {
      return Amst::repError('E0000', 'No data to alter. (2)');
    }

    $system_columns = [
      'id',
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

    $table_no_exists = [];
    foreach ($alter_tb_columns as $func_tb_name => $alter_tb_column) {
      //check db table exist
      $table_exist = DB::selectTableStructure($func_tb_name);
      if (!$table_exist) {
        $table_no_exists[] = $func_tb_name;
        continue;
      }
      $table_exist = $table_exist[$func_tb_name];

      if ($force_alter == false) {
        if (!isset($diagnoseErrorTable[$func_tb_name])) {
          continue;
        }
      }

      //cloumn exist
      $column_exists = [];
      foreach ($table_exist as $key => $value) {
        if (!in_array($value['name'], $system_columns)) {
          $column_exists[] = $value['name'];
        }
      }

      if (isset($options['error_column_exist'])) {
        foreach ($alter_tb_column as $column_name => $column_type) {
          if (in_array($column_name, $column_exists) || in_array($column_name, $system_columns)) {
            return Amst::repError('E0000', 'Column exist.', $column_exists);
          }
        }
      }

      $diagnose_table = isset($diagnoseErrorTable[$func_tb_name]) ? $diagnoseErrorTable[$func_tb_name] : [];
      $alter_success = [];
      $alter_failed = [];

      $columns_for_index = [];
      foreach ($alter_tb_column as $column_name => $column_type) {
        if (in_array($column_name, $column_exists) || in_array($column_name, $system_columns)) {
          continue;
        }

        if (strpos($column_type, 'BIGINT') !== false) {
          $temp = [
            'func_tb_name' => $func_tb_name,
            'column_name'  => $column_name
          ];
          $columns_for_index[] = $temp;
        }

        $after_cloumn = end($column_exists);
        $column_type = static::handleColumnType($column_type);
        $sql = "ALTER TABLE `".$func_tb_name."` ADD `".$column_name."` ".$column_type." AFTER `".$after_cloumn."`;";
        $alter = Amst::query($sql);

        if (!isset($options['no_log'])) {
          $log_data = [
            'table_name'  => $func_tb_name,
            'column_name' => $column_name,
            'column_type' => $column_type
          ];
          static::addDiagnoseLog($action = 'add', 'file_to_server', $log_data);
        }

        //convert PDOStatement to array
        $alter = json_decode(json_encode($alter), true);

        if (isset($alter['queryString'])) {
          $queryString = $alter['queryString'];
          $alter_success[$column_name] = $queryString;
          $column_exists[] = $column_name;
        } else {
          $alter_failed[$column_name] = $alter;
        }
      }

      //MAKE INDEX
      foreach ($columns_for_index as $key => $data) {
        $table = $data['func_tb_name'];
        $column_name = $data['column_name'];
        $sql = "SHOW INDEX FROM $table;";
        $table_indexs = Amst::queryFetchAll($sql);
        $already_indexs = [];
        foreach ($table_indexs as $key => $table_index) {
          $already_indexs[] = $table_index['Column_name'];
        }
        if (in_array($column_name, $already_indexs)) {
          continue;
        }
        $sql = "CREATE  INDEX $column_name ON $table($column_name);";
        Amst::query($sql);
      }

      if ($alter_success) {
        $rep['alter_success'][$func_tb_name] = $alter_success;
      }
      if ($alter_failed) {
        $rep['alter_failed'][$func_tb_name] = $alter_failed;
      }
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | DB DIAGNOSE ALTER
  | Description | 'DB DIAGNOSE ALTER'
  | $ignore_data | ignore_data
   */
  public static function ignoreColumns($ignore_data) {
    foreach ($ignore_data as $tb_name => $cloumns) {
      foreach ($cloumns as $column_name => $column_type) {
        $i_data = [
          'table_name'  => $tb_name,
          'column_name' => $column_name
        ];
        Amst::insert('sys_dbcheck_ignore_column', $i_data);

        $log_data = [
          'table_name'  => $tb_name,
          'column_name' => $column_name
        ];
        static::addDiagnoseLog($action = 'ignore', 'ignore', $log_data);
      }
    }

    return Amst::repSuccess(true);
  }

  /**
  | Group | DB DIAGNOSE ALTER
  | Description | 'DB DIAGNOSE ALTER'
  | $column_type | column_type
   */
  public static function handleColumnType($column_type = 'TEXT') {
    //remove space
    $column_type = str_replace(' ', '', $column_type);
    $def_column_types = [
      'VARCHAR'  => "{str_replace} COLLATE 'utf8mb4_unicode_ci' NOT NULL",
      'TEXT'     => "{str_replace} COLLATE 'utf8mb4_unicode_ci' NOT NULL",
      'LONGTEXT' => "{str_replace} COLLATE 'utf8mb4_unicode_ci' NOT NULL",
      'TINYINT'  => '{str_replace} NOT NULL',
      'INT'      => '{str_replace} NOT NULL',
      'BIGINT'   => '{str_replace} NOT NULL',
      'DECIMAL'  => '{str_replace} NOT NULL',
      'DATETIME' => '{str_replace} NULL',
      'DATE'     => '{str_replace} NULL',
      'TIME'     => '{str_replace} NULL'
    ];

    if (isset($def_column_types[$column_type])) {
      $column_type_sql = $def_column_types[$column_type];
      if ($column_type == 'VARCHAR') {
        $column_type = 'TEXT'; //เก็บเป็น TEXT จะดีกว่าเรื่อง พื้นที่จัดเก็บ
        // $column_type = 'VARCHAR(255)';
      }
      if ($column_type == 'DECIMAL') {
        $column_type = 'DECIMAL(14,2)';
      }
      $column_type_sql = str_replace('{str_replace}', $column_type, $column_type_sql);
      return $column_type_sql;
    }

    //type can have length
    $type_can_have_length = ['VARCHAR', 'DECIMAL'];
    $column_type = explode('(', $column_type);
    if (isset($column_type[0]) && isset($column_type[1])) {
      if (in_array($column_type[0], $type_can_have_length)) {
        $column_type[1] = str_replace(')', '', $column_type[1]);
        if (isset($def_column_types[$column_type[0]])) {
          $column_type_sql = $def_column_types[$column_type[0]];
          $column_type_sql = str_replace('{str_replace}', $column_type[0].'('.$column_type[1].')', $column_type_sql);
          return $column_type_sql;
        }
      }
    }

    //default
    $column_type = 'TEXT';
    $column_type_sql = $def_column_types[$column_type];
    $column_type_sql = str_replace('{str_replace}', $column_type, $column_type_sql);
    return $column_type_sql;
  }

  /**
  | Group | DB DIAGNOSE LOG
  | Description | 'DB DIAGNOSE LOG'
   */
  public static function addDiagnoseLog($action = 'add', $type = 'file_to_server', $data = []) {
    $current_dev = Dev::getCurrent();
    $dev_id = $current_dev['id'];
    $json = API::call(Config::master_url, Config::master_api_key, '', 'Dev', 'getDevByID', [$dev_id], ['time_out' => 10]);
    $api = json_decode($json, true);

    $i_data = [
      'datetime'    => date('Y-m-d H:i:s'),
      'dev_id'      => ($dev_id) ? $dev_id : 0,
      'username'    => ($api['username']) ? $api['username'] : '',
      'action'      => $action,
      'type'        => $type,
      'table_name'  => ($data['table_name']) ? $data['table_name'] : '',
      'column_name' => ($data['column_name']) ? $data['column_name'] : '',
      'column_type' => ($data['column_type']) ? $data['column_type'] : ''
    ];

    return Amst::insert('sys_dbcheck_diagnose_log', $i_data);
  }

  public static function setDiagnoseLogListDetail($return, $options = []) {
    // [ExtractList]
    $selected_records = isset($return['list']) ? $return['list'] : $return;

    // [Repack]
    if (isset($return['list'])) {
      $return['list'] = $selected_records;
    } else {
      $return = $selected_records;
    }
    return $return;
  }

  /**
  | Group | DB DIAGNOSE LOG
  | Description | 'DB DIAGNOSE LOG'
  | $where | []
  | $options | [not_view(true|เอาแค่ข้อมูลหลักไป edit*เร็ว), selected_fields(String หรือ Array ของคอลัมภ์ที่ต้องการ), total_count(true|พร้อมจำนวนข้อมูลทั้งหมด ตามการกรองไม่แบ่งหน้า), page_no(1|เลขหน้า 0 คือส่งทั้งหมด), page_size(10|จำนวนข้อมูลต่อหน้า), sort_col(เรียงตามชื่อคอลัมภ์นี้), sort_by('ASC'|'DESC')]
   */
  public static function selectDiagnoseLog($where = [], $options = []) {
    $return = []; // list
    $sql_where = []; // list

    if (isset($where['datetime']) && $where['datetime']) {
      $sql_where['AND']['datetime[~]'] = $where['datetime'];
    }
    if (isset($where['username']) && $where['username']) {
      $sql_where['AND']['username[~]'] = $where['username'];
    }
    if (isset($where['action']) && $where['action']) {
      $sql_where['AND']['action[~]'] = $where['action'];
    }
    if (isset($where['type']) && $where['type']) {
      $sql_where['AND']['type[~]'] = $where['type'];
    }
    if (isset($where['table_name']) && $where['table_name']) {
      $sql_where['AND']['table_name[~]'] = $where['table_name'];
    }
    if (isset($where['column_name']) && $where['column_name']) {
      $sql_where['AND']['column_name[~]'] = $where['column_name'];
    }
    if (isset($where['column_type']) && $where['column_type']) {
      $sql_where['AND']['column_type[~]'] = $where['column_type'];
    }

    $selected_fields = Amst::getSelectedFields($options);
    $return = Amst::selectNoKey('sys_dbcheck_diagnose_log', $selected_fields, $sql_where, $options);
    return static::setDiagnoseLogListDetail($return, $options);
  }
}
