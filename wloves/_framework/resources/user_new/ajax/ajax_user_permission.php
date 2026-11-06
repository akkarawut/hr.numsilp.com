<?php
require_once '../../../../_framework/import_for_ajax.php';


$function = $_POST['function'];
$check_function = isset($_POST['check_function']) ? $_POST['check_function'] : [];


foreach ($function as $key => $list) {
  $function[$key] = isset($check_function[$key]) ? 1 : 0;
}

$data = [
  'permission' => $function
];

$result = User::updateUser($_POST['id'], $data);

echo json_encode($result);
