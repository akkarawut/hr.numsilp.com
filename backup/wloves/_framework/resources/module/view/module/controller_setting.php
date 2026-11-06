<?php
require_once '../../../../../_framework/import_for_ajax.php';

$code = $_GET['c'];
$page = $_GET['page'];

if ($_POST) {
  if (isset($_POST['submit_setting_account'])) {
    $data_post = [
      'is_title' => isset($_POST['is_title']) ? 1 : 0,
      'title_name' => $_POST['title_name'],
      'title_font_size' => $_POST['title_font_size'],
      'title_font_bold' => isset($_POST['title_font_bold']) ? 1 : 0,
      'title_font_color' => isset($_POST['title_font_color']) ? $_POST['title_font_color'] : $_POST['title_font_color_custom'],
      'username_font_size' => $_POST['username_font_size'],
      'username_font_bold' => isset($_POST['username_font_bold']) ? 1 : 0,
      'username_font_color' => isset($_POST['username_font_color']) ? $_POST['username_font_color'] : $_POST['username_font_color_custom'],
      'password_type' => $_POST['password_type'],
      'is_edit_password' => isset($_POST['is_edit_password']) ? 1 : 0,
      'is_verify_password' => isset($_POST['is_verify_password']) ? 1 : 0,
      'is_update_date_time' => isset($_POST['is_update_date_time']) ? 1 : 0,
      'alignment' => $_POST['alignment'],
      'type_display' => $_POST['type_display'],
    ];


    $data = [
      'json_tab_account_info' => $data_post
    ];

    $result = Module::updateModule($_POST['id'], $data);
  } else if (isset($_POST['submit_setting_profile'])) {
    $data_post = [
      'profile_type' => $_POST['profile_type'],
      'profile_size' => $_POST['profile_size'],
      'alignment' => $_POST['alignment'],
      'is_show_detail' => isset($_POST['is_show_detail']) ? 1 : 0,
      'is_show_add' => isset($_POST['is_show_add']) ? 1 : 0,
      'is_show_profile' => isset($_POST['is_show_profile']) ? 1 : 0,
      'is_update' => isset($_POST['is_update']) ? 1 : 0,
      'is_required' => isset($_POST['is_required']) ? 1 : 0,
    ];
    $data = [
      'json_image_profile' => $data_post
    ];

    $result = Module::updateModule($_POST['id'], $data);
  } else if (isset($_POST['submit_setting_permission'])) {
    $data_post = [
      'is_show_add_page' => isset($_POST['is_show_add_page']) ? 1 : 0,
      'is_show_add_profile' => isset($_POST['is_show_add_profile']) ? 1 : 0,
    ];

    $data = [
      'json_tab_permission' => $data_post
    ];
    $result = Module::updateModule($_POST['id'], $data);
  }
  $redirect = '../../module_detail.php?c=' . $code . '&page=' . $page;
  W_Main::responseAPI();
}
