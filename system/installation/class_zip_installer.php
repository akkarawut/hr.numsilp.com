<?php

class zip_installer {
  public static function repError($msg) {
    return [
      'result'  => 'ERROR',
      'message' => $msg
    ];
  }

  public static function checkWritable($path) {
    return '0777' == substr(sprintf('%o', fileperms($path)), -4);
  }

  public static function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  public static function writeConfig($root_path, $uid = '', $data = []) {
    $_conf_config_file_template = 'template'.DIRECTORY_SEPARATOR.'config_template.php';
    $_conf_config_dir_path = $root_path.DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.'temp';
    if (file_exists($_conf_config_file_template)) {
      $template_text = '';
      if ($data) {
        ob_start();
        include $_conf_config_file_template;
        $template_text = ob_get_clean();
      }
      if (static::checkWritable($_conf_config_dir_path)) {
        $config_file_path = $_conf_config_dir_path.DIRECTORY_SEPARATOR."{$uid}.".'config.php';
        $write_len = file_put_contents($config_file_path, "<?php\n{$template_text}");
        // But always good, quite standard and secure enough:
        // File permissions: 644
        // Directory permissions: 755
        return [
          'result'                     => 'success',
          '_conf_config_file_template' => $_conf_config_file_template,
          '_conf_config_dir_path'      => $_conf_config_dir_path,
          'config_file_path'           => $config_file_path
        ];
      } else {
        return static::repError('NOT 777 Temp Directory Permission, Permission='.substr(sprintf('%o', fileperms($_conf_config_dir_path)), -4));
      }
    } else {
      return static::repError('File Template not found');
    }
    return static::repError('EOF');
  }

  public static function writeConfigModule($root_path, $uid = '', $data = []) {
    $_conf_config_file_template = 'template'.DIRECTORY_SEPARATOR.'config_module_template.php';
    $_conf_config_dir_path = $root_path.DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.'temp';
    if (file_exists($_conf_config_file_template)) {
      $template_text = '';
      if ($data) {
        ob_start();
        include $_conf_config_file_template;
        $template_text = ob_get_clean();
      }
      if (static::checkWritable($_conf_config_dir_path)) {
        $config_file_path = $_conf_config_dir_path.DIRECTORY_SEPARATOR."{$uid}.".'config_module.php';
        $write_len = file_put_contents($config_file_path, "<?php\n{$template_text}");
        // But always good, quite standard and secure enough:
        // File permissions: 644
        // Directory permissions: 755
        return [
          'result'                     => 'success',
          '_conf_config_file_template' => $_conf_config_file_template,
          '_conf_config_dir_path'      => $_conf_config_dir_path,
          'config_file_path'           => $config_file_path
        ];
      } else {
        return static::repError('NOT 777 Temp Directory Permission, Permission='.substr(sprintf('%o', fileperms($_conf_config_dir_path)), -4));
      }
    } else {
      return static::repError('File Template not found');
    }
    return static::repError('EOF');
  }

