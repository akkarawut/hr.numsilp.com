<?php
class Firebase {
  /**
  | Group | Firebase
  | Description | register
   */
  public static function register($user_id, $firebase_registration_id) {
    $rep = [
      'update_result' => false
    ];

    // $data = ['firebase_registration_id' => $firebase_registration_id];
    // return Dev::update($user_id, $data);

    return Amst::repError('E0000', 'System not implement');
  }

  /**
  | Group | Firebase
  | Description | notifiables
   */
  public static function notifiables() {
    $notifiables = [];

    $where = [
      'AND' => [
        'status'                      => 'Active',
        'firebase_registration_id[!]' => ''
      ]
    ];
    $users = []; // [TODO] recheck what to do Dev::select($where);
    foreach ($users as $user) {
      if ($user['firebase_registration_id'] != '') {
        array_push($notifiables, $user['firebase_registration_id']);
      }
    }

    return $notifiables;
  }

  /**
  | Group | Firebase
  | Description | makeBody
   */
  public static function makeBody($title, $msg) {
    $msg = [
      'title'      => $title,
      'subtitle'   => '',
      'body'       => $msg,
      'tickerText' => '',
      'vibrate'    => 1,
      'sound'      => 'default',
      'largeIcon'  => 'large_icon',
      'smallIcon'  => 'small_icon'
    ];
    return $msg;
  }

  /**
  | Group | Firebase
  | Description | notifyAll
   */
  public static function notifyAll($title, $msg, $log = false) {
    $notifiables = static::notifiables();

    if ($log) {
      Log::add(0, 'The notification will be sent to '.count($notifiables).' users: '.json_encode($notifiables).'.');
    }

    $notification_result = static::notify($notifiables, $title, $msg);

    if ($log) {
      Log::add(0, json_encode($notification_result));
    }
  }

  /**
  | Group | Firebase
  | Description | notify
   */
  public static function notify($firebase_registration_ids, $title, $msg, $data = null, $log = false) {
    $body = static::makeBody($title, $msg);

    if (!is_array($firebase_registration_ids)) {
      $firebase_registration_ids = [$firebase_registration_ids];
    }

    $fields = [
      'registration_ids' => $firebase_registration_ids,
      'notification'     => $body
    ];

    if ($data) {
      $fields['data'] = $data;
    }

    if ($log) {
      Log::add(0, 'Notification data '.json_encode($fields));
    }

    $headers = [
      'Authorization: key='.Config::firebaseAccessKey,
      'Content-Type: application/json'
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
  }
}