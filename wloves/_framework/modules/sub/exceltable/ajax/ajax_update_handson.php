<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';

$code = $_POST['api_update']['code'];
$id = $_POST['id'];
$key = $_POST['key'];
$value = $_POST['value'];
$api_update = $_POST['api_update'];

$data = [
  $key => $value
];

$api = $api_update['class'] . '::' . $api_update['function'];

$result = $api($code, $id, $data);

echo json_encode($result);
