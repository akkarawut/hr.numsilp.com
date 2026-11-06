<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'trial_work_status' => isset($_POST['status']) && $_POST['status'] == 'waiting_confirm'  ? $_POST['status'] : ['approved', 'reject'],
  'is_trial_work' =>  1,

];

$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'],
  'sort' => [],
  'is_performance' =>  1,
];
$data = phr_employee::selectEmployee(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
