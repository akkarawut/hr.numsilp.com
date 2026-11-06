<?php
class Notification {
  /**
  | Group | Notification
  | Description | Set Notification
   */
  public static function set($code, $module, $user_id, $type, $topic, $description, $link = '') {
    if (!$code || !$module || !$type || !$topic || !$description) {
      return false;
    }

    if (!in_array($type, ['dev', 'user'], true)) {
      return false;
    }

    $user_info = Amst::getByID(Info::$system_table[$type], '*', $user_id);
    if (!$user_info) {
      return false;
    }

    $data = [
      'code'        => $code,
      'module'      => $module,
      'type'        => $type,
      'user_id'     => $user_id,
      'topic'       => $topic,
      'description' => $description,
      'link'        => $link
    ];

    $add = Amst::insert(Info::$system_table[$type.'_notification'], $data);

    return $add;
  }

  /**
  | Group | Notification
  | Description | Set Multiple Notification
   */
  public static function setMultiple($code, $module, $page_name, $type, $topic, $description, $link = '') {
    if (!$code || !$module || !$page_name || !$type || !$topic || !$description) {
      return false;
    }

    if (!in_array($type, ['dev', 'user'], true)) {
      return false;
    }

    $where = ['permission' => $code.'_'.$page_name];
    $permissions = Amst::selectNoKey(Info::$system_table[$type.'_permission'], ['user_id'], $where);

    foreach ($permissions as $permission) {
      $data = [
        'code'        => $code,
        'module'      => $module,
        'type'        => $type,
        'user_id'     => $permission['user_id'],
        'topic'       => $topic,
        'description' => $description,
        'link'        => $link
      ];
      $add = Amst::insert(Info::$system_table[$type.'_notification'], $data);
    }

    return true;
  }

  /**
  | Group | Notification
  | Description | Select Notification
   */
  public static function select($user_id, $type) {
    if (!$user_id) {
      return false;
    }

    if (!in_array($type, ['dev', 'user'], true)) {
      return false;
    }

    $user_info = Amst::getByID(Info::$system_table[$type], '*', $user_id);
    if (!$user_info) {
      return false;
    }

    $rep = [
      'count_is_read' => 0,
      'notifications' => []
    ];

    $where = [
      'user_id' => $user_id,
      'LIMIT'   => 20
    ];
    $select = Amst::selectNoKey(Info::$system_table[$type.'_notification'], '*', $where);

    $count_is_read = 0;
    foreach ($select as $data) {
      if ($data['is_read'] == 0) {
        $count_is_read += 1;
      }
    }

    if ($select) {
      $rep['count_is_read'] = $count_is_read;
      $rep['notifications'] = $select;
    }

    return $rep;
  }

  /**
  | Group | Notification
  | Description | Read Notification
   */
  public static function read($user_id, $type) {
    if (!$user_id) {
      return false;
    }

    if (!in_array($type, ['dev', 'user'], true)) {
      return false;
    }

    $user_info = Amst::getByID(Info::$system_table[$type], '*', $user_id);
    if (!$user_info) {
      return false;
    }

    $where = [
      'user_id' => $user_id
    ];

    $rep = Amst::update(Info::$system_table[$type.'_notification'], ['is_read' => 1], $where);

    return $rep;
  }
}
