<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../_framework/import_for_ajax.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
if ($type == 'user') {
  User::logout();
} else {
  User::logout();
  Dev::logout();
}

W_Main::redirect('../login/index.php');
