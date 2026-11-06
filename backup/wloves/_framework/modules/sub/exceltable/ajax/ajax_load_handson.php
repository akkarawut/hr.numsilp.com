<?php
$prefix = '../../../../../';
require_once $prefix . '_framework/import_for_ajax.php';

$code = $_POST['api_select']['code'];
$page_no = $_POST['page_no'];
$page_size = $_POST['page_size'];
$temp_search = isset($_POST['temp_search']) ? $_POST['temp_search'] : [];
$api_select = $_POST['api_select'];

$options = [
  'total_count' => true,
  'page_no'     => $page_no,
  'page_size'   => $page_size
];

$where = [];

foreach ($temp_search as $idx => $temp) {
  // if ($temp['key'] == 'receive_date_time' || $temp['key'] == 'ship_out_date') {
  //   $date = explode('/', $temp['value']);
  //   $where['handson_' . $temp['key']] = $date[2] . '-' . $date[1] . '-' . $date[0];
  // } else {
  //   $where['handson_' . $temp['key']]  = $temp['value'];
  // }
  $where[$temp['key']]  = $temp['value'];
}

$api = $api_select['class'] . '::' . $api_select['function'];
$data_select = $api($code, $where, $options);

foreach ($data_select['list'] as $idx => $val) {

  $data_select['list'][$idx]['checkbox'] = false;

  // if ($val['receive_date_time']) {
  //   $product_warehouse['list'][$idx]['receive_date_time'] = $val['receive_date_time'] ?  W_Main::formatDate($val['receive_date_time'], 'd/m/Y') : '';
  // }
}

echo json_encode($data_select);
