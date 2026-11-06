<?php

if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
  require_once ROOT_PHP.'/library/sdkSpaces.php';
}

class Dspace {
  public static function getConfig() {
    if (defined('Config::file_storage_type') && (Config::file_storage_type == 'space')) {
      return [
        'space_bucket_name'   => config::space_bucket_name,
        'space_access_key'    => config::space_access_key,
        'space_access_secret' => config::space_access_secret,
        'space_endpoint'      => config::space_endpoint,
        'space_region'        => config::space_region,
        'space_url_expires'   => config::space_url_expires,
        'space_root_folder'   => config::space_root_folder,
        'space_file_privacy'  => config::space_file_privacy
      ];
    }
  }

  public static function uploadBase64File($file_name, $base64) {
    // Create new sdkSpaces object
    $sdkSpaces = new sdkSpaces(static::getConfig());
    return $sdkSpaces->uploadFileBase64($file_name, $base64);
  }

  public static function uploadFile($file_name, $file) {
    // Create new sdkSpaces object
    $sdkSpaces = new sdkSpaces(static::getConfig());
    return $sdkSpaces->uploadFile($file_name, $file);
  }

  public static function getFileUrl($file_name) {
    // Create new sdkSpaces object
    $sdkSpaces = new sdkSpaces(static::getConfig());
    return $sdkSpaces->getFileUrl($file_name);
  }

  public static function deleteFile($file_name) {
    // Create new sdkSpaces object
    $sdkSpaces = new sdkSpaces(static::getConfig());
    return $sdkSpaces->deleteFile($file_name);
  }

  // public static function getFileContent($file_name) {
  //   $return = false;

  //   try {
  //     $spaceClient = new spaceClient(static::$accessKeyId, static::$accessKeySecret, static::$endpoint);
  //     $return = $spaceClient->getObject(static::$bucket_name, $file_name, []);
  //   } catch (Exception $e) {
  //     return false;
  //   }

  //   return $return;
  // }

  // public static function downloadFile($file_name) {
  //   $rep = [];

  //   $base64 = static::getFileContent($file_name);
  //   if ($base64) {
  //     header('Content-Disposition: attachment;filename="'.$file_name.'"');
  //     // header('Content-Type: application/force-download');
  //     echo ($base64);
  //     exit;
  //   }

  //   return Amst::repSuccess($rep);
  // }
}
