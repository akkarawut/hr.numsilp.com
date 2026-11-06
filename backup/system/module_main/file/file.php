<?php
class File {
  private static $sys_allow_pattern = [
    // ** ห้ามนำหน้าด้วย ตัวอักษร 5 ตัวไม่งั้นมันจะคิดว่าเป็นโค้ด เช่น login
    // PROGRAM SETTING
    '/logo_image/i',
    '/dark_logo_image/i',
    '/mobile_logo_image/i',
    '/favicon/i',
    '/signin_backgroud_image/i',
    '/signin_image/i',
    '/file/',
    // USER
    '/user_profile_image_\d{1,}/i',
    '/user_cover_image_\d{1,}/i',
    '/user_type_icon_\d{1,}/i',
    '/user_identity_card_image_\d{1,}/i',
    '/user_book_bank_image_\d{1,}/i',
    '/user_verify_idcard_\d{1,}/i',
    '/user_verify_bankcover_\d{1,}/i',
    '/sys_function_light_icon_/i',
    '/sys_function_dark_icon_/i',
    '/jform_/i'
    // ** ห้ามนำหน้าด้วย ตัวอักษร 5 ตัวไม่งั้นมันจะคิดว่าเป็นโค้ด เช่น login
  ];

  /**
   * Get Relative (file)Path Folder for files. *No leading DIRECTORY_SEPARATOR
   *
   * @author TULA
   * @param BOOL $with_tailing with or without tailing DIRECTORY_SEPARATOR
   * @return String
   */
  public static function getFolderPath($with_tailing = false) {
    return 'resource/file'.($with_tailing ? DIRECTORY_SEPARATOR : '');
  }

  /**
   * Get Full (file)Path Folder for files.
   *
   * @author TULA
   * @param BOOL $with_tailing with or without tailing DIRECTORY_SEPARATOR
   * @return String
   */
  public static function getFolderFullPath($with_tailing = false) {
    return ROOT_PHP.'/resource/file'.($with_tailing ? DIRECTORY_SEPARATOR : '');
  }

  /**
   * Get Full (URL)Path Folder for files.
   *
   * @author TULA
   * @param BOOL $with_tailing with or without tailing DIRECTORY_SEPARATOR
   * @return String
   */
  public static function getFolderUrl($with_tailing = false) {
    return ROOT_URL.'/resource/file'.($with_tailing ? '/' : '');
  }

  /**
   * Check File/Folder is has Permission 777
   *
   * @author TULA
   * @param String $path full dir path
   * @return String
   */
  public static function checkWritable($path) {
    return '0777' == substr(sprintf('%o', fileperms($path)), -4);
  }

  public static function getFilePermission($path) {
    return substr(sprintf('%o', fileperms($path)), -4);
  }

  /**
   * Prepare Folder for files.
   *
   * @author TULA
   * @return REP
   */
  public static function createFolderFile() {
    $rep = [
      'file_exists' => false
    ];

    if (defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
      $fs_dir_path = static::getFolderFullPath();
      if (!file_exists($fs_dir_path)) {
        mkdir($fs_dir_path, 0777, true);
      }
      if (!is_writable($fs_dir_path)) {
        chmod($fs_dir_path, 0777);
      }
      $rep['file_exists'] = file_exists($fs_dir_path);
    }

    // RECHECK
    if (!$rep['file_exists'] || !is_dir($fs_dir_path)) {
      return Amst::repError('No files folder');
    } else if (!is_writable($fs_dir_path)) {
      return Amst::repError('Folder is not writable');
    }
    return Amst::repSuccess($rep);
  }

  /**
   * Trim and LowerCase File Code.
   *
   * @author TULA
   * @param STRING $code fully code of File
   * @return BOOL false if not String
   */
  public static function getValidFileCode($code) {
    $return = false;

    return (is_string($code)) ? trim(strtolower($code)) : false;
  }

  /**
   * Check File Code with Setting.
   *
   * @author TULA
   * @param STRING $code fully code of File
   * @return REP
   */
  public static function checkImageCodeSetting($code) {
    $rep = [
      'code' => $code
    ];

    if ($code) {
      $code = trim(strtolower($code));
    }

    if (!$code) {
      return Amst::repError('E0001', 'File Code123');
    }

    if (strpos($code, 'jform_') === 0) {
      return Amst::repSuccess($rep);
    }

    //Extract 5CODE
    if ((strlen($code) > 5) && ($code[5] == '_')) {
      $m_code = substr($code, 0, 5);
      $rep['m_code'] = $m_code;
      // $rep['m_code_preg_match1'] = preg_match('/\w{5}_product_\d{1,}_\d{1,}/i', $code);
      // $rep['m_code_preg_match2'] = preg_match('/\w{5}_product_\d{1,}/i', $code);

      $m_conf = Module::getModuleByCode($m_code);
      if (isset($m_conf['image_code_list']) && ($m_conf['image_code_list'] == true)) {
        $rep['image_code_list'] = $m_conf['image_code_list'];
        if (is_array($m_conf['image_code_list'])) {
          foreach ($m_conf['image_code_list'] as $i_pattern) {
            //$i_code_regx = preg_replace('/^\{\$code\}$/i', "\w{5}", $i_pattern); // ไอ่ข้างล่างมันรีเพลสเราอ่ะ 555+
            $i_code_regx = substr($i_pattern, 7); // '{$code}'
            $i_code_regx = preg_replace('/[\[{\(].*?[\]}\)]/i', "\d{1,}", $i_code_regx);
            // $i_code_regx = str_replace('\\\\' , "\\", $i_code_regx);
            //$rep['i_code_regx'][$i_code_regx] = preg_match('/\w{5}'.$i_code_regx.'/i', $code);
            if (preg_match('/\w{5}'.$i_code_regx.'/i', $code) == 1) {
              return Amst::repSuccess($rep);
            }
          }
        }
      } else {
        return Amst::repError('E0000', 'Module Setting(image_code_list) not found');
      }
    } else {
      // SYS
      $sys_pattern = static::$sys_allow_pattern;
      foreach ($sys_pattern as $i_pattern) {
        if (preg_match($i_pattern, $code) == 1) {
          return Amst::repSuccess($rep);
        }
      }
    }

    return Amst::repError('E0000', "Invalid Code '{$code}'");
  }

