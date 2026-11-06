<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$where = [
  'status_confirm' => $_POST['status'],
  'status_checkin' => $_POST['filter_status'],
  'type' => $_POST['type'],
  'from_date' => $_POST['filter_from_date'],
  'to_date' => $_POST['filter_to_date'],
  'is_show_history' => 1
];
$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'] == -1 ? 'all' : $_POST['page_size'],
  'sort' => [
    'insert_date_time' => 'DESC'
  ]
];

$data = phr_checkin::selectCheckIn(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
