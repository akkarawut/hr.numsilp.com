<?php
require_once 'wloves/_framework/import_for_website.php';
$result = User::logout();
if ($result['response_status']) {
  session_destroy();
  W_Main::redirect('login.php');
}