  /**
  | Group | Operation
  | Description | 1.ใส่ไฟล์ ในรูปแบบของ Base64 *เพิ่มได้เฉพาะรูป
  | $code | โค้ดไฟล์
  | $base64 | String ที่ขึ้นต้นด้วย data:image/jpeg;base64,/9j/4AAQSkZJRgA....
   */
  public static function addBase64($code, $base64) {
    $rep = [
      'code'      => $code,
      'insert_id' => false
    ];

    // [FOLDER]
    $api = static::createFolderFile();
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $folder_exists = $api['response_data']['file_exists'];
    } else {
      return $api;
    }

    // [CODE]
    $api = static::checkImageCodeSetting($code);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code = $api['response_data']['code'];
    } else {
      return $api;
    }

    // [FILE]
    $base64_byte = '';
    $file_mime = '';
    $file_type = '';
    $file_size = 0;
    if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
      $base64 = substr($base64, strpos($base64, ',') + 1);
      $file_type = strtolower($type[1]);
      $file_mime = "image/{$file_type}";

      if (!in_array($file_type, ['jpg', 'jpeg', 'gif', 'png'])) {
        Log::add(0, $rep, 0, 'backend');
        return Amst::repError('E0000', 'invalid image type');
      }
      $base64 = str_replace(' ', '+', $base64);
      $stringLength = strlen($base64);
      $file_size = 4 * ceil(($stringLength / 3)) * 0.5624896334383812;
      $base64_byte = base64_decode($base64);

      if ($base64_byte === false) {
        Log::add(0, $rep, 0, 'backend'); // throw new \Exception('base64_decode failed');
        Log::logBackendShouldKnow('Invalid Base64', ['base64' => $base64, 'code' => $code]);
        return Amst::repError('E0000', 'base64_decode failed');
      }
    } else {
      Log::logBackendShouldKnow('Invalid Base64', ['base64' => $base64, 'code' => $code]);
      return Amst::repError('E0000', 'did not match data URI with image data');
    }

    if ($base64_byte) {
      if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
        Oss::uploadBase64File($code.'.'.$file_type, $base64_byte);
      } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
        Dspace::uploadBase64File($code.'.'.$file_type, $base64_byte);
      } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
        Azure_Storage::uploadBase64File($code.'.'.$file_type, $base64_byte);
      } else {
        // set save path
        $save_path = static::getFolderFullPath(true).$code.'.'.$file_type;
        file_put_contents($save_path, $base64_byte);
        chmod($save_path, 0777);
      }
      unset($base64_byte);
    }

    // update file in database
    $n_data = [
      'code'      => $code,
      'path'      => static::getFolderPath(true).$code.'.'.$file_type,
      'file_name' => $code.'.'.$file_type,
      'file_type' => $file_mime,
      'file_size' => (strpos($file_size, '.') === false) ? $file_size : substr($file_size, 0, strpos($file_size, '.')), // remove decimal
      'extension' => $file_type
    ];
    $where = [
      'AND' => [
        'system_delete' => 0,
        'code'          => $code
      ]
    ];
    $sys_file = Amst::get(Info::$system_table['file'], ['id'], $where);
    if (isset($sys_file['id'])) {
      Amst::updateByID(Info::$system_table['file'], $n_data, $sys_file['id']);
      $rep['insert_id'] = $sys_file['id'];
    } else {
      $rep['insert_id'] = Amst::insert(Info::$system_table['file'], $n_data);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | 2.ใส่ไฟล์ ถ้าเป็นรูปจะ Process จัดการให้
  | $code | โค้ดไฟล์
  | $file | FILE_INPUT
   */
  public static function add($code, $file) {
    $rep = [
      'insert_id' => false
    ];

    // [CODE]
    $api = static::checkImageCodeSetting($code);
    //return $api;
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code = $api['response_data']['code'];
    } else {
      return $api;
    }

    // [FILE]
    if (!isset($file['name']) || !($file['name'] != '' && !is_null($file['name']) && !is_null($file))) {
      return Amst::repError('file not in uploadable condition');
    } else if ($file['error'] > 0) {
      return Amst::repError('file error');
    } /* else if (!is_uploaded_file($file['tmp_name'])) {
    return Amst::repError('file is not upload properly');
    }*/

    // [Validate] + [File_Size]
    $file_size = isset($file['size']) ? $file['size'] : 0;
    if (defined('Config::file_max_size') && (Config::file_max_size)) {
      $file_max_size = Util::stringToByte(Config::file_max_size);
      if ($file_size > $file_max_size) {
        return Amst::repError('E0000', 'File size can not over '.Config::file_max_size);
      }
    }

    // [extension]
    $extension_tmp = explode('.', $file['name']);
    $extension = strtolower(trim(end($extension_tmp)));
    $file_mime = "application/octet-stream";
    if ('gif' == $extension) {
      $file_mime = 'image/gif';
    } else if ('png' == $extension) {
      $file_mime = 'image/png';
    } else if ('jpg' == $extension) {
      $file_mime = 'image/jpg';
    } else if ('jpeg' == $extension) {
      $file_mime = 'image/jpeg';
    }

    // [original_file_name]
    $original_file_name = '';
    $file_name_arr = explode('.', $file['name']);
    for ($i = 0; $i < count($file_name_arr) - 1; $i++) {
      $original_file_name .= $file_name_arr[$i].'.';
    }
    $original_file_name = substr($original_file_name, 0, strlen($original_file_name) - 1);

    // [StorageType]
    if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
      Oss::uploadFile($code.'.'.$extension, $file);
    } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
      Dspace::uploadFile($code.'.'.$extension, $file);
    } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
      Azure_Storage::uploadFile($code.'.'.$extension, $file);
    } else {
      // [SYS_FOLDER]
      $api = static::createFolderFile();
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $folder_exists = $api['response_data']['file_exists'];
      } else {
        return $api;
      }
      // remove old file
      static::delete($code);

      // CHECK IS IMAGE OR NOT
      if ($extension === 'png' || $extension === 'PNG' || $extension === 'jpg' || $extension === 'JPG' || $extension === 'jpeg' || $extension === 'JPEG' || $extension === 'gif' || $extension === 'GIF') {
        // // CHECK & CREATE TEMP
        // Image::createTemp();

        // // READ FILE
        // $filepath      = "../resource/file/temp/temp.txt";
        // $resize_status = file_get_contents($filepath);
        // file_put_contents($filepath, "");

        // // CHECK IS RESIZED OR NOT
        // if ($resize_status !== 'resized') {
        //   return Image::resize($code, $file, $extension, 1280);
        // }
      }

      // start save file + set save path
      $save_path = static::getFolderFullPath(true).$code.'.'.$extension;
      $result = move_uploaded_file($file['tmp_name'], $save_path);
      if (!$result) {
        return Amst::repError('error uploading file');
      }
      // set permission
      chmod($save_path, 0777);
    }

    // [SYS_FILE]
    $n_data = [
      'code'      => $code,
      'path'      => static::getFolderPath(true).$code.'.'.$extension,
      'file_name' => $original_file_name,
      'file_type' => $file_mime,
      'file_size' => (strpos($file_size, '.') === false) ? $file_size : substr($file_size, 0, strpos($file_size, '.')), // remove decimal
      'extension' => $extension
    ];
    $where = [
      'AND' => [
        'system_delete' => 0,
        'code'          => $code
      ]
    ];
    $sys_file = Amst::get(Info::$system_table['file'], ['id'], $where);
    if (isset($sys_file['id'])) {
      Amst::updateByID(Info::$system_table['file'], $n_data, $sys_file['id']);
      $rep['insert_id'] = $sys_file['id'];
    } else {
      $rep['insert_id'] = Amst::insert(Info::$system_table['file'], $n_data, ['direct' => true]);
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | 5.เพิ่มรูปโดย URL
  | $code | โค้ดโมดูล
  | $url | url
   */
  public static function addImageURL($code, $url) {
    $rep = [];

    // [CODE]
    $api = static::checkImageCodeSetting($code);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code = $api['response_data']['code'];
    } else {
      return $api;
    }

    $image_url = $url;
    $path_parts = pathinfo($image_url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $image_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_exec($ch);
    $file_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);

    // [Validate] + [File_Size]
    if (defined('Config::file_max_size') && (Config::file_max_size)) {
      $file_max_size = Util::stringToByte(Config::file_max_size);
      if ($file_size > $file_max_size) {
        return Amst::repError('E0000', 'File size can not over '.Config::file_max_size);
      }
    }

    $ch = curl_init($url); //'http://example.com/image.php'
    $fp = fopen(static::getFolderFullPath(true).$code.'.'.$path_parts['extension'], 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    $sys_file = Amst::get('sys_file', ['id'], ['code' => $code]);
    if (!isset($sys_file['id'])) {
      $i_data = [
        'code'      => $code,
        'path'      => static::getFolderPath(true).$code.'.'.$path_parts['extension'],
        'file_name' => $path_parts['filename'],
        'file_type' => 'image/'.$path_parts['extension'],
        'file_size' => $file_size,
        'extension' => $path_parts['extension']
      ];
      Amst::insert('sys_file', $i_data);
      $api = Amst::insert('sys_file', $i_data);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $rep['insert_id'] = $api['response_data']['insert_id'];
      } else {
        return $api;
      }
    } else {
      $u_data = [
        'path'      => static::getFolderPath(true).$code.'.'.$path_parts['extension'],
        'file_name' => $path_parts['filename'],
        'file_type' => 'image/'.$path_parts['extension'],
        'file_size' => $file_size,
        'extension' => $path_parts['extension']
      ];
      $api = Amst::updateByID('sys_file', $u_data, $sys_file['id']);
      if (isset($api['response_status']) && ($api['response_status'] == true)) {
        $rep['update_result'] = $api['response_data']['update_result'];
      } else {
        return $api;
      }
    }
    return Amst::repSuccess($rep);
  }

  private static function _bak_image_processing($code, $file) {
    $rep = [
      'insert_id' => false
    ];
    $save_path = '';
    $extension = '';
    // initial check image file
    $image_exts = ['JPG', 'jpg', 'JPEG', 'jpeg', 'GIF', 'gif', 'PNG', 'png'];
    $image_types = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];
    if ('GIF' == $extension || 'gif' == $extension) {
      $file_mime = 'image/gif';
    } else if ('PNG' == $extension || 'png' == $extension) {
      $file_mime = 'image/png';
    } else if ('JPG' == $extension || 'jpg' == $extension) {
      $file_mime = 'image/jpg';
    } else if ('JPEG' == $extension || 'jpeg' == $extension) {
      $file_mime = 'image/jpeg';
    }

    // if file is image
    // [20211213] TULA DISABLE THIS FEATURE == MEMORY PLOPLEM && USELESS
    if (in_array($extension, $image_exts) && in_array($file['type'], $image_types)) {
      // check rotate
      $rotate_degree = 0;
      $exif = @exif_read_data($file['tmp_name']); // , "FILE", true
      if (isset($exif['Orientation'])) {
        if (3 == $exif['Orientation']) {
          $rotate_degree = 180;
        } else if (6 == $exif['Orientation']) {
          $rotate_degree = 270;
        } else if (8 == $exif['Orientation']) {
          $rotate_degree = 90;
        }
      }

      $debug = [
        'exif'               => $exif,
        'memory_limit_start' => ini_get('memory_limit')
      ];

      // [MEMORY] fix imagecreatefromjpeg Need More MEM
      // if (isset($exif['FileSize'])) {
      //   $memory_limit = (ini_get('memory_limit'));
      //   if ($exif['FileSize'] > $memory_limit) {
      //     if (defined('Config::file_max_size') && (Config::file_max_size)) {
      //       $memory_set_to = Util::stringToByte(Config::file_max_size) * 30;
      //       $debug['memory_set_to1'] =  $memory_set_to;
      //     }
      //     // if ($exif['FileSize'] > $memory_set_to) {
      //     //   $memory_set_to = ($exif['FileSize'] * 2) ;
      //     //   $debug['memory_set_to2'] =  $memory_set_to;
      //     // }
      //     ini_set('memory_limit', $memory_set_to);
      //     $debug['memory_seted'] =  $memory_set_to;
      //     $debug['after_mem_set'] =  ini_get('memory_limit');
      //   }
      // }
      // ini_set('memory_limit', '1024M');

      $img = null;
      if ('image/gif' == $file['type']) {
        $img = @imagecreatefromgif($file['tmp_name']);
      } else if ('image/png' == $file['type']) {
        // problem : exif_read_data(phpEcBCka): File not supported
        $img = @imagecreatefrompng($file['tmp_name']);
        $img = imagerotate($img, $rotate_degree, 0);
        imagealphablending($img, false);
        imagesavealpha($img, true);
      } else if ('image/jpg' == $file['type'] || 'image/jpeg' == $file['type']) {
        $img = @imagecreatefromjpeg($file['tmp_name']);
        // problem : imagerotate() expects parameter 1 to be resource, bool given
        $img = imagerotate($img, $rotate_degree, 0);
      }

      // $debug['memory_get_usage'] = (($usage = memory_get_usage()) != '') ? number_format($usage) : 0;
      // $debug['memory_allowcated'] = (($usage = memory_get_usage(true)) != '') ? number_format($usage) : 0;
      // return Amst::repSuccess($debug);

      if (!$img) {
        return Amst::repError('image cannot initial');
      }

      $img_width = imagesx($img);
      $img_height = imagesy($img);

      // resize if it's too width
      $width_limit = 1200;
      if ($img_width > $width_limit) {
        $ratio = $img_height / $img_width;
        $img_new_height = $width_limit * $ratio;

        $img_new = imagecreatetruecolor($width_limit, $img_new_height);
        if ('image/png' == $file['type']) {
          imagealphablending($img_new, false);
          imagesavealpha($img_new, true);
        }
        imagecopyresampled($img_new, $img, 0, 0, 0, 0, $width_limit, $img_new_height, $img_width, $img_height);
        $img = $img_new;
      }

      // save image
      if ('image/gif' == $file['type']) {
        $result = imagegif($img, $save_path);
      } else if ('image/png' == $file['type']) {
        $result = imagepng($img, $save_path, 9);
      } else if ('image/jpg' == $file['type'] || 'image/jpeg' == $file['type']) {
        $result = imagejpeg($img, $save_path, 80);
      }

      if (isset($img_new)) {
        imagedestroy($img_new);
      }
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | 3.ใส่ไฟล์ ไรก็ได้ ดิบๆ ไม่แปลงไฟล์
  | $code | โค้ดไฟล์
  | $file | FILE_INPUT
   */
  public static function addRaw($code, $file) {
    $rep = [
      'insert_id' => false
    ];
    // [TODO] remove this fn.
    return static::add($code, $file);
  }

  /**
  | Group | Operation
  | Description | ลบไฟล์
  | $code | โค้ดไฟล์
   */
  public static function delete($code) {
    $rep = [
      'delete_result' => false
    ];

    if (is_array($code)) {
      $where = [
        'code' => $code
      ];
      $sys_files = Amst::selectNoKey(Info::$system_table['file'], ['id', 'path', 'code', 'extension'], $where);
      foreach ($sys_files as $i_file) {
        if (isset($i_file['id'])) {
          $where = [
            'id' => $i_file['id']
          ];
          $rep['delete_result'] = Amst::delete(Info::$system_table['file'], $where);
          // [StorageType]
          if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
            $rep['Oss::deleteFile'] = Oss::deleteFile($i_file['code'].'.'.$i_file['extension']);
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
            $rep['Dspace::deleteFile'] = Dspace::deleteFile($i_file['code'].'.'.$i_file['extension']);
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
            $rep['Azure_Storage::deleteFile'] = Azure_Storage::deleteFile($i_file['code'].'.'.$i_file['extension']);
          } else {
            $files = glob(ROOT_PHP.'/'.$i_file['path']);
            if ($files) {
              $rep['file::unlink'] = unlink(ROOT_PHP.'/'.$i_file['path']);
            }
          }
        }
      }
    } else {
      $where = [
        'code' => $code
      ];
      $sys_file = Amst::get(Info::$system_table['file'], ['id', 'path', 'code', 'extension'], $where);
      if ($sys_file) {
        $where = [
          'id' => $sys_file['id']
        ];
        $rep['delete_result'] = Amst::delete(Info::$system_table['file'], $where);
        // [StorageType]
        if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
          $rep['Oss::deleteFile'] = Oss::deleteFile($sys_file['code'].'.'.$sys_file['extension']);
        } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
          $rep['Dspace::deleteFile'] = Dspace::deleteFile($sys_file['code'].'.'.$sys_file['extension']);
        } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
          $rep['Azure_Storage::deleteFile'] = Azure_Storage::deleteFile($sys_file['code'].'.'.$sys_file['extension']);
        } else {
          $files = glob(ROOT_PHP.'/'.$sys_file['path']);
          if ($files) {
            $rep['file::unlink'] = unlink(ROOT_PHP.'/'.$sys_file['path']);
          }
        }
      }
    }
    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | ก๊อปไฟล์จากอันเก่าไปอันใหม่
  | $code_from | โค้ดไฟล์ต้นฉบับ
  | $code_to | โค้ดไฟล์ปลายทาง
   */
  public static function copy($code_from, $code_to) {
    $rep = [
      'old_path'      => '',
      'new_path'      => '',
      'update_result' => false
    ];

    // [CODE]
    $api = static::checkImageCodeSetting($code_from);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code_from = $api['response_data']['code'];
    } else {
      return $api;
    }
    $api = static::checkImageCodeSetting($code_to);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code_to = $api['response_data']['code'];
    } else {
      return $api;
    }

    $option = [
      'no_cache' => true
    ];
    $oldfilepath = File::getPath($code_from, $option);
    $oldfileextension = pathinfo($oldfilepath, PATHINFO_EXTENSION);

    $where = [
      'AND' => [
        'code'          => $code_from,
        'system_delete' => 0
      ]
    ];

    $oldfileinfo = Amst::get(Info::$system_table['file'], ['id', 'file_name', 'extension'], $where);
    if (isset($oldfileinfo['id'])) {
      $data_files = [
        'code'      => $code_to,
        'path'      => static::getFolderPath(true).$code_to.'.'.$oldfileinfo['extension'],
        'file_name' => $oldfileinfo['file_name'],
        'extension' => $oldfileinfo['extension']
      ];

      $where = [
        'AND' => [
          'code'          => $code_to,
          'system_delete' => 0
        ]
      ];
      $sys_file = Amst::get(Info::$system_table['file'], ['id'], $where);
      if (isset($sys_file['id'])) {
        Amst::updateByID(Info::$system_table['file'], $data_files, $sys_file['id']);
      } else {
        Amst::insert(Info::$system_table['file'], $data_files);
      }
    }

    $old_path = static::getFolderFullPath(true).$code_from.'.'.$oldfileextension;
    $new_path = static::getFolderFullPath(true).$code_to.'.'.$oldfileextension;
    if (!file_exists($old_path)) {
      return Amst::repError('E0000', 'file not found');
    }
    $rep = [
      'old_path'      => $old_path,
      'new_path'      => $new_path,
      'update_result' => copy($old_path, $new_path)
    ];

    return Amst::repSuccess($rep);
  }

  /**
  | Group | FileInfo
  | Description | ดึงไฟล์ตาม $code แบบเป็น array **ถ้าไม่เจอจะไม่ส่งข้อมูลไฟล์นั้นให้ ก็คือจะไม่เป็น Placeholder
  | $code | โค้ดไฟล์ เป็น String หรือ Array
  | $options | [placeholder(รูปแบบของplaceholder)]
   */
  public static function selectFileInfo($code, $where = [], $options = []) {
    $return = [];

    // [TODO] ARRAY $code = strtolower(trim("{$code}"));
    if ($code) {
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code[~]'       => ($code)
        ]
      ];
      $path_list = DB::select(Info::$system_table['file'], ['id', 'code', 'extension', 'path', 'update_date_time'], $where);
      $path_key = [];
      foreach ($path_list as $path) {
        $path_key[$path['code']] = $path;
      }
      unset($path_list);
      foreach ($code as $i_code) {
        if (isset($path_key[$i_code])) {
          if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
            $oss_link = Oss::getFileUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($oss_link) {
              $return[$i_code] = $oss_link;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
            $unsign_link = Dspace::getFileUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($unsign_link) {
              $return[$i_code] = $unsign_link;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
            $unsign_link = Azure_Storage::getFilePublicUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($unsign_link) {
              $return[$i_code] = $unsign_link;
            }
          } else {
            $return[$i_code] = ROOT_URL.'/'.$path_key[$i_code]['path'].(isset($options['no_cache']) ? '' : ('?g='.strtotime($path_key[$i_code]['update_date_time'])));
          }
        }
      }
      unset($path_key);
    }
    return $return;
  }

  /**
  | Group | FileInfo
  | Description | ดึงข้อมูลไฟล์ตาม โค้ดไฟล์
  | $code | โค้ดไฟล์
  | $options | [selected_fields(selected_fields)]
   */
  public static function getFileInfo($code, $options = []) {
    $return = null;

    $where = [
      'AND' => [
        'system_delete' => 0,
        'code'          => $code
      ]
    ];
    return Amst::get(Info::$system_table['file'], '*', $where);
  }

  private static function _getTimingFullPath($file_name, $options = []) {
    $return = ''; // PATH

    // raw(ไม่มี)|one_min(ทุกๆหนึ่งนาที)|one_hour|one_day|update_date_time(มีเป็นupdate_date_time)
    $file_cache_timing = false;
    if (Util::isHasOptions('no_cache', $options)) {
      $file_cache_timing = false;
    } else if (Util::isHasOptions('cache_timing', $options)) {
      $file_cache_timing = Util::getOptionsValue('cache_timing', $options, false);
    } else if (defined('Config::file_cache_timing') && (Config::file_cache_timing == true) && defined('Config::file_cache_timing') && (Config::file_cache_timing)) {
      $file_cache_timing = Config::file_cache_timing;
    }

    if ($file_cache_timing == 'one_min') {
      $fixed_timestamp = strtotime(date('Y-m-d H:i:00'));
    } else if ($file_cache_timing == 'one_hour') {
      $fixed_timestamp = strtotime(date('Y-m-d H:00:00'));
    } else if ($file_cache_timing == 'one_day') {
      $fixed_timestamp = strtotime(date('Y-m-d'));
    } else if ($file_cache_timing == 'update_date_time') {
      if ($update_date_time = Util::getOptionsValue('update_date_time', $options, false)) {
        $fixed_timestamp = strtotime($update_date_time);
      } else {
        $fixed_timestamp = strtotime(date('Y-m-d H:i:s'));
      }
    }
    return static::getFolderUrl(true).$file_name.(isset($fixed_timestamp) ? ('?t='.$fixed_timestamp) : '');
  }

  /**
  | Group | Operation
  | Description | ดึงที่อยู่ไฟล์ตาม โค้ดไฟล์
  | $code | โค้ดไฟล์
  | $options | [selected_fields(selected_fields), placeholder(placeholder)]
   */
  public static function getPath($code, $options = []) {
    $return = ''; // PATH

    // [DEF]
    $options = Amst::checkOption($options);
    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $link = static::getPlaceHolder($placeholder_type);

    // [CODE]
    $code = strtolower(trim($code));
    if (!$code) {
      return $link;
    }

    $file_info = []; // BUILD DATA
    if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
      $glob = [
        'code' => $code,
        'path' => (static::getFolderFullPath(true)."{$code}.*"),
        'glob' => glob(static::getFolderFullPath(true)."{$code}.*")
      ];
      if (isset($glob['glob'][0])) {
        $file_name = basename($glob['glob'][0]);
        $extension = pathinfo(static::getFolderPath(true).$file_name, PATHINFO_EXTENSION);
        $update_date_time = '2021-12-31'; // [TODO] filemtime
        $file_info = [
          'code'             => $code,
          'path'             => static::getFolderPath(true).$file_name,
          'file_name'        => $file_name,
          'extension'        => $extension,
          'link'             => $link, // NOT GET LINK HERE static::_getTimingFullPath($file_name, $options),
          'insert_user_id'   => 0,
          'update_date_time' => $update_date_time
        ];
      }
    } else {
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code'          => $code
        ]
      ];
      $file_info = Amst::get(Info::$system_table['file'], '*', $where);
    }

    if (!$file_info) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      if (isset($options['file_info'])) {
        return [
          'code'           => '',
          'path'           => '',
          'file_name'      => '',
          'extension'      => '',
          'link'           => $link,
          'insert_user_id' => 0
        ];
      }
      return $link;
    }
    if (isset($options['path'])) {
      return $file_info['path'];
    }

    // [GET_FILE_URL] + [FILE_LOCATION]
    if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
      $link = Oss::getFileUrl($file_info['code'].'.'.$file_info['extension']);
    } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
      $link = Dspace::getFileUrl($file_info['code'].'.'.$file_info['extension']);
    } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
      if ($public_url = Azure_Storage::getFilePublicUrl($file_info['code'].'.'.$file_info['extension'])) {
        $link = $public_url;
      }
    } else {
      // Timing
      if (isset($file_info['update_date_time'])) {
        $options['update_date_time'] = $file_info['update_date_time'];
      }
      $link = static::_getTimingFullPath($file_info['code'].'.'.$file_info['extension'], $options);
    }

    if (isset($options['file_info'])) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      $file_info['link'] = $link;
      return $file_info;
    }

    $return = $link;
    return $return;
  }

  /**
  | Group | Operation
  | Description | ดึงไฟล์ตาม $code แบบเป็น array
  | $code | โค้ดไฟล์ เป็น String หรือ Array
  | $options | [placeholder(รูปแบบของplaceholder)]
   */
  public static function selectPath($code, $options = []) {
    $return = [];

    // [DEF]
    $options = Amst::checkOption($options);
    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $placeholder = static::getPlaceHolder($placeholder_type);

    // [String2Array]
    if ($code && is_string($code)) {
      $code = [$code];
    }

    if ($code) {
      $file_info_list = [];
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code'          => $code
        ]
      ];
      $selected_records = DB::select(Info::$system_table['file'], ['id', 'code', 'extension', 'path', 'update_date_time'], $where);
      foreach ($selected_records as $i_file_info) {
        if (!isset($file_info_list[$i_file_info['code']])) {
          $file_info_list[$i_file_info['code']] = $i_file_info;
        }
      }
      unset($selected_records);
      foreach ($code as $i_code) {
        if (isset($file_info_list[$i_code])) {
          // [FILE_LOCATION]
          if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
            $file_url = Oss::getFileUrl($file_info_list[$i_code]['code'].'.'.$file_info_list[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
            $file_url = Dspace::getFileUrl($file_info_list[$i_code]['code'].'.'.$file_info_list[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
            $file_url = Azure_Storage::getFilePublicUrl($file_info_list[$i_code]['code'].'.'.$file_info_list[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else {
            // Timing
            if (isset($file_info_list[$i_code]['update_date_time'])) {
              $options['update_date_time'] = $file_info_list[$i_code]['update_date_time'];
            }
            $return[$i_code] = static::_getTimingFullPath($file_info_list[$i_code]['code'].'.'.$file_info_list[$i_code]['extension'], $options);
          }
        } else {
          $return[$i_code] = $placeholder;
        }
      }
      unset($path_key);
    }
    return $return;
  }

  /**
  | Group | Operation
  | Description | ดึงไฟล์ตาม $code แบบเป็น array ไม่สน DB
  | $code | โค้ดไฟล์ เป็น String หรือ Array
  | $options | [placeholder(รูปแบบของplaceholder)]
   */
  public static function selectFixedPath($code, $options = []) {
    $return = [];

    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $placeholder = static::getPlaceHolder($placeholder_type);

    if (!Util::getOptionsValue('no_cache', $options, false)) {
      //if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true)) {
      $options['no_cache'] = true;
      //}
    }

    if ($code) {
      $code = Util::makeArray($code);
      $path_key = [];
      $update_date_time = Util::getSystemTimestamp();
      $extension = Util::getOptionsValue('extension', $options, 'gif');
      foreach ($code as $i_code) {
        $path_key[$i_code] = [
          'code'             => $i_code,
          'extension'        => $extension,
          'glob_path'        => static::getFolderPath(true)."{$i_code}.*",
          'path'             => static::getFolderPath(true)."{$i_code}.{$extension}",
          'update_date_time' => $update_date_time
        ];
      }

      foreach ($code as $i_code) {
        if (isset($path_key[$i_code])) {
          // [FILE_LOCATION]
          if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
            $file_url = Oss::getFileUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
            $file_url = Dspace::getFileUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else if (defined('Config::file_storage_type') && (Config::file_storage_type == 'azure')) {
            $file_url = Azure_Storage::getFilePublicUrl($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension']);
            if ($file_url) {
              $return[$i_code] = $file_url;
            } else {
              $return[$i_code] = $placeholder;
            }
          } else {
            // Local File // $images = glob("*.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);
            $glob = [
              'i_code' => $i_code,
              'path'   => (ROOT_PHP.'/'.$path_key[$i_code]['glob_path']),
              'glob'   => glob(ROOT_PHP.'/'.$path_key[$i_code]['glob_path'])
            ];
            if (isset($glob['glob'][0])) {
              $path_key[$i_code]['path'] = static::getFolderPath(true).basename($glob['glob'][0]);
            } else {
              $glob = false;
            }
            if ($glob) {
              // [TODO] strtotime($path_key[$i_code]['update_date_time'])
              $return[$i_code] = static::_getTimingFullPath($path_key[$i_code]['code'].'.'.$path_key[$i_code]['extension'], $options);
            } else {
              $return[$i_code] = $placeholder;
            }
          }
        } else {
          $return[$i_code] = $placeholder;
        }
      }
      unset($path_key);
    }
    return $return;
  }

  /**
  | Group | Operation
  | Description | ขอรูป placeholder มา
  | $placeholder | ประเภทรูป
  | $options | [getabsolute(getabsolute)]
   */
  public static function getPlaceHolder($placeholder = 'square', $options = []) {
    $return = '';

    if (!$placeholder) {
      $placeholder = 'square';
    }

    $getabsolute = Util::getOptionsValue('getabsolute', $options, true);
    $path = $getabsolute ? ROOT_URL : ROOT_PHP;
    $return = $path.'/resource/placeholder/placeholder_'.$placeholder.'.jpg';
    return $return;
  }

  /**
   * Delete All files in Folder. **Privated due too Dangerous**
   *
   * @author TULA
   * @return TRUE
   */
  private static function _deleteAllFile() {
    $files = glob(static::getFolderFullPath(true).'*'); // get all file names
    foreach ($files as $file) {
      if (is_file($file)) {
        unlink($file);
      }
    }
    return true;
  }

  /**
  | Group | Diagnosis
  | Description | 1.เช็ค Permission Files
   */
  public static function checkSystemFilesFilePermission() {
    $rep = [
      'is_has_error' => false
    ];

    $dir_path = static::getFolderFullPath(false);
    if (static::checkWritable($dir_path)) {
      // $rep['writable'][] = $i_name;
    } else {
      $rep['is_has_error'] = true;
      $rep['error_list'][] = $dir_path;
      return Amst::repError('E0000', 'ERROR FOUND', $rep);
    }

    // $files = array_diff(scandir($path), ['.', '..']);
    foreach (new DirectoryIterator($dir_path) as $item) {
      $i_path = $item->getPathname();
      $i_name = $item->getFilename();
      if ($i_name != '.' && $i_name != '..') {
        // $rep['getFilePermission'][] = static::getFilePermission($i_path);
        if (static::checkWritable($i_path)) {
          // $rep['writable'][] = $i_name;
        } else {
          $rep['is_has_error'] = true;
          $rep['error_list'][] = $i_name;
        }
      }
    }
    if (!isset($rep['error_list'])) {
      $rep['msg'] = 'SUCCESS without error';
    } else {
      return Amst::repError('E0000', 'ERROR FOUND', $rep);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Diagnosis
  | Description | 2.เช็ค Permission Setting
   */
  public static function checkSystemSettingFilePermission() {
    $return = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $dir_path = Wloves::getSettingFullPath(false);
    if (static::checkWritable($dir_path)) {
      // $rep['writable'][] = $i_name;
    } else {
      $rep['is_has_error'] = true;
      $rep['error_list'][] = $dir_path;
      return Amst::repError('E0000', 'ERROR FOUND', $rep);
    }

    // $files = array_diff(scandir($path), ['.', '..']);
    foreach (new DirectoryIterator($dir_path) as $item) {
      $i_path = $item->getPathname();
      $i_name = $item->getFilename();
      if ($i_name != '.' && $i_name != '..') {
        // $rep['getFilePermission'][] = static::getFilePermission($i_path);
        if (static::checkWritable($i_path)) {
          // $rep['writable'][] = $i_name;
        } else {
          $rep['is_has_error'] = true;
          $rep['error_list'][] = $i_name;
        }
      }
    }
    if (!isset($rep['error_list'])) {
      $rep['msg'] = 'SUCCESS without error';
    } else {
      return Amst::repError('E0000', 'ERROR FOUND', $rep);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | 2.เช็ค Permission Setting
  | $code | code
   */
  public static function testGetAsaConfig($code = '') {
    $rep = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $rep['getConfig'] = Azure_Storage::getConfig();

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | 2.เช็ค Permission Setting
  | $code | code
  | $file | FILE
   */
  public static function testAsaFileAdd($code, $file) {
    $rep = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $rep = [
      'insert_id' => false
    ];

    // [CODE]
    $api = static::checkImageCodeSetting($code);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $code = $api['response_data']['code'];
    } else {
      return $api;
    }

    // [FILE]
    if (!isset($file['name']) || !($file['name'] != '' && !is_null($file['name']) && !is_null($file))) {
      return Amst::repError('invalid file input');
    } else if ($file['error'] > 0) {
      return Amst::repError('file error');
    } else if (!file_exists($file['tmp_name'])) {
      return Amst::repError('file is not in upload directory');
    } else if (!is_uploaded_file($file['tmp_name'])) {
      return Amst::repError('file is not upload properly');
    }

    // [Validate] + [File_Size]
    $file_size = isset($file['size']) ? $file['size'] : 0;
    if (defined('Config::file_max_size') && (Config::file_max_size)) {
      $file_max_size = Util::stringToByte(Config::file_max_size);
      if ($file_size > $file_max_size) {
        return Amst::repError('E0000', 'File size can not over '.Config::file_max_size);
      }
    }

    // [extension]
    $extension_tmp = explode('.', $file['name']);
    $extension = strtolower(trim(end($extension_tmp)));
    $file_mime = "application/octet-stream";
    if ('gif' == $extension) {
      $file_mime = 'image/gif';
    } else if ('png' == $extension) {
      $file_mime = 'image/png';
    } else if ('jpg' == $extension) {
      $file_mime = 'image/jpg';
    } else if ('jpeg' == $extension) {
      $file_mime = 'image/jpeg';
    }

    // [original_file_name]
    $original_file_name = '';
    $file_name_arr = explode('.', $file['name']);
    for ($i = 0; $i < count($file_name_arr) - 1; $i++) {
      $original_file_name .= $file_name_arr[$i].'.';
    }
    $original_file_name = substr($original_file_name, 0, strlen($original_file_name) - 1);

    // [StorageType]
    $rep['Azure_Storage.uploadFile'] = Azure_Storage::uploadFile($code.'.'.$extension, $file);

    // [SYS_FILE]
    $n_data = [
      'code'      => $code,
      'path'      => static::getFolderPath(true).$code.'.'.$extension,
      'file_name' => $original_file_name,
      'file_type' => $file_mime,
      'file_size' => (strpos($file_size, '.') === false) ? $file_size : substr($file_size, 0, strpos($file_size, '.')), // remove decimal
      'extension' => $extension
    ];
    $where = [
      'AND' => [
        'system_delete' => 0,
        'code'          => $code
      ]
    ];
    $sys_file = Amst::get(Info::$system_table['file'], ['id'], $where);
    if (isset($sys_file['id'])) {
      Amst::updateByID(Info::$system_table['file'], $n_data, $sys_file['id']);
      $rep['insert_id'] = $sys_file['id'];
    } else {
      $rep['insert_id'] = Amst::insert(Info::$system_table['file'], $n_data, ['direct' => true]);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | TEST
  | Description | 2.เช็ค Permission Setting
  | $code | code
   */
  public static function testAsaGetPath($code, $options = []) {
    $rep = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $return = ''; // PATH

    // [DEF]
    $options = Amst::checkOption($options);
    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $link = static::getPlaceHolder($placeholder_type);

    // [CODE]
    $code = strtolower(trim($code));
    if (!$code) {
      return $link;
    }

    $file_info = []; // BUILD DATA
    if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
      $glob = [
        'code' => $code,
        'path' => (static::getFolderFullPath(true)."{$code}.*"),
        'glob' => glob(static::getFolderFullPath(true)."{$code}.*")
      ];
      if (isset($glob['glob'][0])) {
        $file_name = basename($glob['glob'][0]);
        $extension = pathinfo(static::getFolderPath(true).$file_name, PATHINFO_EXTENSION);
        $update_date_time = '2021-12-31'; // [TODO] filemtime
        $file_info = [
          'code'             => $code,
          'path'             => static::getFolderPath(true).$file_name,
          'file_name'        => $file_name,
          'extension'        => $extension,
          'link'             => $link, // NOT GET LINK HERE static::_getTimingFullPath($file_name, $options),
          'insert_user_id'   => 0,
          'update_date_time' => $update_date_time
        ];
      }
    } else {
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code'          => $code
        ]
      ];
      $file_info = Amst::get(Info::$system_table['file'], '*', $where);
    }

    if (!$file_info) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      if (isset($options['file_info'])) {
        return [
          'code'           => '',
          'path'           => '',
          'file_name'      => '',
          'extension'      => '',
          'link'           => $link,
          'insert_user_id' => 0
        ];
      }
      return $link;
    }
    if (isset($options['path'])) {
      return $file_info['path'];
    }

    // [GET_FILE_URL] + [FILE_LOCATION]
    if ($public_url = Azure_Storage::getFilePublicUrl($file_info['code'].'.'.$file_info['extension'])) {
      $link = $public_url;
    }

    if (isset($options['file_info'])) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      $file_info['link'] = $link;
      return $file_info;
    }

    $return = $link;
    return $return;
  }

  /**
  | Group | TEST
  | Description | 2.testAsaGetFileinfo
  | $code | code
   */
  public static function testAsaGetFileinfo($code, $options = []) {
    $rep = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $return = ''; // PATH

    // [DEF]
    $options = Amst::checkOption($options);
    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $link = static::getPlaceHolder($placeholder_type);

    // [CODE]
    $code = strtolower(trim($code));
    if (!$code) {
      return $link;
    }

    $file_info = []; // BUILD DATA
    if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
      $glob = [
        'code' => $code,
        'path' => (static::getFolderFullPath(true)."{$code}.*"),
        'glob' => glob(static::getFolderFullPath(true)."{$code}.*")
      ];
      if (isset($glob['glob'][0])) {
        $file_name = basename($glob['glob'][0]);
        $extension = pathinfo(static::getFolderPath(true).$file_name, PATHINFO_EXTENSION);
        $update_date_time = '2021-12-31'; // [TODO] filemtime
        $file_info = [
          'code'             => $code,
          'path'             => static::getFolderPath(true).$file_name,
          'file_name'        => $file_name,
          'extension'        => $extension,
          'link'             => $link, // NOT GET LINK HERE static::_getTimingFullPath($file_name, $options),
          'insert_user_id'   => 0,
          'update_date_time' => $update_date_time
        ];
      }
    } else {
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code'          => $code
        ]
      ];
      $file_info = Amst::get(Info::$system_table['file'], '*', $where);
    }

    if (!$file_info) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      if (isset($options['file_info'])) {
        return [
          'code'           => '',
          'path'           => '',
          'file_name'      => '',
          'extension'      => '',
          'link'           => $link,
          'insert_user_id' => 0
        ];
      }
      return $link;
    }
    if (isset($options['path'])) {
      return $file_info['path'];
    }

    // [GET_FILE_URL] + [FILE_LOCATION]
    $return = Azure_Storage::isFileExists($file_info['code'].'.'.$file_info['extension']);

    return $return;
  }

  /**
  | Group | TEST
  | Description | 2.testAsaGetFile
  | $code | code
   */
  public static function testAsaGetFile($code, $options = []) {
    $rep = [
      'is_has_error' => false,
      'msg'          => 'checking..'
    ];

    $return = ''; // PATH

    // [DEF]
    $options = Amst::checkOption($options);
    $placeholder_type = Util::getOptionsValue('placeholder', $options, 'square');
    $link = static::getPlaceHolder($placeholder_type);

    // [CODE]
    $code = strtolower(trim($code));
    if (!$code) {
      return $link;
    }

    $file_info = []; // BUILD DATA
    if (defined('Config::file_read_no_db') && (Config::file_read_no_db == true) && defined('Config::file_storage_type') && (Config::file_storage_type == 'local')) {
      $glob = [
        'code' => $code,
        'path' => (static::getFolderFullPath(true)."{$code}.*"),
        'glob' => glob(static::getFolderFullPath(true)."{$code}.*")
      ];
      if (isset($glob['glob'][0])) {
        $file_name = basename($glob['glob'][0]);
        $extension = pathinfo(static::getFolderPath(true).$file_name, PATHINFO_EXTENSION);
        $update_date_time = '2021-12-31'; // [TODO] filemtime
        $file_info = [
          'code'             => $code,
          'path'             => static::getFolderPath(true).$file_name,
          'file_name'        => $file_name,
          'extension'        => $extension,
          'link'             => $link, // NOT GET LINK HERE static::_getTimingFullPath($file_name, $options),
          'insert_user_id'   => 0,
          'update_date_time' => $update_date_time
        ];
      }
    } else {
      $where = [
        'AND' => [
          'system_delete' => 0,
          'code'          => $code
        ]
      ];
      $file_info = Amst::get(Info::$system_table['file'], '*', $where);
    }

    if (!$file_info) {
      // [TODO] จะให้ย้ายไปใช้ getFileInfo
      if (isset($options['file_info'])) {
        return [
          'code'           => '',
          'path'           => '',
          'file_name'      => '',
          'extension'      => '',
          'link'           => $link,
          'insert_user_id' => 0
        ];
      }
      return $link;
    }
    if (isset($options['path'])) {
      return $file_info['path'];
    }

    // [GET_FILE_URL] + [FILE_LOCATION]
    $return = Azure_Storage::getFile($file_info['code'].'.'.$file_info['extension']);
    header("content-type: ".$file_info['file_type']);
    echo $return;
    die();
    return $return;
  }

  /**
  | Group | TEST
  | Description | 2.testTencentUpFile
  | $code | code
  | $data | [sub_app_id(sub_app_id)]
   */
  public static function testTencentUpFile($code, $file = null, $data = []) {
    return sdkTencentTest::uploadByFileInput($file, $data);
  }

  /**
  | Group | TEST
  | Description | 2.testTencentModifyMediaInfo
  | $code | code
  | $file_id | file_id
  | $data | [sub_app_id(sub_app_id), name(name), description(description)]
   */
  public static function testTencentModifyMediaInfo($code, $file_id, $data = []) {
    return sdkTencentTest::modifyMediaInfo($file_id, $data);
  }

  /**
  | Group | TEST
  | Description | 2.testTencentModifyMediaInfo
  | $code | code

   */
  public static function searchMediaRequest($code) {
    return sdkTencentTest::searchMediaRequest();
  }
}
