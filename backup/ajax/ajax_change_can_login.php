<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$id = $_GET['id'];
$is_can_login = $_GET['is_can_login'] ? 1 : 0;

$result = phr_employee::updateEmployeeLogin(API_CODE, $id, ['is_can_login' => $is_can_login]);
echo json_encode($result);
