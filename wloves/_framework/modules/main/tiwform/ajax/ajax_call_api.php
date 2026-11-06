<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../import_for_ajax.php';

$data_edit = preg_replace('/(\r|\n|"|\')/', '', trim($_POST['data_edit']));
$text_params = json_encode($_POST['params']['params']);
$text_params = str_replace('{' . $_POST['data_key'] . '}', $data_edit, $text_params);
$json_params = json_decode($text_params, true);

$result = call_user_func_array($_POST['params']['api'], $json_params);
echo json_encode($result);
