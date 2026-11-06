<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'user_fullname' => isset($_POST['keyword']) ? $_POST['keyword'] : '',
  'user_filter_code' => isset($_POST['user_filter_code']) ? $_POST['user_filter_code'] : '',
  'status' => ['draft', 'reject'],
  'employee_status' => $_POST['type'],
];
$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'] == -1 ? 'all' : $_POST['page_size'],
  'sort' => [
    'insert_date_time' => 'DESC'
  ]
];

$data = phr_salary::selectSalary(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
