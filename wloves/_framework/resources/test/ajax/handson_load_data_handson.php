<?php
$_WLOVES['no_check_permission'] = 1;
require_once  '../../../../_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax();
$code = $_GET['c'];

$page_no = $_POST['page_no'];
$page_size = $_POST['page_size'];
$temp_search = isset($_POST['temp_search']) ? $_POST['temp_search'] : [];
$status = ["รอเข้าโกดังจีน", "เข้าโกดังจีน", "รอเข้าไทย"];

$options = [
  'total_count' => true,
  'page_no'     => $page_no,
  'page_size'   => $page_size
];

$where = [
  'handson_status' =>  $status
];

foreach ($temp_search as $idx => $temp) {
  if ($temp['key'] == 'receive_date_time' || $temp['key'] == 'ship_out_date') {
    $date = explode('/', $temp['value']);
    $where['handson_' . $temp['key']] = $date[2] . '-' . $date[1] . '-' . $date[0];
  } else {
    $where['handson_' . $temp['key']]  = $temp['value'];
  }
}

$product_warehouse = ctc_product_warehouse::selectProductWarehouse($code, $where, $options);
foreach ($product_warehouse['list'] as $idx => $val) {

  $product_warehouse['list'][$idx]['checkbox'] = false;

  if ($val['receive_date_time']) {
    $product_warehouse['list'][$idx]['receive_date_time'] = $val['receive_date_time'] ?  W_Main::formatDate($val['receive_date_time'], 'd/m/Y') : '';
  }
}

echo json_encode($product_warehouse);
