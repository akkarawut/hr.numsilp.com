<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code = $_GET['c'];
$template_id = $_POST['template_id'];
$order_tab = json_decode($_POST['order_tab'], true);

$result =  Extra::sortTabs($template_id, $order_tab);
echo json_encode($result);
