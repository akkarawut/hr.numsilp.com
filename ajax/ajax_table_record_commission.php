<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');
$where = [
  'status' => isset($_POST['status']) && $_POST['status'] == 'waiting_confirm'  ? $_POST['status'] : ['approved', 'reject'],
];

if ($_POST['month_filter']) {
  // 't' ต้องคำนวณจากเดือนที่เลือก (เดิมได้จำนวนวันของเดือนปัจจุบัน เช่น 2026-02-30)
  $month_start = strtotime($_POST['month_filter'] . '-01');
  $where['status_update_datetime[<>]'] = [date('Y-m-01 00:00:00', $month_start), date('Y-m-t 23:59:59', $month_start)];
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
