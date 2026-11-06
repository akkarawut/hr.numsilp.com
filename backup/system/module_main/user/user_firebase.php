<?php
class User_Firebase
{
  private static function makeBody($title, $msg)
  {
    $msg = [
      'title'              => $title,
      'subtitle'           => '',
      'body'               => $msg,
      'tickerText'         => '',
      'vibrate'            => 1,
      'sound'              => 'default',
      'android_channel_id' => 'click_notification_id',
      'largeIcon'          => 'large_icon',
      'smallIcon'          => 'small_icon'
    ];

    return $msg;
  }

  private static function deleteFirebase($firebase_tokens)
  {
    !is_array($firebase_tokens) ? $firebase_tokens = [$firebase_tokens] : null;

    foreach ($firebase_tokens as $firebase_token) {
      $data = [
        'id',
        'firebase_tokens'
      ];
      $where = [
        'firebase_tokens[~]' => $firebase_token
      ];
      $sys_firebase_tokens = DB::get('sys_user', $data, $where);

      $id = $sys_firebase_tokens['id'];
      $sys_firebase_tokens_array = explode(', ', $sys_firebase_tokens['firebase_tokens']);
      $firebase_token = [$firebase_token];
      $del = array_diff($sys_firebase_tokens_array, $firebase_tokens);

      $data = [
        'firebase_tokens' => implode(', ', $del)
      ];
      $where = [
        'id' => $id
      ];
      DB::update('sys_user', $data, $where);
    }

    return $del;
  }

  /**
  | Group | Operation
  | Description | addFireBaseToken
  | $user_id | ไอดีผู้ใช้
  | $token | โค้ดประจำเครื่อง/ต่อการลงโปรแกรมมือถือ
   */
  public static function addFirebaseToken($user_id, $token = [])
  {
    if (!$token) {
      return false;
    }

    if (!is_array($token)) {
      $token = [$token];
    }

    $token_filter = str_replace(['_', '!'], ['\_', '\!'], $token);
    $data = [
      'firebase_tokens',
      'id'
    ];
    $where = [
      'firebase_tokens[~]' => $token_filter,
      'id[!]' => $user_id
    ];
    $sys_user = DB::select('sys_user', $data, $where);

    foreach ($sys_user as $user_data) {
      $firebase_tokens = explode(", ", $user_data['firebase_tokens']);
      $temp = [];
      foreach ($firebase_tokens as $firebase_token) {
        if (!in_array($firebase_token, $token)) {
          $temp[] = $firebase_token;
        }
      }
      $data = [
        'firebase_tokens' => implode(', ', $temp)
      ];
      $where = [
        'id' => $user_data['id']
      ];
      DB::update('sys_user', $data, $where);
    }

    $where = [
      "id" => $user_id
    ];
    $user_tokens = explode(', ', DB::get('sys_user', 'firebase_tokens', $where));
    $new_user_tokens = array_unique(array_filter(array_merge($user_tokens, $token)));
    $data = [
      'firebase_tokens' => implode(', ', $new_user_tokens)
    ];
    $where = [
      "id" => $user_id
    ];
    DB::update('sys_user', $data, $where);

    return true;
  }

  /**
  | Group | Operation
  | Description | addFireBaseToken
  | $firebaseAccessKey | firebaseAccessKey
  | $firebase_tokens | โค้ดประจำเครื่อง/ต่อการลงโปรแกรมมือถือ
  | $input_data | [title,msg,data,log]
   */
  public static function notifyByFirebase($firebaseAccessKey, $firebase_tokens, $input_data)
  {
    $rep = [];

    $title = (isset($input_data['title'])) ? $input_data['title'] : $check_input = false;
    $msg = (isset($input_data['msg'])) ? $input_data['msg'] : $check_input = false;
    $data = (isset($input_data['data'])) ? $input_data['data'] : [];
    $log = (isset($input_data['log'])) ? $input_data['log'] : false;
    $body  = static::makeBody($title, $msg);

    if ($check_input === false) {
      return Amst::repError('input_data => title or msg is null');
    }

    if (!is_array($firebase_tokens)) {
      $firebase_tokens = [$firebase_tokens];
    }

    if ($firebaseAccessKey === null) {
      return Amst::repError('firebase_tokens is null');
    }

    $token_filter = str_replace(['_', '!'], ['\_', '\!'], $firebase_tokens);
    $where = [
      'firebase_tokens[~]' => $token_filter
    ];
    $sys_firebase_token = DB::select('sys_user', 'firebase_tokens', $where);

    $temp = [];
    foreach ($sys_firebase_token as $sys_tokens) {
      $sys_token_array = explode(', ', $sys_tokens);
      foreach ($sys_token_array as $token) {
        $temp[] = $token;
      }
    }
    $firebase_tokens_found = $temp;

    $fields = [
      'registration_ids' => $firebase_tokens_found,
      'notification'     => $body,
    ];

    if ($data) {
      $fields['data'] = $data;
    }

    if ($log) {
      Log::add(0, 'Notification data ' . json_encode($fields));
    }

    $headers = [
      'Authorization: key=' . $firebaseAccessKey,
      'Content-Type: application/json'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $response = curl_exec($ch);
    curl_close($ch);
    $rep['response'] = $response;
    $reps = json_decode($response);

    if ($reps->failure !== 0) {
      $drop = [];
      $i = 0;
      foreach ($reps->results as $str) {
        if ($str->error) {
          $drop[] = $firebase_tokens_found[$i];
        } else {
          $drop[] = $i;
        }
        $i++;
      }
      $del = array_values(array_intersect($drop, $firebase_tokens_found));
      self::deleteFirebase($del);
    }

    return Amst::repSuccess($rep);
  }

  /**
  | Group | Operation
  | Description | notifyAll
  | $firebaseAccessKey | FirebaseAccessKey
  | $title | Title
  | $msg | Massage
  | $data | Json object
   */
  public static function notifyAll($firebaseAccessKey, $title, $msg, $data = [])
  {
    $input_data = [
      'title' => $title,
      'msg' => $msg,
      'data' => $data
    ];

    $where = [
      'firebase_tokens[!]' => ''
    ];
    $sys_firebase_token = DB::select('sys_user', 'firebase_tokens', $where);

    $temp = [];
    foreach ($sys_firebase_token as $sys_tokens) {
      $sys_tokens_array = explode(', ', $sys_tokens);
      foreach ($sys_tokens_array as $token) {
        $temp[] = $token;
      }
    }
    $firebase_tokens = $temp;

    return static::notifyByFirebase($firebaseAccessKey, $firebase_tokens, $input_data);
  }
}
