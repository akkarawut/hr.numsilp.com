<?php
require_once '../../../../_framework/import_for_ajax.php';

$data_input = file_get_contents('php://input');
$data_input = json_decode($data_input, true);

if ($data_input['data_table']) {
  $data_input = json_decode(base64_decode($data_input['data_table']), true);
  $result = DB_Init::ignoreColumns($data_input);
} else {
  $result = [
    'response_status' => false,
  ];
}

echo json_encode($result);
