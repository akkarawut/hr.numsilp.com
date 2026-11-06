<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'status' => isset($_POST['status']) && $_POST['status'] == 'waiting_confirm'  ? $_POST['status'] : ['approved', 'reject'],
];

if ($_POST['month_filter']) {
  $where['status_update_datetime[<>]'] = [date($_POST['month_filter'] . '-01 00:00:00'), date($_POST['month_filter'] . '-t 23:59:59')];
}

$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'],
  'sort' => []
];
$data = phr_commission::selectCommission(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
