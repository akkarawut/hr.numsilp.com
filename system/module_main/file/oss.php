<?php

if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
  require_once ROOT_PHP.'/library/sdkOss.php';
}

class Oss {
  private static $sample_config = [
    'oss_access_key'    => '',
    'oss_access_secret' => '',
    'oss_endpoint'      => '',
    'oss_bucket_name'   => '',
    'oss_url_expires'   => 3600,
    'oss_folder'        => 'files/'
  ];

  public static function getConfig() {
    if (defined('Config::file_storage_type') && (Config::file_storage_type == 'oss')) {
      return [
        'oss_access_key'    => config::oss_access_key,
        'oss_access_secret' => config::oss_access_secret,
        'oss_endpoint'      => config::oss_endpoint,
        'oss_bucket_name'   => config::oss_bucket_name,
        'oss_url_expires'   => config::oss_url_expires,
        'oss_folder'        => config::oss_folder
      ];
    }
    return false;
  }

  public static function uploadBase64File($file_name, $base64) {
    // Create new sdkOss object
    $sdkOss = new sdkOss();
    return $sdkOss::uploadBase64File(static::getConfig(), $file_name, $base64);
  }

  public static function uploadFile($file_name, $file) {
    // Create new sdkOss object
    $sdkOss = new sdkOss();
    return $sdkOss::uploadFile(static::getConfig(), $file_name, $file);
  }

  public static function getFileUrl($file_name) {
    // Create new sdkOss object
    $sdkOss = new sdkOss();
    return $sdkOss::getFileUrl(static::getConfig(), $file_name);
  }

  public static function deleteFile($file_name) {
    // Create new sdkSpaces object
    $sdkOss = new sdkOss();
    return $sdkOss::deleteFile(static::getConfig(), $file_name);
  }

  // public static function getFileContent($file_name) {
  //   $return = false;

  //   try {
  //     $ossClient = new OssClient(static::$accessKeyId, static::$accessKeySecret, static::$endpoint);
  //     $return = $ossClient->getObject(static::$bucket_name, $file_name, []);
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
