<?php

// $root_path = dirname(__FILE__);
// $root_path = dirname('..'); // [TODO]

// include '../module_main/core/installation.php';
class Installation {
  public static function getPath() {
    return dirname(__FILE__).DIRECTORY_SEPARATOR.'installation.php';
  }

  private static function _checkPermissionWritable($path) {
    return '0777' == substr(sprintf('%o', fileperms($path)), -4);
  }

  public static function checkPermissionUpdatable($path) {
    $prem = substr(sprintf('%o', fileperms($path)), -4);
    return ('0777' == $prem) || ('0664' == $prem);
  }

  public static function repError($msg) {
    return [
      'result'  => 'ERROR',
      'message' => $msg
    ];
  }

  public static function checkFolderPermission($dir_path) {
    $return = false;
    if (file_exists($dir_path)) {
      if (static::_checkPermissionWritable($dir_path)) {
        $return = true;
      }
    }
    return $return;
  }

  public static function hasConfigFile($root_path = '', $is_absolute_path = false) {
    $return = false;

    if (!$is_absolute_path) {
      $config_file_path = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
    } else {
      $config_file_path = $root_path;
    }

    if (file_exists($config_file_path)) {
      // [LATER] Cannot declare class Config, because the name is already in use in /var/www/html/test/system/config/config.php on line 2
      // ob_start();
      // include $config_file_path;
      // if (defined('Config::db_server') && (Config::db_server)) {
      //   // echo Config::db_server;
      //   // echo "SUCCESS";
      //   // return true;
      // }
      // if (!class_exists('DB_Init')) {
      //   include $root_path.DIRECTORY_SEPARATOR.'required'.DIRECTORY_SEPARATOR.'import.php';
      // }
      // if (class_exists('DB_Init')) {
      //   // DB_Init::upgradeTable();
      //   // DB_Init::regenerateSQLTable();
      // } else {
      //   echo 'Class DB_Init not Exists.';die();
      // }
      //$contactStr = ob_get_clean();
      $return = true;
    }
    return $return;
  }

  public static function setConfig($root_path, $data = []) {
    $_conf_config_file_template = 'template'.DIRECTORY_SEPARATOR.'config_template.php';
    $_conf_temp_file_path = 'template'.DIRECTORY_SEPARATOR.'config_output.php';
    $_conf_config_dir_path = $root_path.DIRECTORY_SEPARATOR.'config';
    if (file_exists($_conf_config_file_template)) {
      $template_text = '';
      if ($data) {
        ob_start();
        include $_conf_config_file_template;
        $template_text = ob_get_clean();
      }
      if (static::_checkPermissionWritable($_conf_config_dir_path)) {
        $config_file_path = $_conf_config_dir_path.DIRECTORY_SEPARATOR.'config.php';
        $write_len = file_put_contents($config_file_path, "<?php\n{$template_text}");
        // But always good, quite standard and secure enough:
        // File permissions: 644
        // Directory permissions: 755
        // unlink($_conf_config_file_template);
        // not working chmod($config_file_path, 0644);
        // copy($_conf_temp_file_path, $_conf_config_file_path);
        // chmod($_conf_config_file_path, 0644);
        // unlink($_conf_temp_file_path);
        // unlink($setup_file_path);
        return 'UPDATE SUCCESS';
        return [
          '_conf_config_file_template' => $_conf_config_file_template,
          '_conf_temp_file_path'       => $_conf_temp_file_path,
          '_conf_config_dir_path'      => $_conf_config_dir_path,
          'config_file_path'           => $config_file_path
        ];
      } else {
        return static::repError('NOT 777 Temp Config Permission, temp_file_path='.substr(sprintf('%o', fileperms($_conf_config_dir_path)), -4));
      }
    } else {
      return static::repError('File config_file_template not found');
    }
    return static::repError('EOF');
  }

  public static function checkConfigFolder($root_path) {
    $return = false;
    $_conf_config_folder = $root_path.DIRECTORY_SEPARATOR.'config';
    if (file_exists($_conf_config_folder)) {
      if (static::_checkPermissionWritable($_conf_config_folder)) {
        $return = true;
      }
    }
    return $return;
  }

