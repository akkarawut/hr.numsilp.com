<?php
class Installation {
  public static function getPath() {
    return dirname(__FILE__).DIRECTORY_SEPARATOR.'installation.php';
  }

  private static function _checkPermissionWritable($path) {
    return '0777' == substr(sprintf('%o', fileperms($path)), -4);
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

  public static function hasConfigFile($root_path = '') {
    $config_file_path = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
    if (file_exists($config_file_path)) {
      // Cannot declare class Config, because the name is already in use in /var/www/html/test/system/config/config.php on line 2
      //ob_start();
      // include $config_file_path;
      // if (defined('Config::db_server') && (Config::db_server)) {
      //   // echo Config::db_server;
      //   // echo "SUCCESS";
      //   // return true;
      // }
      if (!class_exists('DB_Init')) {
        include $root_path.DIRECTORY_SEPARATOR.'required'.DIRECTORY_SEPARATOR.'import.php';
      }
      if (class_exists('DB_Init')) {
        DB_Init::upgradeTable();
        DB_Init::regenerateSQLTable();
      } else {
        echo 'Class DB_Init not Exists.';die();
      }
      //$contactStr = ob_get_clean();
      return true;
    }
    return false;
  }

  public static function setConfig($root_path, $data = []) {
    $_conf_config_file_template = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_template.php';
    $_conf_temp_file_path = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_output.php';
    $_conf_config_file_path = $root_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
    if (file_exists($_conf_config_file_template)) {
      if (static::_checkPermissionWritable($_conf_config_file_template)) {
        $template_text = '';
        if ($data) {
          ob_start();
          include $_conf_config_file_template;
          $template_text = ob_get_clean();
        }
        if (file_exists($_conf_temp_file_path)) {
          if (static::_checkPermissionWritable($_conf_temp_file_path)) {
            $write_len = file_put_contents($_conf_temp_file_path, "<?php\n{$template_text}");
            // But always good, quite standard and secure enough:
            // File permissions: 644
            // Directory permissions: 755
            unlink($_conf_config_file_template);
            // not working chmod($config_file_path, 0644);
            copy($_conf_temp_file_path, $_conf_config_file_path);
            chmod($_conf_config_file_path, 0644);
            unlink($_conf_temp_file_path);
            // unlink($setup_file_path);
            return static::hasConfigFile($root_path);
          } else {
            return static::repError('NOT 777 Temp Config Permission, temp_file_path='.substr(sprintf('%o', fileperms($_conf_temp_file_path)), -4));
          }
        } else {
          return static::repError('File config_file_path not found');
        }
      } else {
        return static::repError('NOT 777 Template Permission, config_file_template='.substr(sprintf('%o', fileperms($_conf_config_file_template)), -4));
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
}