<?php
  $_PAGE['permission'] = ['core', 'core_program_setting', 'core_program_setting_setup'];
  require_once '../../../../_framework/import.php';
  if($_POST['type'] == 'submit_add_allow_login') {
    $result = User_type::updateUserType($_POST['id'], $_POST['data']);
    echo json_encode($result);
  } else if($_POST['type'] == 'submit_cancel_allow_login') {
    $result = User_type::updateUserType($_POST['id'], $_POST['data']);
    echo json_encode($result);
  } else if($_POST['type'] == 'submit_add_pin_code') {
    $result = User_type::updateUserType($_POST['id'], $_POST['data']);
    echo json_encode($result);
  } else if($_POST['type'] == 'submit_cancel_pin_code') {
    $result = User_type::updateUserType($_POST['id'], $_POST['data']);
    echo json_encode($result);
  }

?>