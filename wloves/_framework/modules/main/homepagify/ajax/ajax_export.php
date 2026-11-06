<?php
$_WLOVES['no_check_permission'] = 1;
require_once  '../../../../_framework/import_for_ajax.php';

$where = [
  'search_text' => ''
];
$options = [
  'export' => 'excel'
];
$api_keys = API::selectApiKeyDowload('123');

echo json_encode($api_keys);
