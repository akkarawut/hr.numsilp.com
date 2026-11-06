<?php
require_once '../../../../_framework/import_for_ajax.php';
$result = Module::countNavModule();
echo json_encode($result);
