<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../import_for_ajax.php';

$result = File::delete($_POST['code']);
echo json_encode($result);
