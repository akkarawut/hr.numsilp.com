<?php
class SMS {
  public static function sendSMSTwist($username, $password, $phone, $message) {
    $rep = [];
    // DOCUMENT
    // https://www.911itwist.com/article-inner/device/6/%E0%B8%A3%E0%B8%AD%E0%B8%87%E0%B8%A3%E0%B8%B1%E0%B8%9A%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%8A%E0%B8%B7%E0%B9%88%E0%B8%AD%E0%B8%A1%E0%B8%95%E0%B9%88%E0%B8%AD%20API...html

    // 3.การวิเคราะห์ค่า error
    // Code  Response Message  คำอธิบาย
    // 0
    // Success
    // การดำเนินการสำเร็จ
    // -102
    // Authenticate fail
    // คือกรณีของการใส่ user-password ผิด หรือเขียนโปรแกรมส่วนของ user-password มาผิดรูปแบบ
    // -106
    // Invalid data entry
    // เป็นกรณีที่เกิดจากข้อมูลที่เขียนโปรแกรมเข้ามา ไม่ถูกรูปแบบ
    // -107
    // Credit not enough
    // จำนวน SMS เหลือไม่พอส่ง
    // -108
    // Invalid sender name
    // คือ การระบุ Sender Name เข้ามาไม่ถูกต้อง
    // โดยต้องระบุเข้ามาให้ตรงกับที่สร้างไว้ใน user ใช้งาน

    // Target URL
    //$sendTo = 'http://sms.911itwist.com/tunnel/servlet/sendSMS.do';

    // Authenticate Header
    $userName = $username;
    $passWord = $password;

    $txnID  = date('YmdHis') . rand(3, 3);
    $dest   = $phone;
    $sender = 'FAHFOOD'; //เป็นคำว่า SMS ไม่ได้

    /*$xmlEncoding = 'US-ASCII';
    $msgType = 'E';
    $msData = 'Hello SMS';*/
    $xmlEncoding = 'UTF-8';
    $msgType     = 'T';
    $msData      = $message;

    $respData  = '';
    $respError = '';

    $authenHeader = base64_encode($userName . ':' . $passWord);

    // Post data
    $postData = "<?xml version=\"1.0\" encoding=\"$xmlEncoding\"<transaction><id>$txnID</id><msisdn>$dest</msisdn><msgtype>$msgType</msgtype><msdata>$msData</msdata><sender>$sender</sender></transaction>";

    // Handle Warning&Exception pls
    $fp = fsockopen("sms.911itwist.com", 80, $errno, $errstr, 30);
    if (!$fp) {
      $respError = "$errstr ($errno)<br />\n";
    } else {
      $reqPkg = "POST /tunnel/servlet/sendSMS.do HTTP/1.1\r\n";
      $reqPkg .= "Host: sms.911itwist.com\r\n";
      $reqPkg .= "Content-type: text/xml; charset=UTF-8\r\n";
      $reqPkg .= "Authorization: $authenHeader\r\n";
      $reqPkg .= "Connection: close\r\n";
      $reqPkg .= "Content-Length: " . strlen($postData) . "\r\n\r\n";
      $reqPkg .= "$postData";

      //echo "$reqPkg";
      fwrite($fp, $reqPkg);
      while (!feof($fp)) {
        $respData .= fgets($fp, 128);
        //echo "$respData";
      }
      fclose($fp);
    }

    $rep = [
      'respError' => $respError,
    ];
    if (!is_null($respData)) {
      $xml_pos       = strpos($respData, '<transaction>');
      $rep['header'] = trim(substr($respData, 0, $xml_pos));
      if ($xml_pos >= 0) {
        //$rep['html'] = substr($respData, $xml_pos);
        $xml           = new SimpleXMLElement(substr($respData, $xml_pos));
        $rep['id']     = isset($xml->id[0]) ? "{$xml->id[0]}" : '';
        $rep['msgid']  = isset($xml->msgid[0]) ? "{$xml->msgid[0]}" : '';
        $rep['status'] = isset($xml->status[0]) ? "{$xml->status[0]}" : '';
        $rep['desc']   = isset($xml->desc[0]) ? "{$xml->desc[0]}" : '';
      }
    }

    return Amst::repSuccess($rep);
  }

