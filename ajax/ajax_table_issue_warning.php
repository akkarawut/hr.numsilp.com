<?php
require_once '../wloves/_framework/import_for_ajax.php';
W_Structure::loadMetaForAjax('../');

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';


$where = [
  'fullname' => isset($_POST['keyword']) ? $_POST['keyword'] : '',
  'user_code' => isset($_POST['user_filter_code']) ? $_POST['user_filter_code'] : '',
];

if ($user_id) {
  $where['user_id'] = $user_id;
}

$options = [
  'total_count' => true,
  'page_no' => $_POST['page_no'],
  'page_size' => $_POST['page_size'] == -1 ? 'all' : $_POST['page_size'],
  'sort' => [
    'insert_date_time' => 'DESC'
  ]
];

$data = phr_document_warning::selectDocumentWarning(API_CODE, $where, $options);
$return = [
  'data' => $data['list'],
  'recordsTotal' => $data['total_count'],
  'recordsFiltered' => $data['total_count'],
];

echo json_encode($return);
