<?php
class User_Subordinate {
  /**
  | Group | Operation
  | Description | add subordinate to user
   */
  static function addSubordinate($user_id, $sub_user_id) {
    $rep = [];

    if (!$user_id || !$sub_user_id) {
      return Amst::repError('Invalid input data');
    }

    if (!is_array($sub_user_id)) {
      return Amst::repError('Invalid sub_user_id id');
    }

    $user_info = static::getByID('*', $user_id);
    if (!$user_info) {
      return Amst::repError('User not found');
    }

    // clear subordinate
    $where      = ['user_id' => $user_id];
    $delete_sub = Amst::delete(Info::$system_table['user_subordinate'], $where);

    $where = ['id' => $sub_user_id];
    $field = [
      'id',
      'full_name'
    ];
    $sub_user_list = static::select($field, $where);
    if (!$sub_user_list) {
      return Amst::repError('Subordinate not found');
    }

    foreach ($sub_user_id as $key => $sub_id) {
      if (!isset($sub_user_list[$sub_id])) {
        continue;
      }

      $data_use = [
        'user_id'     => $user_id,
        'user_sub_id' => $sub_id
      ];
      $insert = Amst::insert(Info::$system_table['user_subordinate'], $data_use);
    }

    return Amst::repSuccess();
  }

  /**
  | Group | Operation
  | Description | add subordinate to user
   */
  static function addleader($user_id, $leader_user_id) {
    $rep = [];

    if (!$user_id || !$leader_user_id) {
      return Amst::repError('Invalid input data');
    }

    if (!is_array($leader_user_id)) {
      return Amst::repError('Invalid sub_user_id id');
    }

    $user_info = static::getByID('*', $user_id);
    if (!$user_info) {
      return Amst::repError('User not found');
    }

    // clear leader
    $where         = ['user_sub_id' => $user_id];
    $delete_leader = Amst::delete(Info::$system_table['user_subordinate'], $where);

    $where = ['id' => $leader_user_id];
    $field = [
      'id',
      'full_name'
    ];
    $leader_list = static::select($field, $where);
    if (!$leader_list) {
      return Amst::repError('Leader not found');
    }

    foreach ($leader_user_id as $key => $leader_id) {
      if (!isset($leader_list[$leader_id])) {
        continue;
      }

      $data_use = [
        'user_id'     => $leader_id,
        'user_sub_id' => $user_id
      ];
      $insert = Amst::insert(Info::$system_table['user_subordinate'], $data_use);
    }

    return Amst::repSuccess();
  }

  /**
  | Group | Operation
  | Description | select subordinate data
   */
  static function selectSubordinate($user_id) {
    $return = [
      'subordinate_list' => [
        0 => [
          'team_name' => 'test',
          'user_list' => []
        ]
      ]
    ];

    if (!$user_id) {
      return $return;
    }

    $user_info = static::getByID('*', $user_id);
    if (!$user_info) {
      return $return;
    }

    $where = ['id[!]' => $user_id];
    $field = [
      'id',
      'full_name'
    ];
    $list_user = static::selectNoKeyImage($field, $where);

    $where = ['user_id' => $user_id];
    $field = [
      'id',
      'user_id',
      'user_sub_id'
    ];
    $subordinate_list  = Amst::selectNoKey(Info::$system_table['user_subordinate'], $field, $where);
    $subordinate_group = [];

    foreach ($subordinate_list as $key => $value) {
      if (!isset($subordinate_group[$value['user_sub_id']])) {
        $subordinate_group[$value['user_sub_id']] = $value;
      }
    }

    foreach ($list_user as $key => $value) {
      $list_user[$key]['is_subordinate'] = 0;
      if (isset($subordinate_group[$value['id']])) {
        $list_user[$key]['is_subordinate'] = 1;
      }
    }

    $return['subordinate_list'][0]['user_list'] = $list_user;

    return $return;
  }

  /**
  | Group | Operation
  | Description | select leader data
   */
  static function selectLeader($user_id) {
    $return = [
      'leader_list' => [
        0 => [
          'team_name' => 'test',
          'user_list' => []
        ]
      ]
    ];

    if (!$user_id) {
      return $return;
    }

    $user_info = static::getByID('*', $user_id);
    if (!$user_info) {
      return $return;
    }

    $where = ['id[!]' => $user_id];
    $field = [
      'id',
      'full_name'
    ];
    $list_user = static::selectNoKeyImage($field, $where);

    $where = ['user_sub_id' => $user_id];
    $field = [
      'id',
      'user_id',
      'user_sub_id'
    ];
    $leader_list  = Amst::selectNoKey(Info::$system_table['user_subordinate'], $field, $where);
    $leader_group = [];

    foreach ($leader_list as $key => $value) {
      if (!isset($leader_group[$value['user_id']])) {
        $leader_group[$value['user_id']] = $value;
      }
    }

    foreach ($list_user as $key => $value) {
      $list_user[$key]['is_leader'] = 0;
      if (isset($leader_group[$value['id']])) {
        $list_user[$key]['is_leader'] = 1;
      }
    }

    $return['leader_list'][0]['user_list'] = $list_user;

    return $return;
  }
}
