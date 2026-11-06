<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'date' => $_POST['f_date'],
  'user_id' => $_POST['user_id'],
  'role_ids' => isset($_POST['role_ids']) ? json_decode($_POST['role_ids'], true) : '',
  'status' => 'approved',
  'employee_status' => 'month'
];
$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'],
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
