<?php
$_WLOVES['no_check_permission'] = 1;
require_once '../../../../_framework/import.php';

$check_username = W_User::isUsernameExists($_POST['username']);
echo $check_username;
