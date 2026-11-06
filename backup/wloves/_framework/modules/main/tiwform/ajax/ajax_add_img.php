<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../import_for_ajax.php';

$result = File::add($_POST['code'], $_FILES['once_img']);
echo json_encode($result);
