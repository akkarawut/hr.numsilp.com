<?php
if ($_POST) {
  $redirect = '';
  if (isset($_POST['submit_edit_working_info'])) {
    $api_result = User::update(
      $user_id,
      [
        'full_name'  => $_POST['full_name'],
        'name'       => $_POST['name'],
        'start_date' => $_POST['start_date'],
        'team_id'    => $_POST['team_id'],
        'team_name'  => $_POST['team_name'],
        'status'     => $_POST['status']
      ]
    );
  } else if (isset($_POST['submit_edit_contact_info'])) {
    $api_result = User::update(
      $user_id,
      [
        'email'        => $_POST['email'],
        'tel'          => $_POST['tel'],
        'line_id'      => $_POST['line_id'],
        'facebook_url' => $_POST['facebook_url'],
        'twitter_url'  => $_POST['twitter_url'],
        'we_chat_url'  => $_POST['we_chat_url'],
        'address'      => $_POST['address']
      ]
    );
  } else if (isset($_POST['submit_change_password'])) {
    $api_result = User::updateUser($user_id, ['password' =>  $_POST['password']]);
  } else if (isset($_POST['submit_ban_user'])) {
    if ($_POST['is_ban']) {
      $api_result = User::unban($user_id);
    } else {
      $api_result = User::ban($user_id);
      $redirect   = 'manage_user.php?c=' . $_GET['c'];
    }
  } else if (isset($_POST['submit_create_profile_img'])) {
    $api_result = User::createProfileImage($user_id, $_FILES['image']);
  } else if (isset($_POST['submit_add_remark'])) {
    $api_result = User::addRemark($user_id, $_POST['remark']);
  } else if (isset($_POST['submit_add_subordinate'])) {
    $api_result = User::addSubordinate($user_id, $_POST['sub_user_id']);
  } else if (isset($_POST['submit_add_leader'])) {
    $api_result = User::addleader($user_id, $_POST['leader_id']);
  } else if (isset($_POST['submit_create_username'])) {
    $api_result = User::createUsername($user_id, $_POST['username'], $_POST['passwords'], '');
  } else if (isset($_POST['submit_add_occupation'])) {
    $api_result = User::update(
      $user_id,
      [
        'occupation' => $_POST['occupation']
      ]
    );
  }

  W_Main::notification($api_result['response_message'], (($api_result['response_status']) ? 'success' : 'error'));
  W_Main::redirect($redirect);
}
