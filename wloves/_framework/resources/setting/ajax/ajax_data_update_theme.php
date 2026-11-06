<?php
require_once '../../../../_framework/import_for_ajax.php';


$data_input = file_get_contents('php://input');
$data_input = json_decode($data_input, true);
$data_send = [
  $data_input['name'] => $data_input['color'],
];
$result = WLoves::setProgramSetup($data_send);
echo json_encode($result);