  public static function sendSMSThaiBulk($username, $password, $msisdn, $message, $sender = "SMS", $ScheduledDelivery = "", $force = "standard") {
    $url = "http://www.thaibulksms.com/sms_api.php";

    // $username = Config::sms_thaibulk_username;
    // $password = Config::sms_thaibulk_password;

    if (extension_loaded('curl')) {
      $data = [
        'username'          => $username,
        'password'          => $password,
        'msisdn'            => $msisdn,
        'message'           => $message,
        'sender'            => $sender,
        'ScheduledDelivery' => $ScheduledDelivery,
        'force'             => $force,
      ];
      $data_string = http_build_query($data);

      $agent = "ThaiBulkSMS API PHP Client";
      $ch    = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
      $xml_result = curl_exec($ch);
      $code       = curl_getinfo($ch);
      curl_close($ch);

      if ($code['http_code'] == 200) {
        if (function_exists('simplexml_load_string')) {
          $sms   = new SimpleXMLElement($xml_result);
          $count = count($sms->QUEUE);
          if ($count > 0) {
            $count_pass  = 0;
            $count_fail  = 0;
            $used_credit = 0;
            for ($i = 0; $i < $count; $i++) {
              if ($sms->QUEUE[$i]->Status) {
                $count_pass++;
                $used_credit += $sms->QUEUE[$i]->UsedCredit;
              } else {
                $count_fail++;
              }
            }
            if ($count_pass > 0) {
              $msg_string = "สามารถส่งออกได้จำนวน $count_pass หมายเลข, ใช้เครดิตทั้งหมด $used_credit เครดิต";
            }
            if ($count_fail > 0) {
              $msg_string = "ไม่สามารถส่งออกได้จำนวน $count_fail หมายเลข";
            }
          } else {
            $msg_string = "เกิดข้อผิดพลาดในการทำงาน, (" . $sms->Detail . ")";
          }
        } else if (function_exists('xml_parse')) {
          $xml   = sms::xml2array($xml_result);
          $count = count($xml['SMS']['QUEUE']);
          if ($count > 0) {
            $count_pass  = 0;
            $count_fail  = 0;
            $used_credit = 0;
            for ($i = 0; $i < $count; $i++) {
              if ($xml['SMS']['QUEUE'][$i]['Status']) {
                $count_pass++;
                $used_credit +=
                  $xml['SMS']['QUEUE'][$i]['UsedCredit'];
              } else {
                $count_fail++;
              }
            }
            if ($count_pass > 0) {
              $msg_string = "สามารถส่งออกได้จำนวน $count_pass หมายเลข, ใช้เครดิตทั้งหมด $used_credit เครดิต";
            }
            if ($count_fail > 0) {
              $msg_string = "ไม่สามารถส่งออกได้จำนวน $count_fail หมายเลข";
            }
          } else {
            $msg_string = "เกิดข้อผิดพลาดในการทำงาน, (" . $xml['SMS']['Detail'] . ")";
          }
        } else {
          $msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br /> ระบบไม่รองรับฟังก์ชั่น XML";
        }
      } else {
        //$http_codes = parse_ini_file("http_code.ini");
        //$msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br />" . $code['http_code'] . " " . $http_codes[$code['http_code']];
        $msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br />" . $code['http_code'];
      }
    } else {
      if (function_exists('fsockopen')) {
        $msg_string = sending_fsock($username, $password, $msisdn, $message, $sender, $ScheduledDelivery, $force);
      } else {
        $msg_string = "cURL OR fsockopen is not enabled";
      }
    }
    return $msg_string;
  }

  public static function sendSMSBulkSMS($username, $password, $message) {
    $url = "https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30";

    // $username = Config::sms_thaibulk_username;
    // $password = Config::sms_thaibulk_password;
    // example message
    // $messages = [
    //   ['to' => '+'.$phone_number, 'body' => 'Your code is : '.$otp]
    // ];
    // $message = json_encode($messages);

    $ch      = curl_init();
    $headers = [
      'Content-Type:application/json',
      'Authorization:Basic ' . base64_encode("$username:$password"),
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    // Allow cUrl functions 20 seconds to execute
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    // Wait 10 seconds while trying to connect
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $output                    = [];
    $output['server_response'] = curl_exec($ch);
    $curl_info                 = curl_getinfo($ch);
    $output['http_status']     = $curl_info['http_code'];
    $output['error']           = curl_error($ch);
    curl_close($ch);

    return $output;
  }
  public static function sendSMSMkt($message = '', $tel = false, $sender_name = '') {
    if (!$tel) {
      return Amst::repError('tel require');
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL            => 'https://portal-otp.smsmkt.com/api/send-message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING       => '',
      CURLOPT_MAXREDIRS      => 10,
      CURLOPT_TIMEOUT        => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST  => 'POST',
      CURLOPT_HTTPHEADER     => array(
        "Content-Type: application/json",
        "api_key:" . Config::sms_smsmkt_api_key,
        "secret_key:" . Config::sms_smsmkt_api_secret,
      ),
      CURLOPT_POSTFIELDS     => json_encode(array(
        "message" => $message,
        "phone"   => $tel,
        "sender"  => $sender_name,
      )),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public static function getSMSMktCredit() {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL            => 'https://portal-otp.smsmkt.com/api/get-credit',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING       => '',
      CURLOPT_MAXREDIRS      => 10,
      CURLOPT_TIMEOUT        => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST  => 'GET',
      CURLOPT_HTTPHEADER     => [
        "Content-Type: application/json",
        "api_key:" . Config::sms_smsmkt_api_key,
        "secret_key:" . Config::sms_smsmkt_api_secret,
      ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
}
