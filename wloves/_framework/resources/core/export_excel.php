<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import.php';

if (!headers_sent()) {
  header_remove();
}
$where = [
  'search_text' => ''
];
$options = [
  'export' => 'excel'
];

$api_keys = API::selectApiKeyDowload('123');
// W_Main::display($api_keys);
