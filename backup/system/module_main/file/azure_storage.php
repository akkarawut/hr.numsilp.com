<?php

class Azure_Storage {
  private static $_version      = '2019-12-12'; // https://docs.microsoft.com/en-us/rest/api/storageservices/versioning-for-the-azure-storage-services
  private static $sample_config = [
    'asa_account_name'   => '',
    'asa_access_key'     => '',
    'asa_container_name' => ''
  ];

  public static function getConfig() {
    if (defined('Config::asa_account_name') && (Config::asa_account_name)) {
      return [
        'asa_account_name'   => config::asa_account_name,
        'asa_access_key'     => config::asa_access_key,
        'asa_container_name' => config::asa_container_name
      ];
    }
    return false;
  }

  public static function uploadBase64File($file_name, $base64) {
    $rep = ['update_result' => false];

    return Amst::repSuccess($rep);
  }

  public static function uploadFile($file_name, $file) {
    $rep = ['update_result' => false];

    $config = static::getConfig();
    $blobName = $file_name;

    $storageAccount = $config['asa_account_name'];
    $containerName = $config['asa_container_name'];
    $accesskey = $config['asa_access_key'];
    $destinationURL = "https://{$storageAccount}.blob.core.windows.net/{$containerName}/{$blobName}";
    $currentDate = gmdate("D, d M Y H:i:s T", time());
    $handle = fopen($file['tmp_name'], "r");
    $fileLen = filesize($file['tmp_name']);

    $headerResource = "x-ms-blob-cache-control:max-age=3600\nx-ms-blob-type:BlockBlob\nx-ms-date:$currentDate\nx-ms-version:2015-12-11";
    $urlResource = "/$storageAccount/$containerName/$blobName";

    $arraysign = [];
    $arraysign[] = 'PUT'; /*HTTP Verb*/
    $arraysign[] = ''; /*Content-Encoding*/
    $arraysign[] = ''; /*Content-Language*/
    $arraysign[] = $fileLen; /*Content-Length (include value when zero)*/
    $arraysign[] = ''; /*Content-MD5*/
    $arraysign[] = 'image/png'; /*Content-Type*/
    $arraysign[] = ''; /*Date*/
    $arraysign[] = ''; /*If-Modified-Since */
    $arraysign[] = ''; /*If-Match*/
    $arraysign[] = ''; /*If-None-Match*/
    $arraysign[] = ''; /*If-Unmodified-Since*/
    $arraysign[] = ''; /*Range*/
    $arraysign[] = $headerResource; /*CanonicalizedHeaders*/
    $arraysign[] = $urlResource; /*CanonicalizedResource*/

    $str2sign = implode("\n", $arraysign);

    $sig = base64_encode(hash_hmac('sha256', urldecode(utf8_encode($str2sign)), base64_decode($accesskey), true));
    $authHeader = "SharedKey $storageAccount:$sig";

    $headers = [
      'Authorization: '.$authHeader,
      'x-ms-blob-cache-control: max-age=3600',
      'x-ms-blob-type: BlockBlob',
      'x-ms-date: '.$currentDate,
      'x-ms-version: 2015-12-11',
      'Content-Type: image/png',
      'Content-Length: '.$fileLen
    ];

    $ch = curl_init($destinationURL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_INFILE, $handle);
    curl_setopt($ch, CURLOPT_INFILESIZE, $fileLen);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    $result = curl_exec($ch);

    // $rep['result_xml'] = ($result);
    if ($result) {
      $xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
      $json = json_encode($xml);
      $result_array = json_decode($json, true);
      $rep['result_array'] = $result_array;
      if (isset($result_array['Message'])) {
        return Amst::repError('E0000', $result_array['Message']);
      }
    }
    $rep['curl_error'] = curl_error($ch);

    curl_close($ch);
    return Amst::repSuccess($rep);
  }

  private static function __getFileInfo($file_name) {
    $return = null;

    die("HEAD not working = timeout");

    $config = static::getConfig();
    $verb = 'HEAD';
    $date = gmdate('D, d M Y H:i:s \G\M\T');
    $storageAccount = $config['asa_account_name'];
    $containerName = $config['asa_container_name'];
    $accesskey = $config['asa_access_key'];
    $blobName = $file_name;
    $stringtosign = "{$verb}\n\n\n\n\n\n\n\n\n\n\n\nx-ms-date:".$date."\nx-ms-version:".static::$_version."\n/".$storageAccount."/".$containerName."/".$blobName;
    $signature = 'SharedKey'.' '.$storageAccount.':'.base64_encode(hash_hmac('sha256', $stringtosign, base64_decode($accesskey), true));

    $header = [
      "x-ms-date: ".$date,
      "x-ms-version: ".static::$_version,
      "Authorization: ".$signature
    ];
    // $url = "https://{$storageAccount}.blob.core.windows.net/{$containerName}?comp=list";
    $url = "https://{$storageAccount}.blob.core.windows.net/{$containerName}/{$blobName}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $return = curl_exec($ch);

    // header("content-type: image/jpg");
    // echo $return;

    if (curl_errno($ch)) {
      // throw new Exception(curl_error($ch));
      return Amst::repError('E0000', 'ERROR');
    }
    curl_close($ch);

    return $return;
  }

