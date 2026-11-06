<?php
class Image {
  public static function resize($code, $file, $extension, $new_width) {
    // CHECK AND CREATE temp DIR AND temp.txt
    static::createTemp();

    // DEFINE SAVE PATH AND FIND HEIGHT
    $save_path  = File::getFolderFullPath(true).'temp/'.'file'.'.'.$extension;
    $image_size = getimagesize($file['tmp_name']);
    $new_height = intval($new_width * ($image_size[1] / $image_size[0]));
    $new_width  = intval($new_width);
    $new_height = intval($new_height);

    // GIF
    if ($extension == 'gif') {
      require_once "../library/gifresizer.php";

      // DEFINE PATH
      $oripath   = '../resource/file/temp/temp.gif';
      $save_path = '../resource/file/temp/'.$_FILES['params']['name'][1];

      // UPLOAD GIF BEFORE RESIZE
      if (move_uploaded_file($file['tmp_name'], $oripath)) {
        chmod($oripath, 0777);
      } else {
        return "Error saving file";
      }

      // RESIZE
      $gr           = new gifresizer;
      $gr->temp_dir = "../module_main/file/frames";
      $gr->resize($oripath, $save_path, $new_width, $new_height);
      chmod($save_path, 0777);

      // DELETE NOT RESIZE GIF
      $delete = unlink($oripath);
      if (!$delete) {
        return "Error deleting file.";
      }

      // PNG JPG AND JPEG
    } else {
      $img     = imagecreatefromstring(file_get_contents($file['tmp_name']));
      $img_new = imagecreatetruecolor($new_width, $new_height);
      imagecopyresampled($img_new, $img, 0, 0, 0, 0, $new_width, $new_height, $image_size[0], $image_size[1]);

      if ('PNG' == $extension || 'png' == $extension) {
        imagepng($img_new, $save_path);
      } else if ('JPG' == $extension || 'jpg' == $extension) {
        imagejpeg($img_new, $save_path);
      } else if ('JPEG' == $extension || 'jpeg' == $extension) {
        Imagejpeg($img_new, $save_path);
      }
    }

    // SET TEXT TO resized
    $f   = fopen("../resource/file/temp/temp.txt", "a") || die("Unable to open file!");
    $txt = 'resized';
    fwrite($f, $txt);
    fclose($f);

    // CREATE CURL
    $url  = 'http://68.183.234.153:8250/system/api/call.php';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, [
      'api_key'   => '0aa533073e6d38ed1190174a577a7f0b',
      'class'     => 'File',
      'function'  => 'add',
      'params[0]' => $code,
      'params[1]' => new \CURLFile($save_path, null, basename($save_path))
    ]);
    $response  = curl_exec($curl);
    $file_data = json_decode($response, true);

    // DELETE RESIZE IMAGE AND TEMP
    unlink($save_path);
    unlink('../resource/file/temp/temp.txt');
    rmdir('../resource/file/temp/');

    return $file_data;
  }

  public static function createTemp() {
    chmod('../resource/file/', 0777);

    // MAKE temp DIR
    $dir = '../resource/file/temp/';
    if (!is_dir($dir)) {
      mkdir($dir);
      chmod($dir, 0777);
    }

    // MAKE temp.txt
    $f = '../resource/file/temp/temp.txt';
    if (!file_exists($f)) {
      $fp = fopen($f, 'w');
      fclose($fp);
      chmod($f, 0777);
    }
  }
}
