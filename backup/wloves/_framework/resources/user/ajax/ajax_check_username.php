<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import_for_ajax.php';

$check_username = User::isUsernameExists($_POST['username']);
echo $check_username;
