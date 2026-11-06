<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code = $_GET['c'];
$field_name = $_POST['field_name'];
$where = [
  'name' => $field_name
];
$result =  Extra::selectExtraColumns($code, $where);
echo json_encode($result);
