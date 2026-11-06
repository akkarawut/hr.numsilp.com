<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$tab_id  = isset($_GET['tab']) ? $_GET['tab'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';
$func = isset($_POST['func']) ? $_POST['func'] : '';
$tab_setting = isset($_POST['tab_setting']) ? json_decode($_POST['tab_setting'], true) : '';
$meta_data = json_decode($_POST['meta_data'], true);
$meta_list = isset($meta_data['list']) ? array_values($meta_data['list']) : '';

$obj = [];
if ($meta_list != '') {
  foreach ($meta_list as $key => $meta_data) {
    $obj[$key] = json_decode($meta_data['dataset'], true);
  }
}

$data = [
  'meta_data' => $obj,
  'setting' => $tab_setting,
];


$result =  Extra::updateExtraTemplateTab($tab_id, $data);
echo json_encode($result);
