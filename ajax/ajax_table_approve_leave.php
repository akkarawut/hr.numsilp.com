<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$where = [
  'status_confirm' => $_POST['status'],
  'from_date' =>  isset($_POST['filter_from_date']) ? $_POST['filter_from_date'] : '',
  'to_date' =>  isset($_POST['filter_to_date']) ? $_POST['filter_to_date'] : '',
];
$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'] == -1 ? 'all' : $_POST['page_size'],
  'sort' => [
    'insert_date_time' => 'DESC'
  ]
];

$data = phr_leave_work::selectLeaveWork(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
