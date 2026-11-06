<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'user_id' => $_POST['user_id'],
  // 'date' => isset($_POST['month_filter']) ? $_POST['month_filter'] : '',
];
$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'],
  'sort' => []
];
$data = phr_ot::selectOT(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
