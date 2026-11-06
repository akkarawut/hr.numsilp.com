<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$type = isset($_POST['type']) ? $_POST['type'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

if ($type == 'ceo') {
  $where = [
    'date' => isset($_POST['date']) ? $_POST['date'] : '',
  ];
} else {
  $where = [
    'from_date' => isset($_POST['from_date']) ? $_POST['from_date'] : '',
    'to_date' =>  isset($_POST['to_date']) ? $_POST['to_date'] : '',
    'user_id' =>  isset($_POST['user_id']) ? $_POST['user_id'] : '',
  ];
}


$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'],
  'sort' => [
    'insert_date_time' => 'DESC'
  ]
];

$data = phr_employee_sale_report::ReportEmployeeSaleTable(API_CODE, $where, $options);

$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
