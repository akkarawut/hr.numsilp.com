<?php
require_once '../../../../_framework/import_for_ajax.php';


$permission = $_POST['permission'];
$function = $_POST['function'];
$check_permission = isset($_POST['check_permission']) ? $_POST['check_permission'] : [];
$check_function = isset($_POST['check_function']) ? $_POST['check_function'] : [];

foreach ($permission as $key => $list) {
  $permission[$key] = isset($check_permission[$key]) ? 1 : 0;
}
foreach ($function as $key => $list) {
  $function[$key] = isset($check_function[$key]) ? 1 : 0;
}

$data = [
  'hidden_permission' => $permission,
  'setting_permission' => $function
];

$result = Module::updateModule($_POST['id'], $data);

echo json_encode($result);
