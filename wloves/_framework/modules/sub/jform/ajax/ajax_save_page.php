<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$func = isset($_POST['func']) ? $_POST['func'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$is_edit  = isset($_GET['is_edit']) ? $_GET['is_edit'] : '';
$index  = isset($_POST['index']) ? json_decode($_POST['index'], true)  : '';
$meta_data = json_decode($_POST['meta_data'], true);
$meta_list = isset($meta_data['list']) ? array_values($meta_data['list']) : '';
$edit_data = isset($_POST['edit_data']) ? $_POST['edit_data'] : '';

$get_template = Extra::getExtraTemplateTab($tab_id);
$setting = isset($get_template['meta_data'][0]['setting']) ? $get_template['meta_data'][0]['setting'] : '';
$tab_name = $get_template['meta_data'][0]['tab_name'];
$obj = [];
if ($meta_list != '') {
  foreach ($meta_list as $key => $meta_data) {
    $obj[$key] = json_decode($meta_data['dataset'], true);
  }
}

if ($is_edit == 1) {
  $obj[$index] = json_decode($_POST['edit_data'], true);
}
$data = [
  'tab_name' => $tab_name,
  'meta_data' => $obj,
  'setting' => $setting
];

$result =  Extra::updateExtraTemplateTab($tab_id, $data);
echo json_encode($result);
