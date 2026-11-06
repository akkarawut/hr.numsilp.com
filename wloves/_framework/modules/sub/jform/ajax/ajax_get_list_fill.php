<?php

$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax($prefix);

$code  = isset($_GET['c']) ? $_GET['c'] : '';
$tab_id = isset($_GET['tab']) ? $_GET['tab'] : '';
$template = isset($_GET['template']) ? $_GET['template'] : '';
$func = isset($_GET['func']) ? $_GET['func'] : '';
$obj = [];
$file = [];

if ($tab_id) {
  $result = Extra::getExtraTemplateTab($tab_id);
  $data_template = $result['meta_data'][0];
} else {
  $result = Extra::getExtraTemplate($func, $template);
  $data_template = $result['meta_data'][0];
}

if ($data_template) {
  foreach ($data_template['meta_data'] as $key => $data) {
    if (isset($data['unique_name'])) {
      $obj[$key] = $data['unique_name'];
    }
  }
  foreach ($obj as $key => $file_path) {
    $file[$key] = File::getPath($file_path);
  }
}

$return = [
  'list' => [
    'file_path' => $file,
    'meta_data' => $data_template['meta_data'],
  ],
  'tab_setting' => isset($data_template['setting']) ? $data_template['setting'] : '',
];
echo json_encode($return);
