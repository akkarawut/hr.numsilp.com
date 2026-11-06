<?php
class User_Line {
  /**
  | Group | USER Line
  | Description | 1.ดึงชื่อผู้ใช้ Line
  | $access_token | ได้มาจาก line developer หน้า messaging API ด้านล่าง สุดชื่อว่า Channel access token (long-lived) ให้กด issue ก่อน
  | $line_user_id | user id ของ line ไม่ใช่ line id
   */
  public static function getProfile($access_token, $line_user_id) {
    $return = null;

    // Validate
    if (!$access_token) {
      return Amst::repError('E0001', 'Access Token');
    }
    if (!$line_user_id) {
      return Amst::repError('E0001', 'Line User ID');
    }

    // Access Token
    $url = 'https://api.line.me/v2/bot/profile/'.$line_user_id;
    $headers = ['Authorization: Bearer '.$access_token];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);

    $return = json_decode($result, true);

    return $return;
  }

  /**
  | Group | USER Line
  | Description | Link user เข้า กับ line user id
  | $user_id | user_id
  | $line_user_id | user id ของ line ไม่ใช่ line id
   */
  public static function linkUser($user_id, $line_user_id) {
    $social = 'line';

    $user = Amst::getByID('sys_user', '*', $user_id);
    if (!isset($user['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }
    if (!$line_user_id) {
      return Amst::repError('E0000', 'Line user id not found.');
    }

    //check if social is already linked
    $where = [
      'AND' => [
        'user_id[!]'    => $user_id,
        'social'        => 'line',
        'social_id'     => $line_user_id,
        'system_delete' => 0
      ]
    ];
    $check = Amst::get('sys_user_social', '*', $where);
    if (isset($check['id'])) {
      return Amst::repError('E0000', 'Line already linked to another user.');
    }

    $where = [
      'AND' => [
        'user_id'       => $user_id,
        'social'        => 'line',
        'system_delete' => 0
      ]
    ];
    $linked = Amst::get('sys_user_social', '*', $where);
    if (isset($linked['id'])) {
      if ($linked['social_id']) {
        return Amst::repError('E0000', 'Line already linked.');
      } else {
        $u_data = [
          'social_id' => $line_user_id
        ];
        $api = Amst::updateByID('sys_user_social', $u_data, $linked['id']);
        return $api;
      }
    }
    $i_data = [
      'user_id'   => $user_id,
      'social'    => 'line',
      'social_id' => $line_user_id
    ];
    $api = Amst::insert('sys_user_social', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | USER Line
  | Description | 3.ส่งข้อความให้คนที่กำหนด
  | $access_token | ได้มาจาก line developer หน้า messaging API ด้านล่าง สุดชื่อว่า Channel access token (long-lived) ให้กด issue ก่อน
  | $line_user_ids | user id ของ line ไม่ใช่ line id
  | $messages | ข้อความที่จะส่ง
   */
  public static function notify($access_token, $line_user_ids, $messages = []) {
    $rep = [
      'message' => 'ERROR'
    ];

    // Validate
    if (!$access_token) {
      return Amst::repError('E0001', 'Access Token');
    }
    if (!$line_user_ids) {
      return Amst::repError('E0001', 'Line User ID');
    }
    if (!$messages) {
      return Amst::repError('E0001', 'Message');
    }

    if (!is_array($line_user_ids)) {
      $line_user_ids = [$line_user_ids];
    }

    if (!is_array($messages)) {
      $messages = [$messages];
    }

    $line_messages = [];

    foreach ($messages as $m_key => $message) {
      $line_messages[] = [
        'type' => 'text',
        'text' => $message
      ];
    }
    $post = json_encode([
      'to'       => $line_user_ids,
      'messages' => $line_messages
    ]);

    $url = 'https://api.line.me/v2/bot/message/multicast';
    $headers = ['Content-Type: application/json', 'Authorization: Bearer '.$access_token];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    $rep['message'] = $result;

    return Amst::repSuccess($rep);
  }

  /**
  | Group | USER Line
  | Description | 3.ส่งข้อความให้กับคนที่ติดตาม Line
  | $access_token | ได้มาจาก line developer หน้า messaging API ด้านล่าง สุดชื่อว่า Channel access token (long-lived) ให้กด issue ก่อน
  | $messages | ข้อความที่จะส่ง
   */
  public static function notifyAll($access_token, $messages = []) {
    $rep = [
      'message' => 'ERROR'
    ];

    // Validate
    if (!$access_token) {
      return Amst::repError('E0001', 'Access Token');
    }
    if (!$messages) {
      return Amst::repError('E0001', 'Message');
    }

    if (!is_array($messages)) {
      $messages = [$messages];
    }

    $line_messages = [];

    foreach ($messages as $m_key => $message) {
      $line_messages[] = [
        'type' => 'text',
        'text' => $message
      ];
    }
    $post = json_encode([
      'messages' => $line_messages
    ]);

    $url = 'https://api.line.me/v2/bot/message/broadcast';
    $headers = ['Content-Type: application/json', 'Authorization: Bearer '.$access_token];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    $rep['message'] = $result;

    return Amst::repSuccess($rep);
  }
}