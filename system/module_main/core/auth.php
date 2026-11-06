<?php
class Auth {
  public static function getHealth() {
    $return = [
      'status'  => 'Online',
      'version' => '1.0.1'
    ];

    // [LATER] use info ?
    // $return['version'] = Info::version_main.'.'.Info::version_sub.'.'.Info::version_hotfix;

    return $return;
  }

  public static function getAccessToken($authorization_code = '') {
    $return = [
      'access_token' => '',
      'expires_in'   => 1800
    ];

    // Retrieve API access token (Bearer Token) from API server
    if (!$authorization_code) {
      return rep401('request Unauthorized successfully');
    }

    $model = Amst::get('sys_api_key', ['id', 'api_key'], ['site_name' => 'ios']);
    if (isset($model['id'])) {
      $access_token = $model['api_key'];
    }
    if (!$access_token) {
      return rep401('request Unauthorized successfully');
    }
    $return['access_token'] = $access_token;

    return $return;
  }
}
