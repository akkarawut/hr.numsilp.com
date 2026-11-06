<?php
class User_Social {

  /**
  | Group | USER SOCIAL
  | Description | 'USER SOCIAL'
  | $social_id | social_id
   */
  public static function checkSocialID($social_id) {
    $where = [
      'AND' => [
        'social_id'     => $social_id,
        'system_delete' => 0
      ]
    ];

    $check = Amst::get('sys_user_social', '*', $where);
    if (isset($check['id'])) {
      return Amst::repError('E0000', 'Social ID already used.');
    }
    return Amst::repSuccess(true);
  }

  /**
  | Group | USER SOCIAL
  | Description | 'USER SOCIAL'
  | $user_id | user_id
   */
  public static function getUserSocial($user_id) {
    $where = [
      'AND' => [
        'user_id'       => $user_id,
        'social_id[!]'  => '',
        'system_delete' => 0
      ]
    ];
    $api = Amst::select('sys_user_social', ['user_id', 'social', 'social_id'], $where);
    return $api;
  }

  /**
  | Group | USER SOCIAL
  | Description | 'USER SOCIAL'
  | $user_id | user_id
  | $social | social
  | $social_id | social_id
   */
  public static function linkUserSocial($user_id, $social, $social_id) {
    $social    = trim(strtolower($social));
    $social_id = trim(strtolower($social_id));

    $user = Amst::getByID('sys_user', '*', $user_id);
    if (!isset($user['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }
    if (!$social || !$social_id) {
      return Amst::repError('E0000', 'Social data not found.');
    }

    //check if social is already linked
    $where = [
      'AND' => [
        'user_id[!]'    => $user_id,
        'social_id'     => $social_id,
        'system_delete' => 0
      ]
    ];
    $check = Amst::get('sys_user_social', '*', $where);
    if (isset($check['id'])) {
      return Amst::repError('E0000', 'Social already linked to another user.');
    }

    $where = [
      'AND' => [
        'user_id'       => $user_id,
        'social'        => $social,
        'system_delete' => 0
      ]
    ];
    $linked = Amst::get('sys_user_social', '*', $where);
    if (isset($linked['id'])) {
      if ($linked['social_id']) {
        return Amst::repError('E0000', 'Social already linked.');
      } else {
        $u_data = [
          'social_id' => $social_id
        ];
        $api = Amst::updateByID('sys_user_social', $u_data, $linked['id']);
        return $api;
      }
    }
    $i_data = [
      'user_id'   => $user_id,
      'social'    => $social,
      'social_id' => $social_id
    ];
    $api = Amst::insert('sys_user_social', $i_data);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
      $insert_id = $api['response_data']['insert_id'];
    }
    return $api;
  }

  /**
  | Group | USER SOCIAL
  | Description | 'USER SOCIAL'
  | $user_id | user_id
  | $social | social
   */
  public static function unlinkUserSocial($user_id, $social) {
    $where = [
      'AND' => [
        'user_id'       => $user_id,
        'social'        => $social,
        'system_delete' => 0
      ]
    ];
    $u_data = [
      'social_id' => ''
    ];
    $api = Amst::update('sys_user_social', $u_data, $where);
    if (isset($api['response_status']) && ($api['response_status'] == true)) {
    }
    return $api;
  }

  /**
  | Group | USER LOGIN SOCIAL
  | Description | 'USER LOGIN SOCIAL'
  | $social_id | social_id
   */
  public static function loginUserSocial($social_id, $options = []) {
    $where = [
      'AND' => [
        'social_id'     => $social_id,
        'system_delete' => 0
      ]
    ];
    $get_link_social = Amst::get('sys_user_social', '*', $where);
    if (!isset($get_link_social['id'])) {
      return Amst::repError('E0000', 'User Social not found.');
    }

    $user = Amst::getByID('sys_user', '*', $get_link_social['user_id']);
    if (!isset($user['id'])) {
      return Amst::repError('E0000', 'User not found.');
    }
    if ($user['is_ban'] == 1) {
      return Amst::repError('E0000', 'User banned.');
    }

    $username     = $user['username'];
    $md5_password = $user['password'];
    $ex_data      = [
      'remember_me'      => true,
      'encoded_password' => true,
      'site_name'        => isset($options['site_name']) ? $options['site_name'] : null
    ];
    return User::login($username, $md5_password, $ex_data);
  }
}
?>