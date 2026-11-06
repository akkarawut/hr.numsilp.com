<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$id  = isset($_GET['id']) ? $_GET['id'] : '';
$result =  Extra::getExtraColumnsByID($id);
echo json_encode($result);
