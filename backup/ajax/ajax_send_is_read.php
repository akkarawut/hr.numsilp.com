<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$id = $_GET['id'];

$result = phr_notification::readNotification(API_CODE, $id);
echo json_encode($result);