  public static function addAllFile(&$zip, $dir_path, $prefix = 'system/', $not_dir_list = []) {
    $main_dir = realpath($dir_path);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($main_dir),
      RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
      // Skip directories (they would be added automatically)
      if (!$file->isDir()) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($main_dir) + 1);
        // Add current file to archive
        $skiped = false;
        if ($not_dir_list) {
          $debug = [];
          foreach ($not_dir_list as $i_exclude_code) {
            $debug[] = [$relativePath, $i_exclude_code];
            if (static::startsWith($relativePath, $i_exclude_code)) {
              $skiped = true;
            }
          }
          // echo json_encode($debug);
        }
        if (!$skiped) {
          $zip->addFile($filePath, $prefix.'/'.$relativePath);
        }
      }
    }
    return $zip; // this
  }

  public static function create($ex_data = []) {
    $temp_file_list = []; // Tobe Delete
    $project_name = (isset($ex_data['module_folder_name']) && $ex_data['module_folder_name']) ? $ex_data['module_folder_name'] : 'module_name';
    // https://stackoverflow.com/questions/12225964/create-a-zip-file-and-download-it
    $root_path = isset($ex_data['sys_path']) ? $ex_data['sys_path'].DIRECTORY_SEPARATOR : '../';
    // CHECK PERM
    if (!static::checkWritable($root_path.'resource/temp')) {
      die('Cant write temp folder');
    }
    $zip = new ZipArchive;
    $tmp_zip_path = $root_path.'resource/temp/'.$project_name.'.'.time().'.system.zip';
    // $debug['tmp_zip_path'] = $tmp_zip_path;
    if ($zip->open($tmp_zip_path, ZipArchive::CREATE)) {
      // $zip->addFile($root_path.'api/call.php', 'system/api/call.php');
      // $zip->addFile($root_path.'api/rep.php', 'system/api/rep.php');
      static::addAllFile($zip, $root_path.'api', 'system/api');
      static::addAllFile($zip, $root_path.'installation', 'system/installation', ['download.php']);
      static::addAllFile($zip, $root_path.'module_main', 'system/module_main');
      static::addAllFile($zip, $root_path.'required', 'system/required');
      static::addAllFile($zip, $root_path.'resource', 'system/resource', ["temp/", "file/"]); // startsWithDir Do not Copy Temp
      $zip->addEmptyDir('system/resource/file'); // Empty Dir not Loop
      $zip->addEmptyDir('system/resource/temp'); // Empty Dir not Loop
      $zip->addFile($root_path.'index.php', 'system/index.php');
      // $zip->addFile($root_path.'installation.php', 'system/installation.php');
      $zip->addEmptyDir('system/config');
      if (isset($ex_data['uid'])) {
        $config_dir_path = $root_path.DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.'temp';
        $config_file_path = $config_dir_path.DIRECTORY_SEPARATOR."{$ex_data['uid']}.".'config.php';
        if (file_exists($config_file_path)) {
          $zip->addFile($config_file_path, 'system/config/config.php');
          $temp_file_list[] = ($config_file_path);
        }
        $config_file_path = $config_dir_path.DIRECTORY_SEPARATOR."{$ex_data['uid']}.".'config_module.php';
        if (file_exists($config_file_path)) {
          $zip->addFile($config_file_path, 'system/config/config_module.php');
          $temp_file_list[] = ($config_file_path);
        }
      }
      $zip->addEmptyDir('system/library');
      if (isset($ex_data['add_library'])) {
        foreach ($ex_data['add_library'] as $i_selected_lib) {
          if ($i_selected_lib == 'phpmailer') {
            static::addAllFile($zip, $root_path.'library/PHPMailer', 'system/library/PHPMailer');
          } else if ($i_selected_lib == 'phpexcel') {
            static::addAllFile($zip, $root_path.'library/PHPExcel', 'system/library/PHPExcel');
            $zip->addFile($root_path.'library/PHPExcel.php', 'system/library/PHPExcel.php');
          } else if ($i_selected_lib == 'aws') {
            static::addAllFile($zip, $root_path.'library/aws', 'system/library/aws');
            $zip->addFile($root_path.'library/sdkSpaces.php', 'system/library/sdkSpaces.php');
          } else if ($i_selected_lib == 'oss') {
            static::addAllFile($zip, $root_path.'library/sdkOss', 'system/library/sdkOss');
            $zip->addFile($root_path.'library/sdkOss.php', 'system/library/sdkOss.php');
          }
        }
      }
      $zip->addEmptyDir('system/module/general');
      if (isset($ex_data['add_module'])) {
        foreach ($ex_data['add_module'] as $i_selected_lib) {
          if ($i_selected_lib == 'User') {
            static::addAllFile($zip, $root_path.'module/general/user', 'system/module/general/user');
          } else if ($i_selected_lib == 'Version') {
            static::addAllFile($zip, $root_path.'module/general/version', 'system/module/general/version');
          } else if ($i_selected_lib == 'Stack') {
            static::addAllFile($zip, $root_path.'module/general/stack', 'system/module/general/stack');
          }
        }
      }
      $module_type = 'specific';
      if (isset($ex_data['module_type']) && $ex_data['module_type']) {
        $module_type = $ex_data['module_type'];
      }
      $zip->addEmptyDir("system/module/specific");
      $zip->addEmptyDir("system/module/general");
      $zip->addEmptyDir("system/module/$module_type/".$project_name);
      $zip->addEmptyDir("system/module/$module_type/".$project_name."/class");
      $zip->addEmptyDir("system/module/$module_type/".$project_name."/database");
      $zip->addEmptyDir("system/module/$module_type/".$project_name."/database/view");
      $zip->addEmptyDir("system/module/$module_type/".$project_name."/database/table");

      $zip->close();
      if (isset($debug) && $debug) {
        echo 'Archive created!';
        echo json_encode($debug);
        usleep(100);
        unlink($tmp_zip_path);
        die();
      }
      // TEST before Release
      $zip = new ZipArchive();
      $res = $zip->open($tmp_zip_path, ZipArchive::CHECKCONS);
      if ($res !== true) {
        switch ($res) {
        case ZipArchive::ER_NOZIP:
          die('not a zip archive');
        case ZipArchive::ER_INCONS:
          die('consistency check failed');
        case ZipArchive::ER_CRC:
          die('checksum failed');
        default:
          die('error '.$res);
        }
      }
      // Download
      header('Content-disposition: attachment; filename='.$project_name.'.zip');
      header('Content-type: application/zip');
      readfile($tmp_zip_path);
      usleep(100);
      unlink($tmp_zip_path);
      foreach ($temp_file_list as $i_del_path) {
        unlink($i_del_path);
      }
      echo 'Your system.zip is ready. FTP all file to new SERVER, then goto /system_installation.php';
      die();
    } else {
      echo 'Failed!';
    }
  }
}
?>