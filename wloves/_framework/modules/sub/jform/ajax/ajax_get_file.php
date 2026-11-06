<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$unique_name = isset($_POST['file']) ? $_POST['file'] : '';
$result = File::getPath($unique_name);
$return = [
  'unique_name' => $result
];
echo json_encode($return);