  public static function isFileExists($file_name) {
    $return = false;

    $config = static::getConfig();
    $verb = 'GET';
    $date = gmdate('D, d M Y H:i:s \G\M\T');
    $storageAccount = $config['asa_account_name'];
    $containerName = $config['asa_container_name'];
    $accesskey = $config['asa_access_key'];
    $blobName = $file_name;
    $stringtosign = "{$verb}\n\n\n\n\n\n\n\n\n\n\n\nx-ms-date:".$date."\nx-ms-version:".static::$_version."\n/".$storageAccount."/".$containerName."/".$blobName;
    $signature = 'SharedKey'.' '.$storageAccount.':'.base64_encode(hash_hmac('sha256', $stringtosign, base64_decode($accesskey), true));

    $header = [
      "x-ms-date: ".$date,
      "x-ms-version: ".static::$_version,
      "Authorization: ".$signature
    ];
    $url = "https://{$storageAccount}.blob.core.windows.net/{$containerName}/{$blobName}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_exec($ch);

    if ('200' == curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
      $return = true;
    }
    curl_close($ch);

    return $return;
  }

  public static function getFile($file_name) {
    $return = null;

    $config = static::getConfig();
    $verb = 'GET';
    $date = gmdate('D, d M Y H:i:s \G\M\T');
    $storageAccount = $config['asa_account_name'];
    $containerName = $config['asa_container_name'];
    $accesskey = $config['asa_access_key'];
    $blobName = $file_name;
    $stringtosign = "{$verb}\n\n\n\n\n\n\n\n\n\n\n\nx-ms-date:".$date."\nx-ms-version:".static::$_version."\n/".$storageAccount."/".$containerName."/".$blobName;
    $sig = base64_encode(hash_hmac('sha256', $stringtosign, base64_decode($accesskey), true));
    $signature = 'SharedKey'.' '.$storageAccount.':'.$sig;

    $header = [
      "x-ms-date: ".$date,
      "x-ms-version: ".static::$_version,
      "Authorization: ".$signature
    ];
    $url = "https://{$storageAccount}.blob.core.windows.net/{$containerName}/{$blobName}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $return = curl_exec($ch);

    if (curl_errno($ch)) {
      // throw new Exception(curl_error($ch));
      return null;
    }
    curl_close($ch);

    return $return;
  }

  public static function getFileUrl($file_name) {
    $return = '';
    // https://hkstpfbsuatsa.blob.core.windows.net/webfiles/Chrysanthemum.jpg
    if (static::isFileExists($file_name)) {
      $config = static::getConfig();
      $asa_account_name = $config['asa_account_name'];
      $asa_container_name = $config['asa_container_name'];
      $return = "https://{$asa_account_name}.blob.core.windows.net/{$asa_container_name}/{$file_name}";
    }
    return $return;
  }

  private static function getBlobUrl($accountName, $container, $blob, $resourceType, $permissions, $expiry, $_signature) {
    /* Create the signed query part */
    $_parts = [];
    $_parts[] = (!empty($expiry)) ? 'se='.urlencode($expiry) : '';
    $_parts[] = 'sr='.$resourceType;
    $_parts[] = (!empty($permissions)) ? 'sp='.$permissions : '';
    $_parts[] = 'sig='.urlencode($_signature);
    $_parts[] = 'sv=2014-02-14';

    /* Create the signed blob URL */
    $_url = 'https://'
    .$accountName.'.blob.core.windows.net/'
    .$container.'/'
    .$blob.'?'
    .implode('&', $_parts);

    return $_url;
  }

  private static function getSASForBlob($accountName, $container, $blob, $resourceType, $permissions, $expiry, $key) {
    /* Create the signature */
    $_arraysign = [];
    $_arraysign[] = $permissions;
    $_arraysign[] = '';
    $_arraysign[] = $expiry;
    $_arraysign[] = '/'.$accountName.'/'.$container.'/'.$blob;
    $_arraysign[] = '';
    $_arraysign[] = "2014-02-14"; //the API version is now required
    $_arraysign[] = '';
    $_arraysign[] = '';
    $_arraysign[] = '';
    $_arraysign[] = '';
    $_arraysign[] = '';

    $_str2sign = implode("\n", $_arraysign);

    return base64_encode(
      hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
    );
  }

  public static function getFilePublicUrl($file_name) {
    $return = '';
    if (static::isFileExists($file_name)) {
      $config = static::getConfig();
      $asa_account_name = $config['asa_account_name'];
      $asa_container_name = $config['asa_container_name'];
      $asa_access_key = $config['asa_access_key'];
      $expiry = gmdate("Y-m-d\TH:i:s\Z", time() + 3600); // 1 hour
      $sig = static::getSASForBlob($asa_account_name, $asa_container_name, $file_name, "b", "r", $expiry, $asa_access_key);
      $return = static::getBlobUrl($asa_account_name, $asa_container_name, $file_name, "b", "r", $expiry, $sig);
    } 
    return $return;
  }

  public static function deleteFile($file_name) {
    $rep = ['update_result' => false];
    // 
    return Amst::repSuccess($rep);
  }
}