  public static function checkConfigTemplateFile($root_path) {
    $return = false;
    $_conf_config_file_template = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_template.php';

    if (file_exists($_conf_config_file_template)) {
      if (static::_checkPermissionWritable($_conf_config_file_template)) {
        $return = true;
      }
    }
    return $return;
  }

  public static function checkConfigOutputFile($root_path) {
    $return = false;
    $_conf_temp_file_path = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_output.php';

    if (file_exists($_conf_temp_file_path)) {
      if (static::_checkPermissionWritable($_conf_temp_file_path)) {
        $return = true;
      }
    }
    return $return;
  }

  public static function checkResourceFolder($root_path) {
    $return = false;
    $_conf_config_folder = $root_path.DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.'file';
    if (file_exists($_conf_config_folder)) {
      if (static::_checkPermissionWritable($_conf_config_folder)) {
        $return = true;
      }
    }
    return $return;
  }

  public static function queryFetchAll($sql_cmd) {
    $return = false;

    $root_path = '';
    include $root_path.'../module_main/core/medoo.php';

    try {
      $conn = new Medoo([
        'database_type' => 'mysql',
        'server'        => Config::db_server,
        'database_name' => Config::db_name,
        'username'      => Config::db_username,
        'password'      => Config::db_password,
        'port'          => Config::db_port,
        'charset'       => 'utf8'
      ]);
      $init_query = [
        "SET time_zone='+07:00'",
        "USE ".$db_name
      ];
      $conn->query(implode(';', $init_query));
      $return = $conn->query($sql_cmd)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // echo "Database connection error: ".$e->getMessage();
      // exit;
    }
    return $return;
  }

  public static function selectTableStructure() {
    $schema = [];

    $sql_cmd = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".Config::db_name."'";
    $sql_cmd .= " ORDER BY ORDINAL_POSITION;";

    $selected_cols = static::queryFetchAll($sql_cmd);
    if ($selected_cols === false) {
      return 'Can not connect. Please recheck server and config again.';
    }
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
}

function run_installation($data) {
  $root_path = dirname('../');
  // echo $root_path ;
  if (!Installation::hasConfigFile($root_path)) {
    // INIT
    if (!isset($data['db_server'])) {
      return (Installation::repError('Invalid config data'));
    }
    $r = Installation::setConfig($root_path, $data);
    if ($r === true) {
      return (['result' => 'SUCCESS']);
    } else {
      return ($r);
    }
  } else {
    return (['result' => 'SUCCESS']);
  }

  return false;
}

function test_db_connection($db_server, $db_name, $db_username, $db_password, $db_port) {
  $result = false;

  $root_path = '';
  include $root_path.'../module_main/core/medoo.php';

  try {
    $conn = new Medoo([
      'database_type' => 'mysql',
      'server'        => $db_server,
      'database_name' => $db_name,
      'username'      => $db_username,
      'password'      => $db_password,
      'port'          => $db_port,
      'charset'       => 'utf8'
    ]);
    $init_query = [
      "SET time_zone='+07:00'",
      "USE ".$db_name
    ];
    $conn->query(implode(';', $init_query));
    $result = true;
  } catch (PDOException $e) {
    // echo "Database connection error: ".$e->getMessage();
    // exit;
  }
  return $result;
}

function checkPermissionWritable($path) {
  return '0777' == substr(sprintf('%o', fileperms($path)), -4);
}

function checkFolderPermission($dir_path) {
  $return = false;
  if (file_exists($dir_path)) {
    if (checkPermissionWritable($dir_path)) {
      $return = true;
    }
  }
  return $return;
}

// $root_path = dirname(dirname(__FILE__));
// if (isset($_POST['cmd_update_database'])) {
//   $result = Installation::setConfig($root_path, $_REQUEST);
//   // if (isset($result['result'])) {
//   $post_result = json_encode($result, JSON_UNESCAPED_UNICODE);
// }
