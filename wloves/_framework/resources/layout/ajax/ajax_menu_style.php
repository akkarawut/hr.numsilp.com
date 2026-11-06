<?php
require_once '../../../../_framework/import_for_ajax.php';

$is_mini = (isset($_POST['is_mini']) && ($_POST['is_mini'] == 'true')) ? 1 : 0;
W_Main::cookie('_menu_style', $is_mini);
