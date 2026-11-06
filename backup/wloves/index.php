<?php
$_WLOVES['no_check_permission'] = 1;
require '_framework/import.php';

if (W_User::isLoginUser()) {
  W_Permission::redirectToFirstModuleAvailable();
} else if (W_User::isDev()) {
  W_Main::redirect('_framework/resources/log/');
}

W_Main::redirect('_framework/resources/login/index.php');
